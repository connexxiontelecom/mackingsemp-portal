<?php

namespace App\Controllers;

class AccountStatement extends BaseController
{

    function index()
    {
        if ($this->session->active) {
            $staff_id = $this->session->get('staff_id');
            $page_data['page_title'] = 'Account Statement';
            $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
            $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
            $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
            $page_data['user_savings_types'] = $this->_get_savings_types($staff_id);
            $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
            $page_data['savings_accounts'] = $this->_get_saving_accounts($staff_id);
            return view('account-statement/index', $page_data);
        }
        return redirect('auth/login');
    }

    function view_account_statement()
    {
        // @TODO refactor to proper Request pattern
        if ($this->session->active) {
            $post_data = $this->request->getPost();
            if ($post_data) {
                $staff_id = $this->session->get('staff_id');
                $payment_details = array();
                $savings_type = $post_data['savings_type'];
                isset($post_data['start_date']) ? $start_date = $post_data['start_date'] : $start_date = 0;
                isset($post_data['end_date']) ? $end_date = $post_data['end_date'] : $end_date = 0;
                isset($post_data['view_all']) ? $view_all = $post_data['view_all'] : $view_all = 0;
                if (\DateTime::createFromFormat('m/d/Y', $start_date)) {
                    $start_date = $this->_convert_date_to_Ymd($start_date);
                }
                if (\DateTime::createFromFormat('m/d/Y', $end_date)) {
                    $end_date = $this->_convert_date_to_Ymd($end_date);
                }
                if ($savings_type && $savings_type != 'default') {
                    if ($view_all) {
                        $payment_details = $this->paymentDetailModel->get_all_payment_details_by_id($staff_id, $savings_type);
                    } elseif ($start_date && $end_date) {
                        $payment_details = $this->paymentDetailModel->get_payment_details_by_date_range($staff_id, $savings_type, $start_date, $end_date);
                        $page_data['brought_forward'] = $this->_get_brought_forward($savings_type, $start_date);
                        $page_data['start_date'] = $start_date;
                        $page_data['end_date'] = $end_date;
                    } else {
                        $this->session->setFlashdata('no_payment_details', 'Please select a valid Savings Type or Transaction Date Range!');
                        return redirect('account-statement');
                    }
                    $page_data['page_title'] = 'View Account Statement';
                    $page_data['payment_details'] = $payment_details;
                    $page_data['savings_type_ledger'] = $this->contributionTypeModel->where('contribution_type_id', $savings_type)->first();
                    $page_data['encumbered_amount'] = $this->_get_encumbered_amount();
                    $page_data['regular_savings'] = $this->_get_regular_savings_amount($staff_id);
                    $page_data['savings_types_amounts_list'] = $this->_get_savings_types_amounts($staff_id);
                    $page_data['cooperator'] = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();;
                    return view('account-statement/account-statement-ledger', $page_data);
                } else {
                    $this->session->setFlashdata('no_payment_details', 'Please select a valid Savings Type!');
                    return redirect('account-statement');
                }
            }
            return redirect('account-statement');
        }
        return redirect('auth/login');
    }

    private function _get_brought_forward($savings_type, $start_date): int
    {
        $staff_id = $this->session->get('staff_id');
        $payment_details = $this->paymentDetailModel->get_payment_details_before_date($staff_id, $savings_type, $start_date);
        $total_dr = 0;
        $total_cr = 0;
        foreach ($payment_details as $payment_detail) {
            if ($payment_detail->pd_drcrtype == 1) $total_cr += $payment_detail->pd_amount;
            if ($payment_detail->pd_drcrtype == 2) $total_dr += $payment_detail->pd_amount;
        }
        return $total_cr - $total_dr;
    }

    private function _convert_date_to_Ymd($date): string
    {
        return \DateTime::createFromFormat('m/d/Y', $date)->format('Y-m-d');
    }
}