<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanModel extends Model {
	private $db_instance;
	private $query_builder;

	protected $table = 'loans';
	protected $primaryKey = 'loan_id';
	protected $allowedFields = [
		'loan_app_id', 'staff_id', 'schedule_master_id', 'amount', 'interest_rate', 'loan_type',
		'interest', 'disburse', 'disburse_date', 'cart', 'created_at', 'scheduled', 'paid_back'
	];

	public function __construct() {
		parent::__construct();
		$this->db_instance = db_connect();
		$this->query_builder = $this->db_instance->table('loans');
	}

	public function get_cooperator_loans_by_staff_id_loan_id($staff_id, $loan_id): array {
		$this->query_builder->join('loan_setups', 'loan_setups.loan_setup_id = loans.loan_type');
		$this->query_builder->join('loan_applications', 'loan_applications.loan_app_id = loans.loan_app_id');
		$this->query_builder->join('loan_repayments', 'loan_repayments.lr_loan_id = loans.loan_id');
		$this->query_builder->where('loan_repayments.lr_loan_id', $loan_id);
		$this->query_builder->where('loans.staff_id', $staff_id);
		return $this->query_builder->get()->getResult();
	}

	public function get_cooperator_loans_no_repayment_by_staff_id_loan_id($staff_id, $loan_id): array {
		$this->query_builder->join('loan_setups', 'loan_setups.loan_setup_id = loans.loan_type');
		$this->query_builder->join('loan_applications', 'loan_applications.loan_app_id = loans.loan_app_id');
		$this->query_builder->where('loans.staff_id', $staff_id);
		return $this->query_builder->get()->getResult();
	}

}