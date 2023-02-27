<?php

namespace App\Controllers\API;

use App\Libraries\authLib;
use App\Models\ContributionTypeModel;
use App\Models\PaymentDetailModel;
use App\Models\SavingsAccountModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Account extends ResourceController
{
    use ResponseTrait;

    private authLib $authLib;
    private SavingsAccountModel $savingsAccountModel;
    private ContributionTypeModel $contributionTypeModel;
    private PaymentDetailModel $paymentDetailModel;

    public function __construct()
    {
        $this->authLib = new authLib();
        $this->savingsAccountModel = new SavingsAccountModel();
        $this->contributionTypeModel = new ContributionTypeModel();
        $this->paymentDetailModel = new PaymentDetailModel();
    }

    public function get_all_account_details(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $savings_accounts = $this->savingsAccountModel->where('sa_account_no', $staff_id)->findAll();

            foreach ($savings_accounts as $key => $savings_account) {
                $savings_type = $savings_account['sa_account_type'];
                $savings_accounts[$key]['savings_type'] = $this->contributionTypeModel->where('contribution_type_id',
                  $savings_type)->first();
                $payment_details = $this->paymentDetailModel->get_all_payment_details_by_id($staff_id, $savings_type);
                $savings_accounts[$key]['savings_type']['payment_details'] = $payment_details;
                $total_dr = 0;
                $total_cr = 0;
                foreach ($payment_details as $savings_payment_amount) {
                    if ($savings_payment_amount->pd_drcrtype == 1) {
                        $total_cr += $savings_payment_amount->pd_amount;
                    }
                    if ($savings_payment_amount->pd_drcrtype == 2) {
                        $total_dr += $savings_payment_amount->pd_amount;
                    }
                }
                $savings_accounts[$key]['savings_type']['payment_balance'] = $total_cr - $total_dr;
            }

            $response = [
              'success' => true,
              'account_details' => $savings_accounts
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }


}