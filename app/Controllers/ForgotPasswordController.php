<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ForgotPasswordController extends BaseController
{
    // ──────────────────────────────────────────────
    // POST /forgot-password/send-otp
    // Body: { email }
    // ──────────────────────────────────────────────
    public function sendOtp(): ResponseInterface
    {
        $email = trim($this->request->getJSON(true)['email'] ?? '');

        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonResponse(['success' => false, 'message' => 'Please enter a valid email address.'], 422);
        }

        $db   = db_connect();
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();

        // Throttle: max 3 OTPs per email in 15 minutes
        $recent = $db->table('password_reset_otps')
            ->where('email', $email)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-15 minutes')))
            ->countAllResults();

        if ($recent >= 3) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Too many requests. Please wait 15 minutes before trying again.',
            ], 429);
        }

        if ($user) {
            // Invalidate previous unused OTPs — use false for PostgreSQL boolean
            $db->table('password_reset_otps')
                ->where('email', $email)
                ->where('used', false)
                ->update(['used' => true]);

            $otp       = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $db->table('password_reset_otps')->insert([
                'email'      => $email,
                'otp'        => $otp,
                'expires_at' => $expiresAt,
                'used'       => false,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $sent = $this->sendOtpEmail($email, $otp, $user['full_name'] ?? $user['name'] ?? 'User');

            if (! $sent) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Failed to send email. Please try again later.',
                ], 500);
            }
        }

        // Same response whether email exists or not (prevents email enumeration)
        return $this->jsonResponse([
            'success' => true,
            'message' => 'If that email is registered, a code has been sent.',
        ]);
    }

    // ──────────────────────────────────────────────
    // POST /forgot-password/verify-otp
    // Body: { email, otp }
    // ──────────────────────────────────────────────
    public function verifyOtp(): ResponseInterface
    {
        $data  = $this->request->getJSON(true);
        $email = trim($data['email'] ?? '');
        $otp   = trim($data['otp']   ?? '');

        if (empty($email) || empty($otp)) {
            return $this->jsonResponse(['success' => false, 'message' => 'Invalid request.'], 422);
        }

        $db  = db_connect();
        $row = $db->table('password_reset_otps')
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('used', false)   // PostgreSQL boolean fix
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if (! $row) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid or expired code. Please try again.',
            ], 422);
        }

        $db->table('password_reset_otps')
            ->where('id', $row['id'])
            ->update(['used' => true]);   // PostgreSQL boolean fix

        $resetToken = bin2hex(random_bytes(32));
        session()->set('pwd_reset_token',   $resetToken);
        session()->set('pwd_reset_email',   $email);
        session()->set('pwd_reset_expires', time() + 600);

        return $this->jsonResponse(['success' => true, 'reset_token' => $resetToken]);
    }

    // ──────────────────────────────────────────────
    // POST /forgot-password/reset-password
    // Body: { reset_token, password, password_confirm }
    // ──────────────────────────────────────────────
    public function resetPassword(): ResponseInterface
    {
        $data         = $this->request->getJSON(true);
        $token        = trim($data['reset_token']    ?? '');
        $password     = $data['password']             ?? '';
        $passwordConf = $data['password_confirm']     ?? '';

        $sessionToken   = session()->get('pwd_reset_token');
        $sessionEmail   = session()->get('pwd_reset_email');
        $sessionExpires = session()->get('pwd_reset_expires');

        if (
            empty($token) ||
            $token !== $sessionToken ||
            empty($sessionEmail) ||
            time() > (int) $sessionExpires
        ) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Session expired. Please start over.',
            ], 422);
        }

        if (strlen($password) < 8) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Password must be at least 8 characters.',
            ], 422);
        }

        if ($password !== $passwordConf) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Passwords do not match.',
            ], 422);
        }

        $db   = db_connect();
        $user = $db->table('users')->where('email', $sessionEmail)->get()->getRowArray();

        if (! $user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Account not found. Please try again.',
            ], 422);
        }

        $db->table('accounts')
            ->where('user_id', $user['id'])
            ->update(['password' => password_hash($password, PASSWORD_DEFAULT)]);

        session()->remove(['pwd_reset_token', 'pwd_reset_email', 'pwd_reset_expires']);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Password reset successfully! You can now sign in.',
        ]);
    }

    // ──────────────────────────────────────────────
    // Private: send OTP email via Brevo HTTP API
    // ──────────────────────────────────────────────
    private function sendOtpEmail(string $toEmail, string $otp, string $name): bool
    {
        $apiKey = env('BREVO_API_KEY', '');

        if (empty($apiKey)) {
            log_message('error', '[ForgotPassword] BREVO_API_KEY is not set');
            return false;
        }

        $payload = json_encode([
            'sender'      => [
                'name'  => env('EMAIL_FROM_NAME',    'E-Learning System'),
                'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            ],
            'to'          => [['email' => $toEmail, 'name' => $name]],
            'subject'     => 'Your Password Reset Code — E-Learning Reservation System',
            'htmlContent' => $this->buildEmailTemplate($name, $otp),
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
            log_message('error', '[ForgotPassword] Brevo curl error: ' . $curlError);
            return false;
        }

        if ($httpStatus !== 201) {
            log_message('error', '[ForgotPassword] Brevo API failed: HTTP ' . $httpStatus . ' | ' . $response);
            return false;
        }

        return true;
    }

    private function buildEmailTemplate(string $name, string $otp): string
    {
        $year   = date('Y');
        $name   = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $digits = '';

        foreach (str_split($otp) as $digit) {
            $digits .= '<td style="width:44px;height:52px;background:#eff6ff;border:1.5px solid #bfdbfe;'
                . 'border-radius:10px;text-align:center;vertical-align:middle;font-size:26px;'
                . 'font-weight:800;color:#1d4ed8;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;padding:0 6px;">'
                . htmlspecialchars($digit, ENT_QUOTES, 'UTF-8')
                . '</td><td style="width:6px;"></td>';
        }

        return '<!DOCTYPE html><html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Password Reset Code</title></head>
<body style="margin:0;padding:0;background:#f9f9f8;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;padding:40px 16px;">
  <tr><td align="center">
    <table width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;">
      <tr><td style="background:#ffffff;border-radius:12px;border:1px solid #e5e5e3;overflow:hidden;">

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:#1a1a1a;height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:#f0f0ee;border-radius:6px;padding:4px 12px;">
                <span style="font-size:11px;font-weight:600;color:#6b6b6b;letter-spacing:0.06em;text-transform:uppercase;">Password Reset</span>
              </td></tr>
            </table>

            <h2 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0f0f0e;letter-spacing:-0.4px;">Your verification code</h2>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.65;color:#3d3d3a;">Hi <strong style="color:#0f0f0e;">' . $name . '</strong>, use the code below to reset your password.</p>

            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>' . $digits . '</tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr><td style="background:#fef3c7;border-radius:8px;border:1px solid #fde68a;padding:12px 16px;">
                <p style="margin:0;font-size:13px;color:#92400e;font-weight:600;">This code expires in <strong>10 minutes</strong>. Do not share it with anyone.</p>
              </td></tr>
            </table>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#b5b3ad;">If you did not request a password reset, you can safely ignore this email.</p>

          </td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="border-top:1px solid #f0f0ee;padding:16px 40px 20px;">
            <p style="margin:0;font-size:12px;color:#b5b3ad;">&copy; ' . $year . ' E-Learning Resource Reservation System &middot; Brgy. F. De Jesus, Unisan, Quezon</p>
          </td></tr>
        </table>

      </td></tr>
    </table>
  </td></tr>
</table>
</body></html>';
    }

    private function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }
}