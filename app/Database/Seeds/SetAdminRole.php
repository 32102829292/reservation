<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SetAdminRole extends Seeder
{
    public function run()
    {
        // Set admin user's role to 'admin'
        $this->db->table('users')
            ->where('name', 'Chairman')
            ->where('email', 'chairman@example.com')
            ->update(['role' => 'admin']);
        
        echo "Admin role set successfully!\n";
    }
}
