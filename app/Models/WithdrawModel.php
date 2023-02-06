<?php
namespace App\Models;
use CodeIgniter\Model;

class WithdrawModel extends Model {
	/**
	 * withdraw status: 0 = pending, 1 = verified, 2 = approved, 3 = discarded, 4 = scheduled, 5 = disbursed
	 */
	protected $table = 'withdraws';
	protected $primaryKey = 'withdraw_id';
	protected $allowedFields = [
		'withdraw_id', 'withdraw_staff_id', 'withdraw_ct_id', 'withdraw_amount', 'withdraw_charges', 'withdraw_date', 'withdraw_narration', 'withdraw_status', 'withdraw_doc', 'withdraw_verify_by',
		'withdraw_verify_comment', 'withdraw_verify_date', 'withdraw_approved_by', 'withdraw_approved_date', 'withdraw_approved_comment',
		'withdraw_discarded_by', 'withdraw_discarded_date', 'cart', 'withdraw_discarded_comment', 'withdraw_disbursed_date', 'scheduled', 'disburse', 'disburse_date'
	];
}