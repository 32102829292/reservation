<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVisitorFieldsToReservations extends Migration
{
    public function up()
    {
        $this->forge->addColumn('reservations', [
            'visitor_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'pc_number'
            ],
            'visitor_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'visitor_type'
            ],
            'user_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'visitor_name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', ['visitor_type', 'visitor_name', 'user_email']);
    }
}
