<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservations extends Migration
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
            'resource_id'=>[
                'type'=>'INT',
                'constraint'=>11,
            ],
            'reservation_date'=>[
                'type'=>'DATE',
            ],
            'start_time'=>[
                'type'=>'TIME',
            ],
            'end_time'=>[
                'type'=>'TIME',
            ],
            'status'=>[
                'type'=>'ENUM',
                'constraint'=>['pending','approved','canceled'],
                'default'=>'pending'
            ],
            'e_ticket_code'=>[
                'type'=>'VARCHAR',
                'constraint'=>50,
                'null'=>true
            ],
            'created_at'=>[
                'type'=>'DATETIME',
                'null'=>true
            ],
            'updated_at'=>[
                'type'=>'DATETIME',
                'null'=>true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('resource_id','resources','id','CASCADE','CASCADE');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
