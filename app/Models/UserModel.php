<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'full_name',
        'email',
        'phone',
        'role',
        'password',
        'status',
        'is_approved',
        'is_verified',
        'verification_token',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}