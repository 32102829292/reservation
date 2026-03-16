<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table         = 'accounts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'password',
        'is_verified',
        'verification_token',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}