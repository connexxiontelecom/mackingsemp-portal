<?php

namespace App\Models;

use CodeIgniter\Model;

class GLModel extends Model
{
    protected $table = 'gls';
    protected $primaryKey = 'gl_id';
    protected $allowedFields = [
      'gl_id',
      'glcode',
      'posted_by',
      'narration',
      'gl_description',
      'gl_transaction_date',
      'dr_amount',
      'cr_amount',
      'ref_no',
      'bank',
      'ob',
      'posted',
      'created_at',
      'branch'
    ];
}