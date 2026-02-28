<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CleanAndCreateAdmin extends Seeder
{
    public function run()
    {
        // Update existing admin user to have correct role
        $this->db->table('users')
            ->where('email', 'chairman@example.com')
            ->update(['role' => 'chairman']);

        echo "Admin user role updated to chairman successfully!\n";
    }
}
