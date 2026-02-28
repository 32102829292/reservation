<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLogoutTimeToLoginLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('login_logs', [
            'logout_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->modifyColumn('login_logs', [
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['chairman', 'sk', 'resident'],
                'default' => 'resident',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('login_logs', 'logout_time');

        $this->forge->modifyColumn('login_logs', [
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'sk', 'resident'],
                'default' => 'resident',
            ],
        ]);
    }
}
