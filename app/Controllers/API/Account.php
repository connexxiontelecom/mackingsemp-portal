<?php

namespace App\Controllers\API;

use App\Libraries\authLib;
use App\Models\ContributionTypeModel;
use App\Models\GLModel;
use App\Models\PaymentDetailModel;
use App\Models\SavingsAccountModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Account extends ResourceController
{
    use ResponseTrait;

    private authLib $authLib;
    private SavingsAccountModel $savingsAccountModel;
    private ContributionTypeModel $contributionTypeModel;
    private PaymentDetailModel $paymentDetailModel;

    private BaseConnection $db;

    private GLModel $GLModel;

    public function __construct()
    {
        $this->authLib = new authLib();
        $this->savingsAccountModel = new SavingsAccountModel();
        $this->contributionTypeModel = new ContributionTypeModel();
        $this->paymentDetailModel = new PaymentDetailModel();
        $this->GLModel = new GLModel();
        $this->db = Database::connect();
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

    public function get_all_account_types(): Response
    {
        try {
            $this->authLib->get_auth_user();
            $account_types = $this->contributionTypeModel->where('loans', 0)->findAll();
            $response = [
              'success' => true,
              'account_types' => $account_types
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function post_new_savings_account()
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];

            $this->db->transStart();

            $account_type = $this->request->getVar('account_type');
            if (!$account_type) {
                return $this->failValidationErrors('Account type is required');
            }

            $reason = $this->request->getVar('reason');
            if (!$reason) {
                return $this->failValidationErrors('Reason is required');
            }

            $account_no = $staff_id;
            $savings_account = $this->savingsAccountModel->where([
              'sa_account_type' => $account_type,
              'sa_account_no' => $account_no
            ])->first();

            if ($savings_account) {
                return $this->failValidationErrors('You have already created a savings account of this type');
            }

            $savings_account = [
              'sa_account_type' => $account_type,
              'sa_reason' => $reason,
              'sa_account_no' => $account_no,
              'sa_creation_date' => date('Y-m-d')
            ];
            $this->savingsAccountModel->save($savings_account);

            $contribution_type = $this->contributionTypeModel->find($account_type);
            if (!$contribution_type) {
                return $this->failValidationErrors('This account type does not exist');
            }

            if ($contribution_type['fee'] > 0) {
                $payment_detail = [
                  "pd_staff_id" => $account_no,
                  "pd_transaction_date" => date('Y-m-d'),
                  "pd_narration" => "Activation Fee",
                  "pd_amount" => $contribution_type['fee'],
                  "pd_payment_type" => "1",
                  "pd_drcrtype" => "2",
                  "pd_ct_id" => $contribution_type['contribution_type_id'],
                  "pd_pg_id" => null,
                  "pd_ref_code" => time(),
                  "pd_month" => date('m'),
                  "pd_year" => date('Y'),
                  "\tpd_upload_date" => null,
                  "pd_upload_by" => null,
                  "pd_processed_date" => null,
                  "pd_processed_by" => $account_no,
                ];
                $this->paymentDetailModel->save($payment_detail);

                $firstname = $user['cooperator_first_name'];
                $lastname = $user['cooperator_last_name'];
                $othername = $user['cooperator_other_name'];

                $gl_1 = [
                  "gl_code" => intval($contribution_type['contribution_type_glcode']),
                  "posted_by" => $account_no,
                  "narration" => $contribution_type['contribution_type_name'] . ' Activation Fee',
                  "gl_description" => $account_no . ' - ' . $firstname . ' ' . $othername . ' ' . $lastname,
                  "gl_transaction_date" => date('Y-m-d'),
                  "dr_amount" => $contribution_type['fee'],
                  "cr_amount" => "0",
                  "ref_no" => time(),
                  "bank" => "0",
                  "ob" => "0",
                  "posted" => "1",
                  "created_at" => date('Y-m-d'),
                ];

                $this->GLModel->save($gl_1);

                $gl_2 = [
                  "gl_code" => 41109,
                  "posted_by" => $account_no,
                  "narration" => $contribution_type['contribution_type_name'] . ' Activation Fee',
                  "gl_description" => $account_no . ' - ' . $firstname . ' ' . $othername . ' ' . $lastname,
                  "gl_transaction_date" => date('Y-m-d'),
                  "cr_amount" => $contribution_type['fee'],
                  "dr_amount" => "0",
                  "ref_no" => time(),
                  "bank" => "0",
                  "ob" => "0",
                  "posted" => "1",
                  "created_at" => date('Y-m-d'),
                ];

                $this->GLModel->save($gl_2);
            }

            $this->db->transComplete();

            $response = [
              'success' => true,
              'msg' => 'Your savings account was created successfully'
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }
}