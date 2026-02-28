<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQrUsedToReservations extends Migration
{
    public function up()
    {
        $this->forge->addColumn('reservations', [
            'qr_used' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'after' => 'e_ticket_code'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('reservations', 'qr_used');
    }
}
