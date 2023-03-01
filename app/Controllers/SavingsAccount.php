<?php

namespace App\Controllers;

class SavingsAccount extends BaseController
{
    private $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function new_savings_account()
    {
        if (!$this->session->active) {
            return redirect('auth/login');
        }
        $this->db->transStart();

        $post_data = $this->request->getPost();
        if (!$post_data['sa_account_type'] || !$post_data['sa_reason']) {
            $response_data['success'] = false;
            $response_data['msg'] = 'Please fill in all required fields!';
            $response_data['meta'] = 'Empty required fields';
            return $this->response->setJSON($response_data);
        }
        $post_data['sa_account_no'] = $this->session->get('staff_id');
        $post_data['sa_creation_date'] = date('Y-m-d');

        $saving_account = $this->savingsAccountModel->where([
          'sa_account_type' => $post_data['sa_account_type'],
          'sa_account_no' => $post_data['sa_account_no']
        ])->first();

        if ($saving_account) {
            $response_data['success'] = false;
            $response_data['msg'] = 'You have already created a savings account of this type';
            $response_data['meta'] = 'Duplicate account type';
            return $this->response->setJSON($response_data);
        }

        $saved = $this->savingsAccountModel->save($post_data);
        if (!$saved) {
            $response_data['success'] = false;
            $response_data['msg'] = 'An error occurred. Please try again later.';
            $response_data['meta'] = 'Did not save in the db';
            return $this->response->setJSON($response_data);
        }

        $contribution_type = $this->contributionTypeModel->find($post_data['sa_account_type']);

        if (!$contribution_type) {
            $response_data['success'] = false;
            $response_data['msg'] = 'This account type does not exist';
            $response_data['meta'] = 'Invalid account type';
            return $this->response->setJSON($response_data);
        }

        $payment_detail = [
          "pd_staff_id" => $post_data['sa_account_no'],
          "pd_transaction_date" => date('Y-m-d'),
          "pd_narration" => "Activation Fee",
          "pd_amount" => $contribution_type['fee'],
          "pd_payment_type" => "1",
          "pd_drcrtype" => "2",
          "pd_ct_id" => $post_data['sa_account_type'],
          "pd_pg_id" => null,
          "pd_ref_code" => time(),
          "pd_month" => date('m'),
          "pd_year" => date('Y'),
          "\tpd_upload_date" => null,
          "pd_upload_by" => null,
          "pd_processed_date" => null,
          "pd_processed_by" => $post_data['sa_account_no'],
        ];

        $this->paymentDetailModel->save($payment_detail);

        $firstname = $this->session->get('firstname');
        $lastname = $this->session->get('lastname');
        $othername = $this->session->get('othername');

        $gl_1 = [
          "gl_code" => intval($contribution_type['contribution_type_glcode']),
          "posted_by" => $post_data['sa_account_no'],
          "narration" => $contribution_type['contribution_type_name'] . ' Activation Fee',
          "gl_description" => $post_data['sa_account_no'] . ' - ' . $firstname . ' ' . $othername . ' ' . $lastname,
          "gl_transaction_date" => date('Y-m-d'),
          "dr_amount" => $contribution_type['fee'],
          "cr_amount" => "0",
          "ref_no" => time(),
          "bank" => "0",
          "ob" => "0",
          "posted" => "1",
          "created_at" => date('Y-m-d'),
        ];

        $this->glModel->save($gl_1);

        $gl_2 = [
          "gl_code" => 41109,
          "posted_by" => $post_data['sa_account_no'],
          "narration" => $contribution_type['contribution_type_name'] . ' Activation Fee',
          "gl_description" => $post_data['sa_account_no'] . ' - ' . $firstname . ' ' . $othername . ' ' . $lastname,
          "gl_transaction_date" => date('Y-m-d'),
          "cr_amount" => $contribution_type['fee'],
          "dr_amount" => "0",
          "ref_no" => time(),
          "bank" => "0",
          "ob" => "0",
          "posted" => "1",
          "created_at" => date('Y-m-d'),
        ];

        $this->glModel->save($gl_2);

        $this->db->transComplete();

        $response_data['success'] = true;
        $response_data['msg'] = 'Your savings account was created successfully.';
        return $this->response->setJSON($response_data);
    }

    function get_contribution_type_fee($ct_type_id)
    {
        $contribution_type = $this->contributionTypeModel->find($ct_type_id);
        return json_encode($contribution_type);
    }
}