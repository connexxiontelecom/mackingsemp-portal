<?php
namespace App\Models;
use CodeIgniter\Model;

class ReceiptMasterModel extends Model {
  protected $table = 'receipt_master';
  protected $primaryKey = 'rm_id';
  protected $allowedFields = [
    'rm_staff_id', 'rm_date', 'rm_amount', 'rm_payment_method', 'rm_coop_bank', 'rm_status', 'rm_verify_comment',
    'rm_verify_by', 'rm_verify_date', 'rm_approve_comment', 'rm_approve_by', 'rm_approve_date', 'rm_discard_comment',
    'rm_discard_by', 'rm_discard_date', 'rm_a_date', 'rm_by'
  ];
}