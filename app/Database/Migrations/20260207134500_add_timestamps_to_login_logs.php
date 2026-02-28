<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToLoginLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('login_logs', [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => date('Y-m-d H:i:s')
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('login_logs', 'created_at');
        $this->forge->dropColumn('login_logs', 'updated_at');
    }
}
