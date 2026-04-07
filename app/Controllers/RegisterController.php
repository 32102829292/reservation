<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store()
    {
        $rules = [
            'first_name'       => 'required|min_length[2]',
            'last_name'        => 'required|min_length[2]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'role'             => 'required',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $db = \Config\Database::connect();
        
        $sql = "INSERT INTO users (name, email, role, password, is_approved, is_verified, verification_token, created_at) 
                VALUES (?, ?, ?, ?, FALSE, FALSE, ?, ?)";
        
        $db->query($sql, [
            trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
            $this->request->getPost('email'),
            $this->request->getPost('role'),
            password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            bin2hex(random_bytes(32)),
            date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')
            ->with('success', 'Account created! Please wait for approval.');
    }
}