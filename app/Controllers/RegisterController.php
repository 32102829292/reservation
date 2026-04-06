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

        $userModel = new UserModel();

        // combine name
        $fullName = $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name');

        $userModel->save([
            'name'               => $fullName,
            'email'              => $this->request->getPost('email'),
            'role'               => $this->request->getPost('role'),
            'password'           => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_approved'        => false,
            'is_verified'        => false,

            'verification_token' => bin2hex(random_bytes(32)),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Account created! Please verify your email and wait for approval.');
    }
}