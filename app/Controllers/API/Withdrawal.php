<?php

namespace App\Controllers\API;

use App\Libraries\authLib;
use App\Models\AccountClosureModel;
use App\Models\ContributionTypeModel;
use App\Models\LoanModel;
use App\Models\PaymentDetailModel;
use App\Models\PolicyConfigModel;
use App\Models\WithdrawModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Withdrawal extends ResourceController
{
    use ResponseTrait;

    private authLib $authLib;
    private LoanModel $loanModel;
    private ContributionTypeModel $contributionTypeModel;
    private PaymentDetailModel $paymentDetailModel;
    private PolicyConfigModel $policyConfigModel;
    private AccountClosureModel $accountClosureModel;
    private WithdrawModel $withdrawModel;


    public function __construct()
    {
        $this->authLib = new authLib();
        $this->contributionTypeModel = new ContributionTypeModel();
        $this->loanModel = new LoanModel();
        $this->paymentDetailModel = new PaymentDetailModel();
        $this->policyConfigModel = new PolicyConfigModel();
        $this->accountClosureModel = new AccountClosureModel();
        $this->withdrawModel = new WithdrawModel();
    }

    public function get_computed_balance(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $savings_type = $this->request->getGet('savings_type');
            if (!$savings_type) {
                return $this->failValidationErrors('Savings type is required');
            }

            $savings_type_detail = $this->contributionTypeModel->find($savings_type);
            if (!$savings_type_detail) {
                return $this->failValidationErrors('We could not find details on this savings type');
            }
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
            if (!$policy_config) {
                return $this->failValidationErrors('We could not find an active policy config');
            }
            $max_withdrawal = $policy_config['max_withdrawal_amount'];
            $withdrawal_charge = $policy_config['savings_withdrawal_charge'];
            if ($savings_amount) {
                $withdrawal_amount = ($max_withdrawal / 100) * $actual_savings_amount;
                $withdrawable_amount = $withdrawal_amount * (1 - ($withdrawal_charge / 100));
            }

            $response = [
              'success' => true,
              'computed_balance_details' => [
                'savings_amount' => $savings_amount,
                'withdrawable_amount' => $withdrawable_amount,
                'encumbered_amount' => $encumbered_amount,
                'withdrawal_charge' => $withdrawal_charge
              ]
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function post_new_withdrawal_application(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $status = $user['cooperator_status'];
            if ($status !== '2') {
                return $this->failValidationErrors('Your account is currently frozen');
            }
            $account_closure = $this->accountClosureModel->check_account_closure($staff_id);
            if (!empty($account_closure)) {
                return $this->failValidationErrors('Your account is currently undergoing closure');
            }

            $pending_withdrawals = $this->withdrawModel->where([
              'withdraw_status <' => 3,
              'disburse' => 0,
              'withdraw_staff_id' => $staff_id
            ])->findAll();

            if (!empty($pending_withdrawals)) {
                return $this->failValidationErrors('You currently have pending withdrawals');
            }

            $savings_type = $this->request->getVar('savings_type');
            if (!$savings_type) {
                return $this->failValidationErrors('Savings type is required');
            }

            $withdrawal_amount = $this->request->getVar('withdrawal_amount');
            if (!$withdrawal_amount) {
                return $this->failValidationErrors('Withdrawal amount is required');
            }

            $withdrawable_amount = $this->request->getVar('withdrawable_amount');
            if (!$withdrawable_amount) {
                return $this->failValidationErrors('Withdrawable amount is required');
            }

            $withdrawal_narration = $this->request->getVar('withdrawal_narration');
            if (!$withdrawal_narration) {
                return $this->failValidationErrors('Withdrawal narration is required');
            }

            if ($withdrawal_amount > $withdrawable_amount) {
                return $this->failValidationErrors('Your withdrawal amount exceeds the amount you can withdraw');
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
              'withdraw_narration' => $withdrawal_narration
            );
            $this->withdrawModel->save($withdrawal_application_data);

            $response = [
              'success' => true,
              'msg' => 'You have successfully applied for a ' . number_format($withdrawal_amount,
                  2) . ' amount withdrawal. You will be charged ' . number_format($withdrawal_charges,
                  2) . ' for this withdrawal.'
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    private function _get_savings_type_amount($staff_id, $savings_type)
    {
        $savings_type_payments = $this->paymentDetailModel->where([
          'pd_staff_id' => $staff_id,
          'pd_ct_id' => $savings_type
        ])->findAll();
        $total_dr = 0;
        $total_cr = 0;
        foreach ($savings_type_payments as $savings_type_payment) {
            if ($savings_type_payment['pd_drcrtype'] == 1) {
                $total_cr += $savings_type_payment['pd_amount'];
            }
            if ($savings_type_payment['pd_drcrtype'] == 2) {
                $total_dr += $savings_type_payment['pd_amount'];
            }
        }
        return $total_cr - $total_dr;
    }
}