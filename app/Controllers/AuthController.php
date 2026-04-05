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

        // FIX: Explicitly list columns to avoid `is_verified` collision
        //      between users.* and accounts.is_verified.
        $user = $userModel
            ->select('users.id, users.name, users.email, users.role, users.status, accounts.password, accounts.is_verified')
            ->join('accounts', 'accounts.user_id = users.id')
            ->where('users.email', $email)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Invalid email or password.');
            return redirect()->to('/login');
        }

        // Handle multiple possible DB representations of boolean true
        $isVerified = in_array($user['is_verified'], [true, 1, 't', 'true', '1']);

        if (!$isVerified) {
            $session->setFlashdata('error', 'Please verify your email address first. Check your inbox for the verification link.');
            return redirect()->to('/login');
        }

        if ($user['role'] === 'sk' && $user['status'] === 'pending') {
            $session->setFlashdata('error', 'Your SK account is pending approval by the Barangay Chairman. You will be notified via email once a decision is made.');
            return redirect()->to('/login');
        }

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

        // FIX: compare lowercase so both 'SK' and 'sk' are accepted
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

        // Map view role values ('SK' / 'resident') to DB role values ('sk' / 'user')
        $dbRole   = (strtolower($role) === 'sk') ? 'sk' : 'user';
        $isSK     = ($dbRole === 'sk');
        $now      = Time::now('Asia/Manila')->toDateTimeString();

        $userModel->insert([
            'name'        => $fullName,
            'full_name'   => $fullName,
            'email'       => $email,
            'role'        => $dbRole,
            'status'      => 'pending',
            // FIX: use integer 0/1 instead of PHP bool to be driver-agnostic
            'is_approved' => $isSK ? 0 : 1,
            'is_verified' => 0,
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
            'is_verified'        => 0,
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
            $session->setFlashdata('success', '✅ Account created! Please check your email (' . $email . ') and click the verification link to activate your account.');
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

        // FIX: same broad boolean check used in loginAction
        $alreadyVerified = in_array($account['is_verified'], [true, 1, 't', 'true', '1']);

        if ($alreadyVerified) {
            session()->setFlashdata('info', 'Your email is already verified. You can log in.');
            return redirect()->to('/login');
        }

        $now = Time::now('Asia/Manila')->toDateTimeString();

        $accountModel->update($account['id'], [
            'is_verified'        => 1,
            'verification_token' => null,
            'updated_at'         => $now,
        ]);

        $userModel = new UserModel();
        $user      = $userModel->find($account['user_id']);

        if (!$user) {
            session()->setFlashdata('error', 'Account not found. Please contact support.');
            return redirect()->to('/login');
        }

        $isSK = ($user['role'] === 'sk');

        $userModel->update($account['user_id'], [
            'is_verified' => 1,
            'status'      => $isSK ? 'pending' : 'approved',
            'updated_at'  => $now,
        ]);

        if ($isSK) {
            $this->notifyChairmanNewSK($user);
            session()->setFlashdata('info', '✅ Email verified! Your SK account is now pending approval by the Barangay Chairman. You will receive an email notification once a decision has been made.');
            return redirect()->to('/login');
        }

        session()->setFlashdata('success', '✅ Email verified successfully! You can now log in.');
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

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

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

    private function notifyChairmanNewSK(array $user): void
    {
        $chairmanEmail = env('CHAIRMAN_EMAIL', 'chairman@elearning.edu.ph');
        $apiKey        = env('BREVO_API_KEY', '');

        if (empty($apiKey)) {
            log_message('error', '[AuthController] BREVO_API_KEY not set for chairman notification');
            return;
        }

        $body = view('emails/admin_sk_pending', [
            'skName'    => $user['name'],
            'skEmail'   => $user['email'],
            'appliedAt' => $user['created_at'],
            'manageUrl' => base_url('admin/manage-sk'),
        ]);

        $payload = json_encode([
            'sender'      => [
                'name'  => env('EMAIL_FROM_NAME', 'E-Learning System'),
                'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            ],
            'to'          => [['email' => $chairmanEmail]],
            'subject'     => 'New SK Account Awaiting Your Approval — Action Required',
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
            log_message('error', '[AuthController] Chairman SK notification failed: HTTP ' . $httpStatus . ' | ' . $response);
        }
    }
}