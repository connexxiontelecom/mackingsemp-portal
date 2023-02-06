<?php

namespace App\Controllers;

class SavingsVariation extends BaseController
{

  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Savings Variation';
      $page_data['savings_types'] = $this->_get_savings_types($staff_id);
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      return view('service-forms/savings-variation', $page_data);
    }
    return redirect('auth/login');
  }
}