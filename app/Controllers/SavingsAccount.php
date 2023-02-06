<?php

namespace App\Controllers;

class SavingsAccount extends BaseController
{
    function new_savings_account()
    {
        if (!$this->session->active) {
            return redirect('auth/login');
        }
        $post_data = $this->request->getPost();
        if (!$post_data['sa_account_type'] || !$post_data['sa_reason']) {
            $response_data['success'] = false;
            $response_data['msg'] = 'Please fill in all required fields!';
            $response_data['meta'] = 'Empty required fields';
            return $this->response->setJSON($response_data);
        }
        $post_data['sa_account_no'] = $this->session->get('staff_id');
        $post_data['sa_creation_date'] = date('Y-m-d');

        $saving_account = $this->savingsAccountModel->where(['sa_account_type' => $post_data['sa_account_type'], 'sa_account_no' => $post_data['sa_account_no']])->first();

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

        $response_data['success'] = true;
        $response_data['msg'] = 'Your savings account was created successfully.';
        return $this->response->setJSON($response_data);
    }
}