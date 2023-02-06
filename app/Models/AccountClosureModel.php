<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountClosureModel extends Model {
  private $db_instance;
  private $query_builder;

  protected $table = 'account_closure';
  protected $primaryKey = 'ac_id';
  protected $allowedFields = [
    'ac_id', 'ac_staff_id', 'ac_effective_date', 'ac_mailing', 'ac_email', 'ac_phone',
    'ac_verify_comment', 'ac_verify_by', 'ac_verify_date', 'ac_approve_comment', 'ac_approve_by', 'ac_approve_date',
    'ac_discard_comment', 'ac_discard_by', 'ac_discard_date', 'ac_a_date', 'ac_by', 'ac_status'
  ];

  public function __construct() {
    parent::__construct();
    $this->db_instance = db_connect();
    $this->query_builder = $this->db_instance->table('account_closure');
  }

  public function check_account_closure($staff_id): array {
    $this->query_builder->where('ac_staff_id', $staff_id);
    $this->query_builder->where('ac_status', 0);
    $this->query_builder->orWhere('ac_status', 1);
    $this->query_builder->orWhere('ac_status', 2);
    return $this->query_builder->get()->getResult();
  }

}