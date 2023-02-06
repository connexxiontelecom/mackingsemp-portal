<?php namespace App\Controllers;

class Account extends BaseController
{
  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'My Account';
      $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
      $cooperator['state'] = $this->stateModel->where('state_id', $cooperator['cooperator_state_id'])->first();
      $cooperator['department'] = $this->departmentModel->where('department_id', $cooperator['cooperator_department_id'])->first();
      $cooperator['location'] = $this->locationModel->where('location_id', $cooperator['cooperator_location_id'])->first();
      $cooperator['payroll_group'] = $this->payrollGroupModel->where('pg_id', $cooperator['cooperator_payroll_group_id'])->first();
      $cooperator['bank'] = $this->bankModel->where('bank_id', $cooperator['cooperator_bank_id'])->first();
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $cooperator;
      $page_data['banks'] = $this->bankModel->findAll();
      return view('account/index', $page_data);
    }
    return redirect('auth/login');
  }

  function security()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
      $page_data['cooperator'] = $cooperator;
      $page_data['page_title'] = 'My Account';
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      return view('account/security', $page_data);
    }
    return redirect('auth/login');
  }

  function upload_display_picture()
  {
    if (!$this->session->active) {
      return redirect('auth/login');
    }
    $staff_id = $this->session->get('staff_id');
    $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
    if ($cooperator['cooperator_passport']) {
      unlink('uploads/passports/' . $cooperator['cooperator_passport']);
    }
    $response_data = array();
    $file = $this->request->getFile('dp');
    if ($file->isValid() && !$file->hasMoved()) {
      $filename = $file->getRandomName();
      $file->move('uploads/passports', $filename);
      $cooperator_data = ['cooperator_id' => $cooperator['cooperator_id'], 'cooperator_passport' => $filename];
      $this->cooperatorModel->save($cooperator_data);
      $response_data['success'] = true;
      $response_data['msg'] = 'The display picture was uploaded!';
    } else {
      $response_data['success'] = false;
      $response_data['msg'] = 'The display picture is required';
      return $this->response->setJSON($response_data);
    }
    return $this->response->setJSON($response_data);
  }

  function change_password()
  {
    if (!$this->session->active) {
      return redirect('auth/login');
    }
    $post_data = $this->request->getPost();
    $response_data = array();

    if (!$post_data['current-password']) {
      $response_data['success'] = false;
      $response_data['msg'] = 'Current password is required';
      return $this->response->setJSON($response_data);
    }

    $current_password = $post_data['current-password'];
    $staff_id = $this->session->get('staff_id');
    $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();

    if (!password_verify($current_password, $cooperator['cooperator_password'])) {
      $response_data['success'] = false;
      $response_data['msg'] = 'The current password is invalid';
      return $this->response->setJSON($response_data);
    }

    $password = $post_data['password'];
    $confirm_password = $post_data['confirm-password'];
    if ($password !== $confirm_password) {
      $response_data['success'] = false;
      $response_data['msg'] = 'The new password does not match confirm password';
      return $this->response->setJSON($response_data);
    }

    $cooperator_data = ['cooperator_id' => $cooperator['cooperator_id'], 'cooperator_password' => password_hash($password, PASSWORD_DEFAULT)];
    $this->cooperatorModel->save($cooperator_data);
    $response_data['success'] = true;
    $response_data['msg'] = 'Password changed successfully!';
    return $this->response->setJSON($response_data);
  }

  function update_bank()
  {
    if (!$this->session->active) {
      return redirect('auth/login');
    }
    $staff_id = $this->session->get('staff_id');
    $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
    if ($cooperator['cooperator_profile_lock']) {
      $response_data['success'] = false;
      $response_data['msg'] = 'You currently cannot update your bank information. Please, contact support.';
      return $this->response->setJSON($response_data);
    }
    $post_data = $this->request->getPost();
    $cooperator_data = [
      'cooperator_id' => $cooperator['cooperator_id'],
      'cooperator_bank_id' => $post_data['application_bank_id'],
      'cooperator_account_number' => $post_data['application_account_number'],
      'cooperator_bank_branch' => $post_data['application_bank_branch'],
      'cooperator_sort_code' => $post_data['application_sort_code'],
      'cooperator_profile_lock' => 1
    ];
    $this->cooperatorModel->save($cooperator_data);
    $response_data['success'] = true;
    $response_data['msg'] = 'Bank updated successfully!';
    return $this->response->setJSON($response_data);
  }

  function update_nok()
  {
    if (!$this->session->active) {
      return redirect('auth/login');
    }
    $staff_id = $this->session->get('staff_id');
    $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
    if ($cooperator['cooperator_profile_lock']) {
      $response_data['success'] = false;
      $response_data['msg'] = 'You currently cannot update your next of kin information. Please, contact support.';
      return $this->response->setJSON($response_data);
    }
    $post_data = $this->request->getPost();
    $cooperator_data = [
      'cooperator_id' => $cooperator['cooperator_id'],
      'cooperator_kin_fullname' => $post_data['cooperator_kin_fullname'],
      'cooperator_kin_relationship' => $post_data['cooperator_kin_relationship'],
      'cooperator_kin_email' => $post_data['cooperator_kin_email'],
      'cooperator_kin_phone' => $post_data['cooperator_kin_phone'],
      'cooperator_kin_percentage' => $post_data['cooperator_kin_percentage'],
      'cooperator_kin_address' => $post_data['cooperator_kin_address'],
      'cooperator_profile_lock' => 1
    ];
    $this->cooperatorModel->save($cooperator_data);
    $response_data['success'] = true;
    $response_data['msg'] = 'Next of kin updated successfully!';
    return $this->response->setJSON($response_data);
  }

}