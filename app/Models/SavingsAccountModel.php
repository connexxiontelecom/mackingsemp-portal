<?php

namespace App\Models;

use CodeIgniter\Model;

class SavingsAccountModel extends Model
{
    protected $table = 'saving_accounts';
    protected $primaryKey = 'sa_id';
    protected $allowedFields = [
        'sa_account_no',
        'sa_account_type',
        'sa_reason',
        'sa_creation_date'
    ];

}