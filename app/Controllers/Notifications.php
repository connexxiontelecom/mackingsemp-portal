<?php

namespace App\Controllers;

class Notifications extends BaseController
{

  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Notifications';
      $page_data['notifications'] = $this->notificationModel->where('receiver_id', $staff_id)->findAll();
      $page_data['unseen_count'] = sizeof($this->notificationModel->where(['receiver_id' => $staff_id, 'seen' => 0])->findAll());
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      return view('notifications/index-all', $page_data);
    }
    return redirect('auth/login');
  }

  function unread_notifications()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Unread Notifications';
      $page_data['notifications'] = $this->notificationModel->where(['receiver_id' => $staff_id, 'seen' => 0])->findAll();
      $page_data['unseen_count'] = sizeof($this->notificationModel->where(['receiver_id' => $staff_id, 'seen' => 0])->findAll());
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      return view('notifications/index-unread', $page_data);
    }
    return redirect('auth/login');
  }

  function get_user_notifications()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $user_notifications = $this->notificationModel->where(['receiver_id' => $staff_id, 'seen' => 0])->findAll();
      return $this->response->setJSON($user_notifications);
    }
    return redirect('auth/login');
  }

  function view_notification($notification_id)
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $notification = $this->notificationModel->find($notification_id);
      if ($notification) {
        if ($notification['seen'] == 0) {
          $notification_data = ['notification_id' => $notification['notification_id'], 'seen' => 1];
          $this->notificationModel->save($notification_data);
          $notification = $this->notificationModel->find($notification_id);
        }
        $page_data['page_title'] = 'View Notification';
        $page_data['notification'] = $notification;
        $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
        switch ($notification['type']) {
          case 'guarantor_notification':
            $loan_guarantor = $this->loanGuarantorModel->find($notification['details']);
            $loan_application = $this->loanApplicationModel->find($loan_guarantor['loan_application_id']);
            $loan_details = $this->loanSetupModel->find($loan_application['loan_type']);
            $page_data['loan_guarantor'] = $loan_guarantor;
            $page_data['loan_application'] = $loan_application;
            $page_data['loan_details'] = $loan_details;
            $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
            $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
            $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
            break;
        }
        return view('notifications/view-notification', $page_data);
      }
      return redirect('dashboard');
    }
    return redirect('auth/login');
  }
}