<?php

namespace App\Libraries;

use App\Models\CooperatorModel;
use Exception;

class authLib
{
    private jwtLib $jwtLib;
    private CooperatorModel $cooperatorModel;

    public function __construct()
    {
        $this->jwtLib = new jwtLib();
        $this->cooperatorModel = new CooperatorModel();
    }

    /**
     * @throws Exception
     */
    public function get_auth_user()
    {
        $bearer_token = $this->jwtLib->get_bearer_token();
        $is_jwt_valid = $this->jwtLib->is_jwt_valid($bearer_token);
        if (!$is_jwt_valid) {
            throw new Exception('This access is invalid');
        }
        $decoded = $this->jwtLib->decode_token($bearer_token);
        $cooperator = $this->cooperatorModel->where('cooperator_email', $decoded->email)->first();

        if (!$cooperator) {
            throw new Exception('This access is invalid');
        }

        return $cooperator;
    }

}