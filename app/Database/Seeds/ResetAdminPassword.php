<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResetAdminPassword extends Seeder
{
    public function run()
    {
        $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        $this->db->table('users')
            ->where('email', 'chairman@example.com')
            ->update([
                'password' => $newPassword,
                'role' => 'admin',
            ]);
        
        echo "Admin password reset successfully!\n";
        echo "Email: chairman@example.com\n";
        echo "Password: admin123\n";
    }
}
