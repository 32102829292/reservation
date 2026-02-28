<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPurposeToReservations extends Migration
{
    public function up()
    {
        $this->forge->addColumn('reservations', [
            'purpose' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'end_time'
            ],
            'pc_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'purpose'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', ['purpose', 'pc_number']);
    }
}
