<?php

namespace App\Models;
use CodeIgniter\Model;

class LoginLog extends Model
{
    protected $table = 'login_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'role', 'login_time', 'logout_time', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
