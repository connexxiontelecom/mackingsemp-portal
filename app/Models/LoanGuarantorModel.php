<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanGuarantorModel extends Model {
  protected $table = 'loan_guarantors';
  protected $primaryKey = 'loan_guarantor_id';
  protected $allowedFields = [
    'loan_application_id', 'guarantor_id', 'staff_id', 'confirm'
  ];
}