<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AccountModel;
use App\Models\LoginLog;
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    private function isTrue($value): bool
    {
        if ($value === true || $value === 1) return true;
        if (is_string($value)) return in_array(strtolower(trim($value)), ['t', 'true', '1', 'yes'], true);
        return false;
    }

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
            ->select('
                users.id,
                users.name,
                users.email,
                users.role,
                users.status,
                accounts.password,
                accounts.is_verified AS account_verified
            ')
            ->join('accounts', 'accounts.user_id = users.id')
            ->where('users.email', $email)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Invalid email or password.');
            return redirect()->to('/login');
        }

        if (!$this->isTrue($user['account_verified'])) {
            $session->setFlashdata('warning', 'Please verify your email address first. Check your inbox for the verification link.');
            return redirect()->to('/login');
        }

        // Pending approval gate — applies to both SK and residents
        if ($user['status'] === 'pending') {
            $roleLabel = $user['role'] === 'sk' ? 'SK' : 'resident';
            $session->setFlashdata('warning', "Your {$roleLabel} account is pending approval by the Barangay Chairman. You will be notified via email once a decision is made.");
            return redirect()->to('/login');
        }

        if ($user['role'] === 'sk' && $user['status'] === 'rejected') {
            $session->setFlashdata('error', 'Your SK account application was not approved. Please contact the Barangay office for more information.');
            return redirect()->to('/login');
        }

        if ($user['role'] === 'user' && $user['status'] === 'rejected') {
            $session->setFlashdata('error', 'Your resident account was not approved. Please contact the Barangay office for more information.');
            return redirect()->to('/login');
        }

        $session->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        $this->closeStaleSessions($user['id']);

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

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectDashboard();
        }
        return view('auth/register');
    }

    public function registerAction()
    {
        $session = session();

        $firstName = trim($this->request->getPost('first_name') ?? '');
        $lastName  = trim($this->request->getPost('last_name') ?? '');
        $fullName  = trim($firstName . ' ' . $lastName);

        $email           = trim($this->request->getPost('email') ?? '');
        $role            = trim($this->request->getPost('role') ?? '');
        $password        = $this->request->getPost('password') ?? '';
        $confirmPassword = $this->request->getPost('confirm_password') ?? '';

        if (!$firstName || !$lastName || !$email || !$role || !$password || !$confirmPassword) {
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

        $dbRole = (strtolower($role) === 'sk') ? 'sk' : 'user';
        $isSK   = ($dbRole === 'sk');
        $now    = Time::now('Asia/Manila')->toDateTimeString();

        // Both residents and SK require chairman approval — is_approved starts as false
        $userModel->insert([
            'name'        => $fullName,
            'full_name'   => $fullName,
            'email'       => $email,
            'role'        => $dbRole,
            'status'      => 'pending',
            'is_approved' => false,
            'is_verified' => false,
            'created_at'  => $now,
            'updated_at'  => $now,
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
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $sent = $this->sendVerificationEmail($email, $fullName, $verificationToken);

        if (!$sent) {
            $brevoError = session()->getFlashdata('brevo_error') ?? 'unknown';
            $session->setFlashdata('error', 'Email failed: ' . $brevoError);
            return redirect()->to('/login');
        }

        if ($isSK) {
            $session->setFlashdata('success', '✅ Account created! Please check your email (' . $email . ') and click the verification link. After verification, your account will need approval from the Barangay Chairman.');
        } else {
            $session->setFlashdata('success', '✅ Account created! Please check your email (' . $email . ') and click the verification link. After verification, your account will need approval from the Barangay Chairman before you can log in.');
        }

        return redirect()->to('/login');
    }

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

        if ($this->isTrue($account['is_verified'])) {
            session()->setFlashdata('info', 'Your email is already verified. You can log in.');
            return redirect()->to('/login');
        }

        $now = Time::now('Asia/Manila')->toDateTimeString();

        $accountModel->update($account['id'], [
            'is_verified'        => true,
            'verification_token' => null,
            'updated_at'         => $now,
        ]);

        $userModel = new UserModel();
        $user      = $userModel->find($account['user_id']);

        if (!$user) {
            session()->setFlashdata('error', 'Account not found. Please contact support.');
            return redirect()->to('/login');
        }

        $isSK       = ($user['role'] === 'sk');
        $isResident = ($user['role'] === 'user');

        // Both SK and residents stay 'pending' until chairman approves
        $userModel->update($account['user_id'], [
            'is_verified' => true,
            'status'      => 'pending',
            'updated_at'  => $now,
        ]);

        // Notify chairman for both roles
        if ($isSK || $isResident) {
            $this->notifyChairmanNewAccount($user);
        }

        if ($isSK) {
            session()->setFlashdata('info', '✅ Email verified! Your SK account is now pending approval by the Barangay Chairman. You will be notified via email once a decision has been made.');
            return redirect()->to('/login');
        }

        // Resident
        session()->setFlashdata('info', '✅ Email verified! Your resident account is now pending approval by the Barangay Chairman. You will be notified via email once a decision has been made.');
        return redirect()->to('/login');
    }

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

    private function closeStaleSessions(int $userId): void
    {
        $db     = db_connect();
        $cutoff = Time::now('Asia/Manila')->subHours(4)->toDateTimeString();

        $db->table('login_logs')
            ->where('user_id', $userId)
            ->where('logout_time IS NULL')
            ->where('login_time <', $cutoff)
            ->update(['logout_time' => $cutoff]);
    }

    private function sendVerificationEmail(string $to, string $name, string $token): bool
    {
        $verifyUrl = base_url("verify-email/{$token}");
        $apiKey    = env('BREVO_API_KEY', '');

        if (empty($apiKey)) {
            log_message('error', '[AuthController] BREVO_API_KEY is not set');
            return false;
        }

        $body = view('emails/verify_email', [
            'name'      => $name,
            'verifyUrl' => $verifyUrl,
        ]);

        $payload = json_encode([
            'sender'      => [
                'name'  => env('EMAIL_FROM_NAME', 'E-Learning System'),
                'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            ],
            'to'          => [['email' => $to, 'name' => $name]],
            'subject'     => 'Verify Your Email Address — E-Learning System',
            'htmlContent' => $body,
        ]);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'api-key: ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            log_message('error', '[AuthController] Brevo curl error: ' . $curlError);
            session()->setFlashdata('brevo_error', 'curl: ' . $curlError);
            return false;
        }

        if ($httpStatus !== 201) {
            log_message('error', '[AuthController] Brevo API failed: HTTP ' . $httpStatus . ' | ' . $response);
            session()->setFlashdata('brevo_error', 'HTTP ' . $httpStatus . ': ' . $response);
            return false;
        }

        return true;
    }

    private function notifyChairmanNewAccount(array $user): void
    {
        $chairmanEmail = env('CHAIRMAN_EMAIL', 'chairman@elearning.edu.ph');
        $apiKey        = env('BREVO_API_KEY', '');

        if (empty($apiKey)) {
            log_message('error', '[AuthController] BREVO_API_KEY not set for chairman notification');
            return;
        }

        $isSK      = ($user['role'] === 'sk');
        $roleLabel = $isSK ? 'SK Officer' : 'Resident';
        $manageUrl = $isSK ? base_url('admin/manage-sk') : base_url('admin/resident-accounts');

        $body = view('emails/admin_sk_pending', [
            'skName'    => $user['name'],
            'skEmail'   => $user['email'],
            'appliedAt' => $user['created_at'],
            'manageUrl' => $manageUrl,
            'roleLabel' => $roleLabel,
        ]);

        $payload = json_encode([
            'sender'      => [
                'name'  => env('EMAIL_FROM_NAME', 'E-Learning System'),
                'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            ],
            'to'          => [['email' => $chairmanEmail]],
            'subject'     => "New {$roleLabel} Account Awaiting Your Approval — Action Required",
            'htmlContent' => $body,
        ]);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'api-key: ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus !== 201) {
            log_message('error', '[AuthController] Chairman account notification failed: HTTP ' . $httpStatus . ' | ' . $response);
        }
    }
}