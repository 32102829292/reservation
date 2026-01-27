<?php

namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Chairman',
                'email'=> 'chairman@example.com',
                'role' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'is_approved' => 1,
                'is_verified' => 1,
            ],
            [
                'name' => 'SK Officer',
                'email' => 'sk@example.com',
                'role' => 'sk',
                'password' => password_hash('sk123', PASSWORD_DEFAULT),
                'is_approved' => 1,
                'is_verified' => 1,
            ],
            [
                'name' => 'Resident User',
                'email' => 'resident@example.com',
                'role' => 'user',
                'password' => password_hash('resident123', PASSWORD_DEFAULT),
                'is_approved' => 1,
                'is_verified' => 1,
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
