<?php

namespace App\Controllers;

use Aws\S3\S3Client;
use DateTime;

class Auth extends BaseController
{

    function login()
    {
        if ($this->session->active) {
            return redirect('/');
        }
        $page_data['page_title'] = 'Login';
        return view('auth/login', $page_data);
    }

    function auth_login()
    {
        // @TODO refactor method to use proper Request pattern
        extract($_POST);

        if ($staff_id && $password) {
            $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
            if ($cooperator) {
                if (password_verify($password, $cooperator['cooperator_password'])) {
                    $user_data = array(
                        'staff_id' => $cooperator['cooperator_staff_id'],
                        'firstname' => $cooperator['cooperator_first_name'],
                        'lastname' => $cooperator['cooperator_last_name'],
                        'othername' => $cooperator['cooperator_other_name'],
                        'dob' => $cooperator['cooperator_dob'],
                        'email' => $cooperator['cooperator_email'],
                        'address' => $cooperator['cooperator_address'],
                        'city' => $cooperator['cooperator_city'],
                        'state' => $this->stateModel->where('state_id', $cooperator['cooperator_state_id'])->first(),
                        'department' => $this->departmentModel->where('department_id', $cooperator['cooperator_department_id'])->first(),
                        'location' => $this->locationModel->where('location_id', $cooperator['cooperator_location_id'])->first(),
                        'payroll_group' => $this->payrollGroupModel->where('pg_id', $cooperator['cooperator_payroll_group_id'])->first(),
                        'bank' => $this->bankModel->where('bank_id', $cooperator['cooperator_bank_id'])->first(),
                        'account_number' => $cooperator['cooperator_account_number'],
                        'bank_branch' => $cooperator['cooperator_bank_branch'],
                        'sort_code' => $cooperator['cooperator_sort_code'],
                        'date' => $cooperator['cooperator_date'],
                        'approved_date' => $cooperator['cooperator_approved_date'],
                        'savings' => $cooperator['cooperator_savings'],
                        'status' => $cooperator['cooperator_status'],
                        'regular_savings' => $this->_get_regular_savings_amount($cooperator['cooperator_staff_id']),
                        'savings_types_amounts_list' => $this->_get_savings_types_amounts($cooperator['cooperator_staff_id']),
                        'active' => true
                    );
                    $this->session->set($user_data);
                    $this->session->setFlashdata('login_success', 'You have logged in successfully!');
//		      print_r($this->_get_savings_types_amounts($user_data['staff_id']));
                    return redirect('dashboard');
                }
            }
            $this->session->setFlashdata('login_failure', 'Invalid login credentials!');
            return redirect('auth/login');
        }
    }

    function membership()
    {
        if ($this->session->active) {
            return redirect('/');
        }
        $page_data['page_title'] = 'Account Opening Form';
        $page_data['departments'] = $this->departmentModel->findAll();
        $page_data['payroll_groups'] = $this->payrollGroupModel->findAll();
        $page_data['states'] = $this->stateModel->findAll();
        $page_data['banks'] = $this->bankModel->findAll();
        $page_data['groups'] = $this->groupModel->findAll();
        $page_data['profile'] = $this->policyConfigModel->first();
        return view('auth/membership', $page_data);
    }

