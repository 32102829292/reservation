<?php

namespace App\Models;

use CodeIgniter\Model;

class PcModel extends Model
{
    protected $table = 'pcs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pc_number', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'pc_number' => 'required|is_unique[pcs.pc_number,id,{id}]|max_length[50]',
        'status' => 'required|in_list[available,maintenance]'
    ];
    
    protected $validationMessages = [
        'pc_number' => [
            'required' => 'PC number is required',
            'is_unique' => 'This PC number already exists'
        ]
    ];
}