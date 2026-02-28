<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
                'after' => 'name'
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'name'
            ],
            'is_approved' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'role'
            ],
            'is_verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_approved'
            ],
            'verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'is_verified'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['email', 'full_name', 'is_approved', 'is_verified', 'verification_token']);
    }
}
