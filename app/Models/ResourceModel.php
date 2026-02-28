<?php

namespace App\Models;

use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table = 'resources';
    protected $allowedFields = ['name'];
}
