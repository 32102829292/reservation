<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAdminRole extends Migration
{
    public function up()
    {
        // Update all Chairman users to have admin role
        $this->db->table('users')->update(['role' => 'admin'], ['name' => 'Chairman']);
    }

    public function down()
    {
        // Revert admin role to empty
        $this->db->table('users')->update(['role' => ''], ['name' => 'Chairman']);
    }
}
