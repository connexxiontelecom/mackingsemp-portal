<?php

namespace App\Controllers;

class DepositLodgement extends BaseController
{
  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Deposit/Lodgement';
      $page_data['coop_banks'] = $this->_get_coop_banks();
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      return view('service-forms/deposit-lodgement', $page_data);
    }
    return redirect('auth/login');
  }

  function get_active_loans()
  {
    $staff_id = $this->session->staff_id;
    $response_data = array();

    $ledgers = $this->loanModel->where(['staff_id' => $staff_id, 'disburse' => 1, 'paid_back' => 0])->findAll();
    foreach ($ledgers as $key => $ledger) {
      $total_cr = 0;
      $total_dr = 0;
      $total_interest = 0;

      $ledger_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $ledger['loan_id']);
      if (!empty($ledger_details)) {
        foreach ($ledger_details as $ledger_detail) {
          if ($ledger_detail->lr_dctype == 1) {
            $total_cr += $ledger_detail->lr_amount;
          }
          if ($ledger_detail->lr_dctype == 2) {
            $total_dr += $ledger_detail->lr_amount;
          }
          if ($ledger_detail->lr_interest == 1) {
            $total_interest += $ledger_detail->lr_amount;
          }
        }
      } else {
        $ledger_details = $this->loanModel->get_cooperator_loans_no_repayment_by_staff_id_loan_id($staff_id, $ledger['loan_id']);
      }
      $ledgers[$key]['loan_type'] = $this->loanSetupModel->find($ledger['loan_type']);
      $ledgers[$key]['loan_principal'] = number_format($ledger_details[0]->amount, 2);
      $ledgers[$key]['loan_balance'] = number_format($ledger_details[0]->amount + ($total_dr - $total_cr), 2);
    }
    $response_data['success'] = true;
    $response_data['ledgers'] = $ledgers;
    return $this->response->setJSON($response_data);
  }

  function get_savings_types()
  {
    $staff_id = $this->session->staff_id;
    $response_data = array();
    $response_data['success'] = true;
    $response_data['savings_types'] = $this->_get_savings_types($staff_id);
    return $this->response->setJSON($response_data);
  }

  function submit_deposit()
  {
    $staff_id = $this->session->get('staff_id');
    $staff_status = $this->session->get('status');
    $response_data = array();
    $account_closure = $this->accountClosureModel->check_account_closure($staff_id);
    $pending_deposits = $this->receiptMasterModel->where(['rm_staff_id' => $staff_id, 'rm_status' => 0])->findAll();

    // check if account is undergoing closure
    if (!empty($account_closure)) {
      $response_data['success'] = false;
      $response_data['msg'] = 'Your account is currently undergoing closure';
      return $this->response->setJSON($response_data);
    }
    // verify staff status
    if ($staff_status != 2) {
      $response_data['success'] = false;
      $response_data['msg'] = 'Your account is currently frozen';
      return $this->response->setJSON($response_data);
    }
    // check for pending deposits
    if (!empty($pending_deposits)) {
      $response_data['success'] = false;
      $response_data['msg'] = 'You currently have pending deposit submissions';
      return $this->response->setJSON($response_data);
    }

    $post_data = $this->request->getPost();
    $payment_date = date('Y-m-d', strtotime($post_data['payment_date']));
    $payment_amount = str_replace(',', '', $post_data['payment_amount']);
    // validate payment amount
    if ($payment_amount <= 0) {
      $response_data['success'] = false;
      $response_data['msg'] = 'Your payment amount must be greater than 0';
      return $this->response->setJSON($response_data);
    }
    $purpose_breakdown = json_decode($post_data['purpose_breakdown']);
    $purpose_amount = 0;
    foreach ($purpose_breakdown as $breakdown) {
      $amount = str_replace(',', '', $breakdown->schemeAmount);
      $purpose_amount += $amount;
    }
    // validate payment amount and purpose amount
    if ($payment_amount != $purpose_amount) {
      $response_data['success'] = false;
      $response_data['msg'] = 'Your payment amount must be equal to the sum of your breakdown amounts';
      return $this->response->setJSON($response_data);
    }

    $receipt_master_data = array(
      'rm_staff_id' => $staff_id,
      'rm_date' => $payment_date,
      'rm_amount' => $payment_amount,
      'rm_payment_method' => $post_data['payment_type'],
      'rm_coop_bank' => $post_data['coop_bank'],
      'rm_status' => 0,
      'rm_a_date' => date("Y-m-d"),
      'rm_by' => $this->session->firstname . ' ' . $this->session->lastname,
    );

    $rm_id = $this->receiptMasterModel->insert($receipt_master_data);
    if (!$rm_id) {
      $response_data['success'] = false;
      $response_data['msg'] = 'There was an error while submitting your deposit. Please contact support';
      return $this->response->setJSON($response_data);
    }
    $count = 0;
    foreach ($purpose_breakdown as $breakdown) {
      $amount = str_replace(',', '', $breakdown->schemeAmount);
      $receipt_detail_data = array(
        'rd_rm_id' => $rm_id,
        'rd_amount' => $amount,
        'rd_type' => $breakdown->schemeType,
        'rd_target' => $breakdown->scheme
      );
      $count++;
      $this->receiptDetailModel->save($receipt_detail_data);
    }
    if ($count != count($purpose_breakdown)) {
      $response_data['success'] = false;
      $response_data['msg'] = 'There was an error while submitting your deposit. Please contact support';
      return $this->response->setJSON($response_data);
    }
    $response_data['success'] = true;
    $response_data['msg'] = 'You have successfully submitted a deposit of ' . number_format($payment_amount, 2);

    return $this->response->setJSON($response_data);
  }

  private function _get_coop_banks()
  {
    $coop_banks = $this->coopBankModel->findAll();
    foreach ($coop_banks as $key => $coop_bank) {
      $bank = $this->bankModel->find($coop_bank['bank_id']);
      $coop_banks[$key]['bank'] = $bank;
    }
    return $coop_banks;
  }

}