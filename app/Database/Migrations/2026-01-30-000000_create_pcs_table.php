<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePcsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'pc_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'maintenance', 'out_of_order'],
                'default' => 'available'
            ],
            'added_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('added_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pcs');
    }

    public function down()
    {
        $this->forge->dropTable('pcs');
    }
}
