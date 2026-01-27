<?php

namespace App\Controllers;

use App\Models\UserModel;

class FixController extends BaseController
{
    public function fixAdminRole()
    {
        $userModel = new UserModel();
        
        // Update all Chairman to admin
        $userModel->whereIn('id', [1, 4])->set(['role' => 'admin'])->update();
        
        echo "Admin role fixed!";
    }
}
