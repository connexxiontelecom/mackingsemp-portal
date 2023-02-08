<?php

namespace App\Models;

use CodeIgniter\Model;

class ExternalGuarantorModel extends Model
{
    private $db_instance;
    private $query_builder;

    protected $table = 'external_guarantors';
    protected $primaryKey = 'eg_id';

    public function __construct()
    {
        parent::__construct();
        $this->db_instance = db_connect();
        $this->query_builder = $this->db_instance->table($this->table);
    }

    public function search_guarantors($search_value): array
    {
        $this->query_builder->like('eg_first_name', $search_value);
        $this->query_builder->orLike('eg_other_name', $search_value);
        $this->query_builder->orLike('eg_last_name', $search_value);
        $this->query_builder->orLike('eg_phone1', $search_value);
        $this->query_builder->orLike('eg_phone2', $search_value);
        $this->query_builder->orLike('eg_email', $search_value);
        $this->query_builder->orLike('eg_address', $search_value);
        $this->query_builder->orLike('eg_occupation', $search_value);
        $this->query_builder->orLike('eg_dob', $search_value);
        return $this->query_builder->get()->getResult();
    }
}