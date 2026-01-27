<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CleanAndCreateAdmin extends Seeder
{
    public function run()
    {
        $this->db->table('users')->whereIn('id', [1, 4])->delete();
        $this->db->table('users')->insert([
            'name' => 'Chairman',
            'email' => 'chairman@example.com',
            'role' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'is_approved' => 1,
            'is_verified' => 1,
        ]);
        
        echo "Admin user created successfully!\n";
    }
}
