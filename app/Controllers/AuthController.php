<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginAction()
    {
        $session = session();
        $request = $this->request;

        $email = $request->getPost('email'); // changed from name to email
        $password = $request->getPost('password');

        if (!$email || !$password) {
            $session->setFlashdata('error', 'Please fill all fields');
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] === 'sk' && !($user['is_approved'] ?? 0)) {
                $session->setFlashdata('error', 'Your account is not approved yet.');
                return redirect()->to('/login');
            }

            $session->set([
                'user_id' => $user['id'],
                'email'    => $user['email'],
                'role'    => $user['role'],
                'isLoggedIn' => true
            ]);

            switch ($user['role']) {
                case 'chairman':
                    return redirect()->to('/admin/dashboard');
                case 'sk':
                    return redirect()->to('/sk/dashboard');
                default:
                    return redirect()->to('/dashboard');
            }
        }

        $session->setFlashdata('error', 'Invalid credentials');
        return redirect()->to('/login');
    }

    public function register()
{
    return view('auth/register');  // Make sure the file is at app/Views/auth/register.php
}

public function registerAction()
{
    $session = session();
    $request = $this->request;

    $fullName = $request->getPost('full_name');
    $email = $request->getPost('email');
    $role = $request->getPost('role');
    $password = $request->getPost('password');
    $confirmPassword = $request->getPost('confirm_password');

    if (!$fullName || !$email || !$password || !$confirmPassword) {
        $session->setFlashdata('error', 'Please fill all fields');
        return redirect()->to('/register');
    }

    if ($password !== $confirmPassword) {
        $session->setFlashdata('error', 'Passwords do not match');
        return redirect()->to('/register');
    }

    $userModel = new \App\Models\UserModel();

    if ($userModel->where('email', $email)->first()) {
        $session->setFlashdata('error', 'Email already exists');
        return redirect()->to('/register');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $verificationToken = bin2hex(random_bytes(16));
    $isApproved = $role === 'sk' ? 0 : 1;

    $userModel->insert([
        'full_name' => $fullName,
        'email' => $email,
        'role' => $role,
        'password' => $hash,
        'is_approved' => $isApproved,
        'is_verified' => 0,
        'verification_token' => $verificationToken,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $session->setFlashdata('success', 'Registered successfully! Please verify your email.');
    return redirect()->to('/login');
}


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Optional: method to verify email
    public function verifyEmail($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('verification_token', $token)->first();

        if ($user) {
            $userModel->update($user['id'], ['is_verified' => 1, 'verification_token' => null]);
            session()->setFlashdata('success', 'Email verified! You can now log in.');
            return redirect()->to('/login');
        }

        session()->setFlashdata('error', 'Invalid verification link.');
        return redirect()->to('/login');
    }

    public function redirectDashboard()
    {
        $session = session();
        
        // If not logged in, redirect to login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // If logged in, redirect to appropriate dashboard based on role
        $role = $session->get('role');
        if ($role === 'chairman') {
            return redirect()->to('/admin/dashboard');
        } elseif ($role === 'sk') {
            return redirect()->to('/sk/dashboard');
        } else {
            return redirect()->to('/dashboard');
        }
    }
}
