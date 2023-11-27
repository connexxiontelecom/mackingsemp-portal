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

    public function change_to_nigerian_intl_format($phoneNumber)
    {
        // Remove non-numeric characters from the phone number
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the number starts with '0' (local) or '234' (Nigeria country code)
        if (substr($phoneNumber, 0, 1) == '0') {
            // Remove leading '0' and add '234' country code
            $formattedNumber = '234' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 3) == '234') {
            // Number already has country code
            $formattedNumber = $phoneNumber;
        } else {
            // Default to assuming it's a local number, add '234' country code
            $formattedNumber = '234' . $phoneNumber;
        }

        return $formattedNumber;
    }

}