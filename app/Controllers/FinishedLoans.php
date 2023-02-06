<?php

namespace App\Controllers;

class FinishedLoans extends BaseController
{

  function index()
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Finished Loans';
      $page_data['finished_loans'] = $this->_get_user_loans(1);
      $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
      $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
      $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
      $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
      return view('finished-loans/index', $page_data);
    }
    return redirect('auth/login');
  }

  function view_finished_loan($loan_id)
  {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $loan_exists = $this->loanModel->where('loan_id', $loan_id)->first();
      if ($loan_id && $loan_exists) {
        $page_data['page_title'] = 'View Finished Loan';
        $page_data['finished_loan_details'] = $this->_get_user_loan_details($loan_id);
        $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
        $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
        $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
        $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
        return view('finished-loans/finished-loans-ledger', $page_data);
      }
      return redirect('finished-loans');
    }
    return redirect('auth/login');
  }
}