<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PcSeeder extends Seeder
{
    public function run()
    {
        $pcs = [
            ['pc_number' => 'PC-1', 'status' => 'available', 'added_by' => 1],
            ['pc_number' => 'PC-2', 'status' => 'available', 'added_by' => 1],
            ['pc_number' => 'PC-3', 'status' => 'available', 'added_by' => 1],
            ['pc_number' => 'PC-4', 'status' => 'available', 'added_by' => 1],
            ['pc_number' => 'PC-5', 'status' => 'available', 'added_by' => 1],
        ];

        $this->db->table('pcs')->insertBatch($pcs);
    }
}
