<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
    'name', 'email', 'phone', 'role', 'password', 'is_approved', 'is_verified', 'verification_token', 'created_at'
];
}