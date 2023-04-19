<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentDetailModel extends Model
{
    private $db_instance;
    private $query_builder;

    protected $table = 'payment_details';
    protected $primaryKey = 'pd_id';
    protected $allowedFields = [
        'pd_id',
        'pd_staff_id',
        'pd_transaction_date',
        'pd_narration',
        'pd_amount',
        'pd_payment_type',
        'pd_drcrtype',
        'pd_ct_id',
        'pd_pg_id',
        'pd_ref_code',
        'pd_month',
        'pd_year',
        '\tpd_upload_date',
        'pd_upload_by',
        'pd_processed_date',
        'pd_processed_by',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db_instance = db_connect();
        $this->query_builder = $this->db_instance->table('payment_details');
    }

    public function get_payment_details_by_staff_id($staff_id): array
    {
        $this->query_builder->groupBy('pd_ct_id');
        $this->query_builder->where('pd_staff_id', $staff_id);
        return $this->query_builder->get()->getResult();
    }

    public function get_all_payment_details_by_id($staff_id, $savings_id): array
    {
        $this->query_builder->where('pd_staff_id', $staff_id);
        $this->query_builder->where('pd_ct_id', $savings_id);
        $this->query_builder->orderBy('pd_transaction_date', 'asc');
        return $this->query_builder->get()->getResult();
    }


    public function get_payment_details_by_date_range($staff_id, $savings_type, $start_date, $end_date): array
    {
        $this->query_builder->where('pd_staff_id', $staff_id);
        $this->query_builder->where('pd_ct_id', $savings_type);
        $this->query_builder->where('pd_transaction_date >=', $start_date);
        $this->query_builder->where('pd_transaction_date <=', $end_date);
        $this->query_builder->orderBy('pd_transaction_date', 'asc');
        return $this->query_builder->get()->getResult();
    }

    public function get_payment_details_by_date_range_filtered($staff_id, $savings_type, $start_date, $end_date, $amount = null, $remark = null): array
    {
        $this->query_builder->where('pd_staff_id', $staff_id);
        $this->query_builder->where('pd_ct_id', $savings_type);
        $this->query_builder->where('pd_transaction_date >=', $start_date);
        $this->query_builder->where('pd_transaction_date <=', $end_date);

        if ($amount)
            $this->query_builder->where('pd_amount', $amount);
        if ($remark)
            $this->query_builder->like('pd_narration', $remark);

        $this->query_builder->orderBy('pd_transaction_date', 'asc');
        return $this->query_builder->get()->getResult();
    }

    public function get_payment_details_before_date($staff_id, $savings_type, $start_date): array
    {
        $this->query_builder->where('pd_staff_id', $staff_id);
        $this->query_builder->where('pd_ct_id', $savings_type);
        $this->query_builder->where('pd_transaction_date <', $start_date);
        return $this->query_builder->get()->getResult();
    }

}