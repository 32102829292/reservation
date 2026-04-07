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
    
    protected $fieldData = [
        'is_approved' => ['type' => 'boolean'],
        'is_verified' => ['type' => 'boolean'],
    ];
    
    protected function beforeInsert(array $data)
    {
        if (isset($data['data']['is_approved'])) {
            $data['data']['is_approved'] = $data['data']['is_approved'] ? true : false;
        }
        if (isset($data['data']['is_verified'])) {
            $data['data']['is_verified'] = $data['data']['is_verified'] ? true : false;
        }
        return $data;
    }
    
    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['is_approved'])) {
            $data['data']['is_approved'] = $data['data']['is_approved'] ? true : false;
        }
        if (isset($data['data']['is_verified'])) {
            $data['data']['is_verified'] = $data['data']['is_verified'] ? true : false;
        }
        return $data;
    }
}