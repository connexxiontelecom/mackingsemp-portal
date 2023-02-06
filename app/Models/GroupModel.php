<?php
namespace App\Models;
use CodeIgniter\Model;

class GroupModel extends Model {
    protected $table = 'group_setups';
    protected $primaryKey = 'gs_id';
    protected $allowedFields = ['gs_name','gs_code'];
}