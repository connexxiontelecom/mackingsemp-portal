<?php namespace App\Models;

use CodeIgniter\Model;

class CooperatorModel extends Model
{
  private $db_instance;
  private $query_builder;

  protected $table = 'cooperators';
  protected $primaryKey = 'cooperator_id';
  protected $allowedFields = [
    'cooperator_id', 'cooperator_cooperator_id', 'cooperator_staff_id', 'cooperator_username', 'cooperator_password', 'cooperator_first_name', 'cooperator_last_name', 'cooperator_other_name', 'cooperator_gender',
    'cooperator_department_id', 'cooperator_location_id', 'cooperator_payroll_group_id', 'cooperator_dob', 'cooperator_email',
    'cooperator_address', 'cooperator_city', 'cooperator_state_id', 'cooperator_telephone', 'cooperator_kin_fullname', 'cooperator_kin_address',
    'cooperator_kin_email', 'cooperator_kin_phone', 'cooperator_kin_relationship', 'cooperator_bank_id', 'cooperator_account_number',
    'cooperator_bank_branch', 'cooperator_sort_code', 'cooperator_date', 'cooperator_savings', 'cooperator_verify_by', 'cooperator_verify_date', 'cooperator_verify_comment', 'cooperator_approved_by',
    'cooperator_approved_date', 'cooperator_approved_comment', 'cooperator_discarded_by', 'cooperator_discarded_date', 'cooperator_discarded_reason', 'cooperator_status', 'cooperator_passport', 'cooperator_profile_lock'
  ];

  public function __construct()
  {
    parent::__construct();
    $this->db_instance = db_connect();
    $this->query_builder = $this->db_instance->table('cooperators');
  }

  public function search_cooperators($search_value): array
  {
    $this->query_builder->like('cooperator_staff_id', $search_value);
    $this->query_builder->orLike('cooperator_first_name', $search_value);
    $this->query_builder->orLike('cooperator_last_name', $search_value);
    return $this->query_builder->get()->getResult();
  }
}