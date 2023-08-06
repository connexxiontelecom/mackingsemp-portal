<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationModel extends Model
{
  protected $table = 'applications';
  protected $primaryKey = 'application_id';
  protected $allowedFields = [
    'application_id', 'application_staff_id', 'application_first_name', 'application_last_name', 'application_other_name', 'application_gender',
    'application_department_id', 'application_location_id', 'application_payroll_group_id', 'application_dob', 'application_email',
    'application_address', 'application_city', 'application_state_id', 'application_telephone',
    'application_kin_fullname', 'application_kin_address',
    'application_kin_email', 'application_kin_phone', 'application_kin_relationship', 'application_kin_percentage',
    'application_kin2_fullname', 'application_kin2_address',
    'application_kin2_email', 'application_kin2_phone', 'application_kin2_relationship', 'application_kin2_percentage',
    'application_bank_id', 'application_account_number',
    'application_bank_branch', 'application_sort_code', 'application_verify_by', 'application_verify_date', 'application_verify_comment', 'application_savings', 'application_approved_by', 'application_date',
    'application_approved_date', 'application_approved_comment', 'application_discarded_by', 'application_discarded_date', 'application_discarded_reason', 'application_status', 'application_upload_passport',
    'application_upload_card', 'application_authorized_signature', 'application_nationality', 'application_alt_phone', 'application_bvn', 'application_group', 'application_credit_officer'
  ];

}