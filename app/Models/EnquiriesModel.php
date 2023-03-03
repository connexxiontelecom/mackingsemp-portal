<?php

namespace App\Models;

use CodeIgniter\Model;

class EnquiriesModel extends Model
{
    protected $table = 'enquiries';
    protected $primaryKey = 'enquiry_id';
    protected $allowedFields = ['staff_id', 'subject', 'message', 'status', 'date_created', 'date_resolved'];
}