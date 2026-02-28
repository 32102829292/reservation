<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AccountModel;
use App\Models\LoginLog;
use CodeIgniter\I18n\Time;

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

        $email    = $request->getPost('email');
        $password = $request->getPost('password');

        if (!$email || !$password) {
            $session->setFlashdata('error', 'Please fill all fields');
            return redirect()->to('/login');
        }

        $userModel = new UserModel();

        // JOIN users with accounts to get password
        $user = $userModel
            ->select('users.*, accounts.password, accounts.verification_token')
            ->join('accounts', 'accounts.user_id = users.id')
            ->where('users.email', $email)
            ->first();

        if ($user && password_verify($password, $user['password'])) {

            if ($user['role'] === 'sk' && !($user['is_approved'] ?? 0)) {
                $session->setFlashdata('error', 'Your account is not approved yet.');
                return redirect()->to('/login');
            }

            $session->set([
                'user_id'    => $user['id'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            $loginLog = new LoginLog();
            $loginLog->insert([
                'user_id'    => $user['id'],
                'role'       => $user['role'],
                'login_time' => Time::now('Asia/Manila')->toDateTimeString()
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
        return view('auth/register');
    }

    public function registerAction()
    {
        $session = session();
        $request = $this->request;

        $fullName        = $request->getPost('full_name');
        $email           = $request->getPost('email');
        $role            = $request->getPost('role');
        $password        = $request->getPost('password');
        $confirmPassword = $request->getPost('confirm_password');

        if (!$fullName || !$email || !$password || !$confirmPassword) {
            $session->setFlashdata('error', 'Please fill all fields');
            return redirect()->to('/register');
        }

        if ($password !== $confirmPassword) {
            $session->setFlashdata('error', 'Passwords do not match');
            return redirect()->to('/register');
        }

        $userModel = new UserModel();

        if ($userModel->where('email', $email)->first()) {
            $session->setFlashdata('error', 'Email already exists');
            return redirect()->to('/register');
        }

        $isApproved = $role === 'sk' ? 0 : 1;

        // Step 1: Insert profile info into users
        $userModel->insert([
            'name'        => $fullName,
            'email'       => $email,
            'role'        => $role,
            'is_approved' => $isApproved,
            'is_verified' => 0,
            'created_at'  => Time::now('Asia/Manila')->toDateTimeString()
        ]);

        $userId = $userModel->getInsertID();

        // Step 2: Insert credentials into accounts
        $accountModel      = new AccountModel();
        $hash              = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = bin2hex(random_bytes(16));

        $accountModel->insert([
            'user_id'            => $userId,
            'password'           => $hash,
            'verification_token' => $verificationToken,
            'created_at'         => Time::now('Asia/Manila')->toDateTimeString()
        ]);

        $session->setFlashdata('success', 'Registered successfully! Please verify your email.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        $session = session();
        $user_id = $session->get('user_id');

        if ($user_id) {
            $loginLog = new LoginLog();

            // Only update the latest active session of this user
            $latestLog = $loginLog
                ->where('user_id', $user_id)
                ->where('logout_time', null)
                ->orderBy('login_time', 'DESC')
                ->first();

            if ($latestLog) {
                $loginLog->update($latestLog['id'], [
                    'logout_time' => Time::now('Asia/Manila')->toDateTimeString()
                ]);
            }
        }

        $session->destroy();
        return redirect()->to('/login');
    }

    public function verifyEmail($token)
    {
        $accountModel = new AccountModel();
        $account      = $accountModel->where('verification_token', $token)->first();

        if ($account) {
            $accountModel->update($account['id'], [
                'is_verified'        => 1,
                'verification_token' => null
            ]);

            session()->setFlashdata('success', 'Email verified! You can now log in.');
            return redirect()->to('/login');
        }

        session()->setFlashdata('error', 'Invalid verification link.');
        return redirect()->to('/login');
    }

    public function redirectDashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

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