<?php
namespace App\Models;

class CoopBankModel extends \CodeIgniter\Model {
  protected $table = 'coop_banks';
  protected $primaryKey = 'coop_bank_id';
  protected $allowedFields = ['bank_id', 'branch', 'account_no', 'description', 'glcode'];
}