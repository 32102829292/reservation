<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Computer Lab',
                'description' => 'Main computer laboratory with 20 PCs',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Meeting Room A',
                'description' => 'Small meeting room for up to 10 people',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Meeting Room B',
                'description' => 'Large meeting room for up to 25 people',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Auditorium',
                'description' => 'Main auditorium for events and presentations',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Study Area',
                'description' => 'Quiet study area with individual desks',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('resources')->insertBatch($data);
    }
}
