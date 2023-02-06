<?php

namespace App\Controllers;

class WithdrawalApplication extends BaseController
{

  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Withdrawal Application';
      $page_data['savings_types'] = $this->_get_savings_types($staff_id);
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      $page_data['bank'] = $this->bankModel->where('bank_id', $page_data['cooperator']['cooperator_bank_id'])->first();
      return view('service-forms/withdrawal-application', $page_data);
    }
    return redirect('auth/login');
  }

  function compute_balance($savings_type)
  {
    $staff_id = $this->session->get('staff_id');
    $status = $this->session->get('status');
    if ($status == 2) {
      $savings_type_detail = $this->contributionTypeModel->find($savings_type);
      $encumbered_amount = 0;
      if ($savings_type_detail['contribution_type_regular'] == 1) {
        $active_loans = $this->loanModel->where([
          'staff_id' => $staff_id,
          'paid_back' => 0,
          'disburse' => 1
        ])->findAll();
        foreach ($active_loans as $active_loan) {
          $encumbered_amount += $active_loan['encumbrance_amount'];
        }
      }
      $savings_amount = $this->_get_savings_type_amount($staff_id, $savings_type);
      $actual_savings_amount = $savings_amount - $encumbered_amount;
      $withdrawable_amount = 0;
      $policy_config = $this->policyConfigModel->first();
      $max_withdrawal = $policy_config['max_withdrawal_amount'];
      $withdrawal_charge = $policy_config['savings_withdrawal_charge'];
      if ($savings_amount) {
        $withdrawal_amount = ($max_withdrawal / 100) * $actual_savings_amount;
        $withdrawable_amount = $withdrawal_amount * (1 - ($withdrawal_charge / 100));
      }
      $response_data = [
        'success' => true,
        'savings_amount' => $savings_amount,
        'withdrawable_amount' => $withdrawable_amount,
        'encumbered_amount' => $encumbered_amount,
        'withdrawal_charge' => $withdrawal_charge
      ];
      return $this->response->setJSON($response_data);
    }
    $response_data = [
      'success' => false,
      'msg' => 'User account must be active to withdraw'
    ];
    return $this->response->setJSON($response_data);
  }

  function submit_withdrawal_application()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $staff_status = $this->session->get('status');
      $response_data = array();
      if ($staff_status == 2) {
        $account_closure = $this->accountClosureModel->check_account_closure($staff_id);
        if (empty($account_closure)) {
          $pending_withdrawals = $this->withdrawModel->where([
            'withdraw_status <' => 3,
            'disburse' => 0,
            'withdraw_staff_id' => $staff_id
          ])->findAll();
          if (empty($pending_withdrawals)) {
            $post_data = $this->request->getPost();
            if ($post_data) {
              $savings_type = $post_data['savings_type'];
              $withdrawal_amount = $post_data['withdrawal_amount'];
              $withdrawable_amount = $post_data['withdrawable_amount'];
              $file = $this->request->getFile('withdrawal_attachment');
              if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                $file->move('uploads/withdrawal-attachments', $filename);
                // @TODO send file to admin service
              }
              if (!$savings_type && $savings_type == 'default') {
                $response_data['success'] = false;
                $response_data['msg'] = 'The savings type is required';
                return $this->response->setJSON($response_data);
              }
              if (!$withdrawal_amount) {
                $response_data['success'] = false;
                $response_data['msg'] = 'The withdrawal amount is required';
                return $this->response->setJSON($response_data);
              }
              if ($withdrawal_amount > $withdrawable_amount) {
                $response_data['success'] = false;
                $response_data['msg'] = 'Your withdrawal amount exceeds the amount you can withdraw';
                return $this->response->setJSON($response_data);
              }
              $policy_config = $this->policyConfigModel->first();
              $withdrawal_charge = $policy_config['savings_withdrawal_charge'];
              $withdrawal_charges = ($withdrawal_charge / 100) * $withdrawal_amount;

              $withdrawal_application_data = array(
                'withdraw_staff_id' => $staff_id,
                'withdraw_ct_id' => $savings_type,
                'withdraw_amount' => $withdrawal_amount,
                'withdraw_charges' => $withdrawal_charges,
                'withdraw_date' => date('Y-m-d'),
                'withdraw_status' => 0,
                'withdraw_doc' => $filename,
              );
              $this->withdrawModel->save($withdrawal_application_data);
              $response_data['success'] = true;
              $response_data['msg'] = 'You have successfully applied for a ' . number_format($withdrawal_amount, 2) . '
              amount withdrawal. You will be charged ' . number_format($withdrawal_charges, 2) . ' for this withdrawal.';
              return $this->response->setJSON($response_data);
            }
          } else {
            $response_data['success'] = false;
            $response_data['msg'] = 'You currently have pending withdrawals';
            $response_data['data'] = $pending_withdrawals;
            return $this->response->setJSON($response_data);
          }
        } else {
          $response_data['success'] = false;
          $response_data['msg'] = 'Your account is currently undergoing closure';
          return $this->response->setJSON($response_data);
        }
      } else {
        $response_data['success'] = false;
        $response_data['msg'] = 'Your account is currently frozen';
        return $this->response->setJSON($response_data);
      }
    }
    return redirect('auth/login');
  }

  private function _get_savings_type_amount($staff_id, $savings_type)
  {
    $savings_type_payments = $this->paymentDetailModel->where(['pd_staff_id' => $staff_id, 'pd_ct_id' => $savings_type])->findAll();
    $total_dr = 0;
    $total_cr = 0;
    foreach ($savings_type_payments as $savings_type_payment) {
      if ($savings_type_payment['pd_drcrtype'] == 1) $total_cr += $savings_type_payment['pd_amount'];
      if ($savings_type_payment['pd_drcrtype'] == 2) $total_dr += $savings_type_payment['pd_amount'];
    }
    return $total_cr - $total_dr;
  }
}