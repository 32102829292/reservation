<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixAdminRole extends Seeder
{
    public function run()
    {
        // Update Chairman users to have admin role
        $this->db->table('users')
            ->whereIn('id', [1, 4])
            ->update(['role' => 'admin']);
            
        echo "Admin roles fixed!\n";
    }
}
