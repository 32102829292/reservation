<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LandingController extends BaseController
{
    public function index()
    {
        // If user is already logged in, redirect based on role
        if (session()->get('user_id')) {

            $role = session()->get('role');

            if ($role === 'chairman') {
                return redirect()->to('/admin/dashboard');
            } elseif ($role === 'sk') {
                return redirect()->to('/sk/dashboard');
            } else {
                return redirect()->to('/dashboard');
            }
        }

        // If not logged in, show landing page
        return view('landing');
    }
}