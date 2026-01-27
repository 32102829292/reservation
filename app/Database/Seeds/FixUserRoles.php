<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixUserRoles extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Fix resident user - set to 'user' role
        $db->table('users')->update(['role' => 'user'], ['id' => 6]);
        echo "User 6 role set to 'user'\n";
        
        // Fix admin user
        $db->table('users')->update(['role' => 'admin'], ['id' => 11]);
        echo "User 11 role set to 'admin'\n";
        
        echo "User roles fixed!\n";
    }
}
