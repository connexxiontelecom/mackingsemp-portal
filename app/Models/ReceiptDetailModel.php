<?php
namespace App\Models;
use CodeIgniter\Model;

class ReceiptDetailModel extends Model {
  protected $table = 'receipt_detail';
  protected $primaryKey = 'rd_id';
  protected $allowedFields = [
    'rd_rm_id', 'rd_amount', 'rd_type', 'rd_target'
  ];
}