<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'user_id'=>[
                'type'=>'INT',
                'constraint'=>11,
            ],
            'role'=>[
                'type'=>'ENUM',
                'constraint'=>['admin','sk','resident'],
            ],
            'login_time'=>[
                'type'=>'DATETIME',
                'null'=>false,
                'default'=>date('Y-m-d H:i:s')
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('login_logs');
    }

    public function down()
    {
        $this->forge->dropTable('login_logs');
    }
}
