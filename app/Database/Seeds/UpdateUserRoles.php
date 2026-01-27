<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUserRoles extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Update resident user with role 'resident'
        $db->table('users')->update(['role' => 'resident'], ['id' => 6]);
        echo "User 6 (Resident) role set to 'resident'\n";
        
        // Update admin/chairman user with role 'chairman'
        $db->table('users')->update(['role' => 'chairman'], ['id' => 11]);
        echo "User 11 (Chairman) role set to 'chairman'\n";
        
        echo "User roles updated!\n";
    }
}
