<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixAllRoles extends Seeder
{
    public function run()
    {
        // Fix all user roles based on their names/emails
        $this->db->table('users')->where('id', 11)->update(['role' => 'admin']);
        $this->db->table('users')->where('id', 5)->update(['role' => 'sk']);
        $this->db->table('users')->where('id', 6)->update(['role' => 'user']);
        
        echo "All roles fixed!\n";
        echo "ID 11 (Chairman): admin\n";
        echo "ID 5 (SK Officer): sk\n";
        echo "ID 6 (Resident): user\n";
    }
}
