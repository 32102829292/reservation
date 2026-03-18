<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AccountModel;
use App\Models\LoginLog;
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    /* ══════════════════════════════════════════════════════
     |  LOGIN
     ══════════════════════════════════════════════════════ */

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/login');
    }

    public function loginAction()
    {
        $session  = session();
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            $session->setFlashdata('error', 'Please fill in all fields.');
            return redirect()->to('/login');
        }

        $userModel = new UserModel();

        $user = $userModel
            ->select('users.*, accounts.password, accounts.is_verified')
            ->join('accounts', 'accounts.user_id = users.id')
            ->where('users.email', $email)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Invalid email or password.');
            return redirect()->to('/login');
        }

        if (!$user['is_verified']) {
            $session->setFlashdata('error', 'Please verify your email address first. Check your inbox for the verification link.');
            return redirect()->to('/login');
        }

        // SK pending chairman approval
        if ($user['role'] === 'sk' && $user['status'] === 'pending') {
            $session->setFlashdata('error', 'Your SK account is pending approval by the Barangay Chairman. You will be notified via email once a decision is made.');
            return redirect()->to('/login');
        }

        // SK rejected
        if ($user['role'] === 'sk' && $user['status'] === 'rejected') {
            $session->setFlashdata('error', 'Your SK account application was not approved. Please contact the Barangay office for more information.');
            return redirect()->to('/login');
        }

        $session->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        $loginLog = new LoginLog();
        $loginLog->insert([
            'user_id'    => $user['id'],
            'role'       => $user['role'],
            'login_time' => Time::now('Asia/Manila')->toDateTimeString(),
        ]);

        switch ($user['role']) {
            case 'chairman': return redirect()->to('/admin/dashboard');
            case 'sk':       return redirect()->to('/sk/dashboard');
            default:         return redirect()->to('/dashboard');
        }
    }

    /* ══════════════════════════════════════════════════════
     |  REGISTER
     ══════════════════════════════════════════════════════ */

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/register');
    }

    public function registerAction()
    {
        $session         = session();
        $fullName        = trim($this->request->getPost('full_name'));
        $email           = trim($this->request->getPost('email'));
        $role            = $this->request->getPost('role');
        $password        = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!$fullName || !$email || !$role || !$password || !$confirmPassword) {
            $session->setFlashdata('error', 'Please fill in all fields.');
            return redirect()->to('/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->setFlashdata('error', 'Please enter a valid email address.');
            return redirect()->to('/register');
        }

        if (!in_array(strtolower($role), ['resident', 'sk'])) {
            $session->setFlashdata('error', 'Invalid role selected.');
            return redirect()->to('/register');
        }

        if ($password !== $confirmPassword) {
            $session->setFlashdata('error', 'Passwords do not match.');
            return redirect()->to('/register');
        }

        if (strlen($password) < 8) {
            $session->setFlashdata('error', 'Password must be at least 8 characters long.');
            return redirect()->to('/register');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $session->setFlashdata('error', 'Password must contain at least one uppercase letter.');
            return redirect()->to('/register');
        }

        if (!preg_match('/[0-9]/', $password)) {
            $session->setFlashdata('error', 'Password must contain at least one number.');
            return redirect()->to('/register');
        }

        $userModel = new UserModel();

        if ($userModel->where('email', $email)->first()) {
            $session->setFlashdata('error', 'That email address is already registered.');
            return redirect()->to('/register');
        }

        // Map to correct DB enum values
        $dbRole = strtolower($role) === 'sk' ? 'sk' : 'user';

        $userModel->insert([
            'name'        => $fullName,
            'full_name'   => $fullName,
            'email'       => $email,
            'role'        => $dbRole,
            'status'      => 'pending',
            'is_approved' => ($dbRole === 'sk') ? false : true,
            'is_verified' => false,
            'created_at'  => Time::now('Asia/Manila')->toDateTimeString(),
            'updated_at'  => Time::now('Asia/Manila')->toDateTimeString(),
        ]);

        $userId = $userModel->getInsertID();

        if (!$userId) {
            $session->setFlashdata('error', 'Registration failed. Please try again.');
            return redirect()->to('/register');
        }

        $accountModel      = new AccountModel();
        $verificationToken = bin2hex(random_bytes(32));

        $accountModel->insert([
            'user_id'            => $userId,
            'password'           => password_hash($password, PASSWORD_DEFAULT),
            'verification_token' => $verificationToken,
            'is_verified'        => false,
            'created_at'         => Time::now('Asia/Manila')->toDateTimeString(),
            'updated_at'         => Time::now('Asia/Manila')->toDateTimeString(),
        ]);

        $sent = $this->sendVerificationEmail($email, $fullName, $verificationToken);

        if (!$sent) {
            $session->setFlashdata('error', 'Account created but we could not send the verification email. Please contact support.');
            return redirect()->to('/login');
        }

        if ($dbRole === 'sk') {
            $session->setFlashdata('success', '✅ Account created! Please check your email (' . $email . ') and click the verification link. After verification, your account will need approval from the Barangay Chairman.');
        } else {
            $session->setFlashdata('success', '✅ Account created! Please check your email (' . $email . ') and click the verification link to activate your account.');
        }

        return redirect()->to('/login');
    }

    /* ══════════════════════════════════════════════════════
     |  EMAIL VERIFICATION
     ══════════════════════════════════════════════════════ */

    public function verifyEmail($token)
    {
        if (!$token) {
            session()->setFlashdata('error', 'Invalid verification link.');
            return redirect()->to('/login');
        }

        $accountModel = new AccountModel();
        $account      = $accountModel->where('verification_token', $token)->first();

        if (!$account) {
            session()->setFlashdata('error', 'This verification link is invalid or has already been used.');
            return redirect()->to('/login');
        }

        if ($account['is_verified']) {
            session()->setFlashdata('info', 'Your email is already verified. You can log in.');
            return redirect()->to('/login');
        }

        $accountModel->update($account['id'], [
            'is_verified'        => true,
            'verification_token' => null,
            'updated_at'         => Time::now('Asia/Manila')->toDateTimeString(),
        ]);

        $userModel = new UserModel();
        $userModel->update($account['user_id'], [
            'is_verified' => true,
            'status'      => 'approved',
            'updated_at'  => Time::now('Asia/Manila')->toDateTimeString(),
        ]);

        $user = $userModel->find($account['user_id']);

        if ($user['role'] === 'sk') {
            $this->notifyChairmanNewSK($user);
            session()->setFlashdata('info', '✅ Email verified! Your SK account is now pending approval by the Barangay Chairman. You will receive an email notification once a decision has been made.');
            return redirect()->to('/login');
        }

        session()->setFlashdata('success', '✅ Email verified successfully! You can now log in.');
        return redirect()->to('/login');
    }

    /* ══════════════════════════════════════════════════════
     |  LOGOUT
     ══════════════════════════════════════════════════════ */

    public function logout()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if ($userId) {
            $loginLog  = new LoginLog();
            $latestLog = $loginLog
                ->where('user_id', $userId)
                ->where('logout_time', null)
                ->orderBy('login_time', 'DESC')
                ->first();

            if ($latestLog) {
                $loginLog->update($latestLog['id'], [
                    'logout_time' => Time::now('Asia/Manila')->toDateTimeString(),
                ]);
            }
        }

        $session->destroy();
        return redirect()->to('/login');
    }

    /* ══════════════════════════════════════════════════════
     |  DASHBOARD REDIRECT
     ══════════════════════════════════════════════════════ */

    public function redirectDashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        switch (session()->get('role')) {
            case 'chairman': return redirect()->to('/admin/dashboard');
            case 'sk':       return redirect()->to('/sk/dashboard');
            default:         return redirect()->to('/dashboard');
        }
    }

    /* ══════════════════════════════════════════════════════
     |  PRIVATE EMAIL HELPERS
     ══════════════════════════════════════════════════════ */

    private function sendVerificationEmail(string $to, string $name, string $token): bool
    {
        $verifyUrl    = base_url("verify-email/{$token}");
        $config       = new \Config\Email();
        $emailService = \Config\Services::email($config, false);

        $emailService->clear();
        $emailService->setTo($to);
        $emailService->setFrom(
            env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            env('EMAIL_FROM_NAME',    'E-Learning System')
        );
        $emailService->setSubject('Verify Your Email Address — E-Learning System');
        $emailService->setMessage(view('emails/verify_email', [
            'name'      => $name,
            'verifyUrl' => $verifyUrl,
        ]));
        $emailService->setMailType('html');

        $result = $emailService->send();

        if (!$result) {
            log_message('error', '[AuthController] Verify email FAILED for ' . $to . ' | ' . $emailService->printDebugger(['headers']));
        }

        return $result;
    }

    private function notifyChairmanNewSK(array $user): void
    {
        $chairmanEmail = env('CHAIRMAN_EMAIL', 'chairman@elearning.edu.ph');
        $manageUrl     = base_url('admin/manage-sk');
        $config        = new \Config\Email();
        $emailService  = \Config\Services::email($config, false);

        $emailService->clear();
        $emailService->setTo($chairmanEmail);
        $emailService->setFrom(
            env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            env('EMAIL_FROM_NAME',    'E-Learning System')
        );
        $emailService->setSubject('New SK Account Awaiting Your Approval — Action Required');
        $emailService->setMessage(view('emails/admin_sk_pending', [
            'skName'    => $user['name'],
            'skEmail'   => $user['email'],
            'appliedAt' => $user['created_at'],
            'manageUrl' => $manageUrl,
        ]));
        $emailService->setMailType('html');

        if (!$emailService->send()) {
            log_message('error', '[AuthController] Chairman SK notification FAILED: ' . $emailService->printDebugger(['headers']));
        }
    }
}