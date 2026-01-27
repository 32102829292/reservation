<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUsersRole extends Seeder
{
    public function run()
    {
        // Set role to 'user' for all users without a role
        $this->db->table('users')
            ->where('role', '')
            ->update(['role' => 'user']);
        
        // Also update null roles
        $this->db->table('users')
            ->where('role IS NULL')
            ->update(['role' => 'user']);
        
        echo "Updated all users with role='user'\n";
    }
}
