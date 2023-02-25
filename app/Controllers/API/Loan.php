<?php

namespace App\Controllers\API;

use App\Libraries\authLib;
use App\Models\LoanModel;
use App\Models\LoanSetupModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Loan extends ResourceController
{
    use ResponseTrait;

    private authLib $authLib;
    private LoanModel $loanModel;
    private LoanSetupModel $loanSetupModel;


    public function __construct()
    {
        $this->authLib = new authLib();
        $this->loanModel = new LoanModel();
        $this->loanSetupModel = new LoanSetupModel();
    }

    public function get_loan($loan_id): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $loan_id);
            $response = [
              'success' => true,
              'loan_details' => $cooperator_loan_details
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function get_outstanding_loans(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $cooperator_loans = $this->loanModel->where('staff_id', $staff_id)->findAll();
            $loans = array();
            $i = 0;
            foreach ($cooperator_loans as $cooperator_loan) {
                if ($cooperator_loan['disburse'] == 1 && $cooperator_loan['paid_back'] == 0) {
                    $total_dr = 0;
                    $total_cr = 0;
                    $total_interest = 0;
                    $cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id,
                      $cooperator_loan['loan_id']);
//                    if (empty($cooperator_loan_details)) {
//                        $cooperator_loan_details = $this->loanModel->get_cooperator_loans_no_repayment_by_staff_id_loan_id($staff_id,
//                          $cooperator_loan['loan_id']);
//                    }

                    if (!empty($cooperator_loan_details)) {
                        foreach ($cooperator_loan_details as $cooperator_loan_detail) {
                            $cooperator_loan_detail->lr_dctype == 1 ? $total_cr += $cooperator_loan_detail->lr_amount : $total_cr += 0;
                            $cooperator_loan_detail->lr_dctype == 2 ? $total_dr += $cooperator_loan_detail->lr_amount : $total_dr += 0;
                            $cooperator_loan_detail->lr_interest == 1 ? $total_interest += $cooperator_loan_detail->lr_amount : $total_interest += 0;
                        }
                        $loans[$i] = array(
                          'loan_id' => $cooperator_loan_details[0]->loan_id,
                          'loan_type' => $cooperator_loan_details[0]->loan_description,
                          'loan_principal' => $cooperator_loan_details[0]->amount,
                          'total_interest' => $total_interest,
                          'total_dr' => $total_dr,
                          'total_cr' => $total_cr,
                          'loan_balance' => $cooperator_loan_details[0]->amount + ($total_dr - $total_cr),
                          'loan_encumbrance_amount' => $cooperator_loan['encumbrance_amount']
                        );
                        $i++;
                    }
                }
            }

            $response = [
              'success' => true,
              'outstanding_loans' => $loans
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function get_loan_setups(): Response
    {
        try {
            $this->authLib->get_auth_user();
            $loan_setups = $this->loanSetupModel->where('status', 1)->findAll();
            $response = [
              'success' => true,
              'loan_setups' => $loan_setups
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function get_loan_setup_details($loan_setup_id)
    {
    }

    public function post_new_loan_application()
    {
    }
}