    function auth_membership()
    {
        $post_data = $this->request->getPost();
        if (!$post_data) {
            $response_data['success'] = false;
            $response_data['msg'] = 'An error occurred. Please try again later.';
            $response_data['meta'] = 'No post data';
            return $this->response->setJSON($response_data);
        }

        if (!$post_data['application_first_name'] || !$post_data['application_last_name'] || !$post_data['application_email'] || !$post_data['application_nationality'] || !$post_data['application_dob'] || !$post_data['application_city'] || !$post_data['application_telephone'] || !$post_data['application_address'] || !$post_data['application_bank_branch'] || !$post_data['application_account_number'] || !$post_data['application_sort_code']) {
            $response_data['success'] = false;
            $response_data['msg'] = 'Please fill in all required fields!';
            $response_data['meta'] = 'Empty required fields';
            return $this->response->setJSON($response_data);
        }

        $application = $this->applicationModel->where('application_telephone', $post_data['application_telephone'])->findAll();
        if ($application) {
            $response_data['success'] = false;
            $response_data['msg'] = 'The telephone number already exists';
            $response_data['meta'] = 'Duplicate telephone number';
            return $this->response->setJSON($response_data);
        }

        $application = $this->applicationModel->where('application_email', $post_data['application_email'])->findAll();
        if ($application) {
            $response_data['success'] = false;
            $response_data['msg'] = 'The email address already exists';
            $response_data['meta'] = 'Duplicate email address';
            return $this->response->setJSON($response_data);
        }

        $_POST['application_date'] = date('Y-m-d');
        $dob = DateTime::createFromFormat('m/d/Y', $_POST['application_dob']);
        $_POST['application_dob'] = $dob->format('Y-m-d');

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => getenv('AWS_REGION'),
            'credentials' => [
                'key' => getenv('ACCESS_KEY_ID'),
                'secret' => getenv('SECRET_ACCESS_KEY')
            ]
        ]);
        $bucket = getenv('BUCKET_NAME');

        $passport = $this->request->getFile('application_upload_passport');
        $card = $this->request->getFile('application_upload_card');
        $signature = $this->request->getFile('application_authorized_signature');

        if ($passport->isValid() && !$passport->hasMoved()) {
            $filename = $passport->getRandomName();
            $passport->move('uploads/passports', $filename);
            $filepath = FCPATH . 'uploads/passports/' . $filename;
            $key = $filename;
            $_POST['application_upload_passport'] = $filename;

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'Body' => fopen($filepath, 'r'),
                ]);
            } catch (\Exception $e) {
                $response_data['success'] = false;
                $response_data['msg'] = 'An error occurred. Please try again later.';
                $response_data['meta'] = $e->getMessage();
                return $this->response->setJSON($response_data);
            }
        }

        if ($card->isValid() && !$card->hasMoved()) {
            $filename = $card->getRandomName();
            $card->move('uploads/id_cards', $filename);
            $filepath = FCPATH . 'uploads/id_cards/' . $filename;
            $key = $filename;
            $_POST['application_upload_card'] = $filename;

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'Body' => fopen($filepath, 'r'),
                ]);
            } catch (\Exception $e) {
                $response_data['success'] = false;
                $response_data['msg'] = 'An error occurred. Please try again later.';
                $response_data['meta'] = $e->getMessage();
                return $this->response->setJSON($response_data);
            }
        }

        if ($signature->isValid() && !$signature->hasMoved()) {
            $filename = $signature->getRandomName();
            $signature->move('uploads/signatures', $filename);
            $filepath = FCPATH . 'uploads/signatures/' . $filename;
            $key = $filename;
            $_POST['application_authorized_signature'] = $filename;

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'Body' => fopen($filepath, 'r'),
                ]);
            } catch (\Exception $e) {
                $response_data['success'] = false;
                $response_data['msg'] = 'An error occurred. Please try again later.';
                $response_data['meta'] = $e->getMessage();
                return $this->response->setJSON($response_data);
            }
        }


        $saved = $this->applicationModel->save($_POST);

        if (!$saved) {
            $response_data['success'] = false;
            $response_data['msg'] = 'An error occurred. Please try again later.';
            $response_data['meta'] = 'Did not save in the db';
            return $this->response->setJSON($response_data);
        }

        $response_data['success'] = true;
        $response_data['msg'] = 'Your application was submitted successfully.';
        return $this->response->setJSON($response_data);
    }

    function logout()
    {
        if ($this->session->active) {
            $this->session->stop();
            $this->session->destroy();
        }
        return redirect('auth/login');
    }

//  private function _get_savings_types_amounts($staff_id): array
//  {
//    $savings_types = $this->_get_savings_types($staff_id);
//    $savings_types_amounts = array();
//    foreach ($savings_types as $savings_type) {
//      $total_dr = 0;
//      $total_cr = 0;
//      $savings_payment_amounts = $this->paymentDetailModel->get_all_payment_details_by_id($staff_id, $savings_type['contribution_type_id']);
//      foreach ($savings_payment_amounts as $savings_payment_amount) {
//        if ($savings_payment_amount->pd_drcrtype == 1) $total_cr += $savings_payment_amount->pd_amount;
//        if ($savings_payment_amount->pd_drcrtype == 2) $total_dr += $savings_payment_amount->pd_amount;
//      }
//      $savings_types_amounts[$savings_type['contribution_type_name']] = $total_cr - $total_dr;
//    }
//    return $savings_types_amounts;
//  }
}