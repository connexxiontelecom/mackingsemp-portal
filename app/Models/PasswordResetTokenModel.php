<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetTokenModel extends Model
{
    protected $table = 'password_reset_token';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email', 'token', 'expires_at'
    ];
}