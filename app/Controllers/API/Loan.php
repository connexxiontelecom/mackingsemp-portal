<?php

namespace App\Controllers\API;

use App\Libraries\authLib;
use App\Libraries\helperLib;
use App\Models\AccountClosureModel;
use App\Models\CooperatorModel;
use App\Models\ExternalGuarantorModel;
use App\Models\GroupModel;
use App\Models\LoanApplicationModel;
use App\Models\LoanFeesSetup;
use App\Models\LoanGuarantorModel;
use App\Models\LoanInterestDuration;
use App\Models\LoanModel;
use App\Models\LoanSetupModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Loan extends ResourceController
{
    use ResponseTrait;

    private authLib $authLib;
    private helperLib $helperLib;
    private LoanModel $loanModel;
    private LoanSetupModel $loanSetupModel;
    private GroupModel $groupModel;
    private LoanInterestDuration $loanInterestDuration;
    private LoanFeesSetup $loanFeesSetup;
    private ExternalGuarantorModel $externalGuarantorModel;
    private CooperatorModel $cooperatorModel;
    private AccountClosureModel $accountClosureModel;
    private LoanApplicationModel $loanApplicationModel;
    private LoanGuarantorModel $loanGuarantorModel;

    public function __construct()
    {
        $this->authLib = new authLib();
        $this->loanModel = new LoanModel();
        $this->loanSetupModel = new LoanSetupModel();
        $this->groupModel = new GroupModel();
        $this->loanInterestDuration = new LoanInterestDuration();
        $this->loanFeesSetup = new LoanFeesSetup();
        $this->externalGuarantorModel = new ExternalGuarantorModel();
        $this->cooperatorModel = new CooperatorModel();
        $this->accountClosureModel = new AccountClosureModel();
        $this->loanApplicationModel = new LoanApplicationModel();
        $this->loanGuarantorModel = new LoanGuarantorModel();
        $this->helperLib = new helperLib();
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
                          'loan_encumbrance_amount' => $cooperator_loan['encumbrance_amount'],
                          'loan_disburse_date' => $cooperator_loan['disburse_date']
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

    public function get_loan_setup($loan_setup_id): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $loan_setup_details = $this->loanSetupModel->find($loan_setup_id);
            if ($loan_setup_details) {
                $loan_setup_details['cooperator_group'] = $this->groupModel->where('gs_id',
                  $user['cooperator_group_id'])->first();
                $loan_setup_details['int_duration'] = $this->loanInterestDuration->where('lid_loan_type_id',
                  $loan_setup_id)->findAll();
                if ($loan_setup_details['insurance_fee']) {
                    $loan_setup_details['insurance'] = $this->loanFeesSetup->where('lf_type', 3)->findAll();
                }
                if ($loan_setup_details['mgt_fee']) {
                    $loan_setup_details['management'] = $this->loanFeesSetup->where('lf_type', 2)->findAll();
                }
                if ($loan_setup_details['equity_fee']) {
                    $loan_setup_details['equity'] = $this->loanFeesSetup->where('lf_type', 1)->findAll();
                }
            }

            $response = [
              'success' => true,
              'loan_setup_details' => $loan_setup_details
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function search_loan_external_guarantor(): Response
    {
        try {
            $this->authLib->get_auth_user();
            $value = $this->request->getGet('value');
            if (!$value) {
                return $this->failValidationErrors('Search value is required');
            }
            $guarantors = $this->externalGuarantorModel->search_guarantors($value);
            $response = [
              'success' => true,
              'data' => $guarantors
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function search_loan_internal_guarantor(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $value = $this->request->getGet('staff_id');
            if (!$value) {
                return $this->failValidationErrors('Staff ID is required');
            }
            if ($value === $staff_id) {
                return $this->failValidationErrors('Cannot assign self as guarantor');
            }
            $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $value)->first();
            if (!$cooperator) {
                return $this->failNotFound('Cooperator with that staff id not found');
            }

            $cooperator['cooperator_password'] = null;
            $cooperator['cooperator_imei'] = null;

            $response = [
              'success' => true,
              'cooperator' => $cooperator
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function post_new_loan_application(): Response
    {
        try {
            $user = $this->authLib->get_auth_user();
            $staff_id = $user['cooperator_staff_id'];
            $firstname = $user['cooperator_first_name'];
            $lastname = $user['cooperator_last_name'];
            $othername = $user['cooperator_other_name'];
            $status = $user['cooperator_status'];
            $account_closure = $this->accountClosureModel->check_account_closure($staff_id);
            if (!empty($account_closure)) {
                return $this->failValidationErrors('Your account is currently undergoing closure');
            }

            if ($status !== '2') {
                return $this->failValidationErrors('Your account is currently frozen');
            }

            $loan_setup_id = $this->request->getVar('loan_type');
            if (!$loan_setup_id) {
                return $this->failValidationErrors('Loan type is required');
            }

            $loan_duration = $this->request->getVar('loan_duration_m') ?? $this->request->getVar('loan_duration_d');
            if (!$loan_duration) {
                return $this->failValidationErrors('Loan duration (monthly or daily) is required');
            }

            $loan_amount = $this->request->getVar('loan_amount');
            if (!$loan_amount) {
                return $this->failValidationErrors('Loan amount is required');
            }

            $internal_guarantor_id = $this->request->getVar('internal_guarantor');
            if (!$internal_guarantor_id) {
                return $this->failValidationErrors('Internal guarantor staff id is required');
            }
            if ($internal_guarantor_id === $staff_id) {
                return $this->failValidationErrors('Cannot assign self as guarantor');
            }
            $internal_guarantor = $this->cooperatorModel->where('cooperator_staff_id', $internal_guarantor_id)->first();
            if (!$internal_guarantor) {
                return $this->failNotFound('Internal guarantor not found');
            }

            $external_guarantor_id = $this->request->getVar('external_guarantor');
            if (!$external_guarantor_id) {
                return $this->failValidationErrors('External guarantor id is required');
            }
            $external_guarantor = $this->externalGuarantorModel->find($external_guarantor_id);
            if (!$external_guarantor) {
                return $this->failNotFound('External guarantor not found');
            }

            $waiver_charge = 0;
            $allowed_loan = 0;

            $loan_setup_details = $this->loanSetupModel->find($loan_setup_id);
            if (!$loan_setup_details) {
                return $this->failValidationErrors('We did not find any details on this loan type');
            }

            $approved_date = $user['cooperator_approved_date'];
            $start = new \DateTime($approved_date);
            $end = new \DateTime(date('Y-m-d'));
            $diff = $start->diff($end);
            $yearsInMonths = $diff->format('%r%y') * 12;
            $months = $diff->format('%r%m');
            $months_difference = $yearsInMonths + $months;
            if ($months_difference <= $loan_setup_details['age_qualification']) {
                return $this->failValidationErrors('You have not been a member long enough to apply for this loan type');
            }

            $loan_duration_type = $loan_setup_details['duration_type'];
            if ($loan_duration_type === 'M') {
                $loan_interest_duration = $this->loanInterestDuration->find($loan_duration);
                if (!$loan_interest_duration) {
                    return $this->failNotFound('We did not find any details on this loan duration');
                }
                $loan_duration = $loan_interest_duration['lid_duration'];
                $interest_rate = $loan_interest_duration['lid_rate'];
            } else {
                $loan_interest_duration = $this->loanInterestDuration->where('lid_loan_type_id',
                  $loan_setup_id)->first();
                $interest_rate = $loan_interest_duration['lid_rate'];
            }

            $loan_application_data = array(
              'staff_id' => $staff_id,
              'name' => $firstname . ' ' . $othername . ' ' . $lastname,
              'guarantor' => $internal_guarantor['cooperator_staff_id'] ?? null,
              'guarantor_2' => $external_guarantor['eg_id'],
              'loan_type' => $loan_setup_id,
              'duration' => $loan_duration,
              'interest_rate' => $interest_rate ?? 0,
              'amount' => $loan_amount,
              'applied_date' => date('Y-m-d H:i:s'),
              'waiver' => $waiver_charge,
              'encumbrance_amount' => $allowed_loan
            );
            $loan_application_id = $this->loanApplicationModel->insert($loan_application_data);
            $this->_update_loan_guarantors($loan_application_id, $internal_guarantor['cooperator_staff_id'], $staff_id);

            $response = [
              'success' => true,
              'msg' => 'Loan application successful'
            ];
            return $this->respond($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    private function _update_loan_guarantors($loan_application_id, $guarantor_id, $staff_id)
    {
        $guarantor_data = array(
          'loan_application_id' => $loan_application_id,
          'guarantor_id' => $guarantor_id,
          'staff_id' => $staff_id,
          'confirm' => 0
        );

        $loan_guarantor_id = $this->loanGuarantorModel->insert($guarantor_data);
        $notification_type = 'guarantor_notification';
        $notification_topic = 'You have been selected as a guarantor for a loan';
        $notification_receiver_id = $guarantor_id;
        $notification_details = $loan_guarantor_id;
        $this->helperLib->create_new_notification($notification_type, $notification_topic, $notification_receiver_id,
          $notification_details);
    }
}