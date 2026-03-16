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
            // Invalidate previous unused OTPs
            $db->table('password_reset_otps')
                ->where('email', $email)
                ->where('used', 0)
                ->update(['used' => 1]);

            $otp       = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $db->table('password_reset_otps')->insert([
                'email'      => $email,
                'otp'        => $otp,
                'expires_at' => $expiresAt,
                'used'       => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $sent = $this->sendOtpEmail($email, $otp, $user['full_name'] ?? 'User');

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
            ->where('used', 0)
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

        $db->table('password_reset_otps')->where('id', $row['id'])->update(['used' => 1]);

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
    // Private: send OTP email via Brevo SMTP
    // ──────────────────────────────────────────────
    private function sendOtpEmail(string $toEmail, string $otp, string $name): bool
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($toEmail);
        $emailService->setSubject('Your Password Reset Code – E-Learning Reservation System');
        $emailService->setMessage($this->buildEmailTemplate($name, $otp));
        return $emailService->send(false);
    }

    private function buildEmailTemplate(string $name, string $otp): string
    {
        $year   = date('Y');
        $name   = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $digits = '';

        foreach (str_split($otp) as $digit) {
            $digits .= '<td style="width:44px;height:52px;background:#eff6ff;border:1.5px solid #bfdbfe;'
                . 'border-radius:10px;text-align:center;vertical-align:middle;font-size:26px;'
                . 'font-weight:800;color:#1d4ed8;font-family:Arial,sans-serif;padding:0 6px;">'
                . htmlspecialchars($digit, ENT_QUOTES, 'UTF-8')
                . '</td>';
        }

        $html  = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
        $html .= '<meta name="viewport" content="width=device-width,initial-scale=1.0">';
        $html .= '<title>Password Reset Code</title></head>';
        $html .= '<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">';
        $html .= '<tr><td align="center">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">';

        // Logo row
        $html .= '<tr><td align="center" style="padding-bottom:24px;">';
        $html .= '<div style="width:52px;height:52px;background:#2563eb;border-radius:16px;display:inline-block;text-align:center;line-height:52px;font-size:24px;">🎓</div>';
        $html .= '<div style="margin-top:10px;font-size:13px;color:#64748b;font-weight:600;">E-Learning Resource Reservation System</div>';
        $html .= '<div style="font-size:11px;color:#94a3b8;margin-top:2px;">Brgy. F De Jesus, Unisan Quezon</div>';
        $html .= '</td></tr>';

        // Card
        $html .= '<tr><td style="background:white;border-radius:24px;border:1px solid #e2e8f0;overflow:hidden;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr><td style="height:4px;background:#2563eb;"></td></tr>';
        $html .= '<tr><td style="padding:36px;">';

        $html .= '<p style="margin:0 0 6px;font-size:20px;font-weight:800;color:#0f172a;">Password Reset Request</p>';
        $html .= '<p style="margin:0 0 24px;font-size:14px;color:#64748b;">Hi ' . $name . ', we received a request to reset your password.</p>';
        $html .= '<p style="margin:0 0 12px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;">Your 6-digit verification code</p>';

        $html .= '<table cellpadding="0" cellspacing="6" style="margin-bottom:24px;"><tr>' . $digits . '</tr></table>';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">';
        $html .= '<tr><td style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:12px 16px;">';
        $html .= '<p style="margin:0;font-size:13px;color:#92400e;font-weight:600;">This code expires in <strong>10 minutes</strong>. Do not share it with anyone.</p>';
        $html .= '</td></tr></table>';

        $html .= '<p style="margin:0 0 24px;font-size:13px;color:#64748b;line-height:1.6;">If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>';
        $html .= '<hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 20px;">';
        $html .= '<p style="margin:0;font-size:12px;color:#94a3b8;">This is an automated message. Please do not reply to this email.</p>';

        $html .= '</td></tr></table></td></tr>';

        // Footer
        $html .= '<tr><td align="center" style="padding-top:20px;">';
        $html .= '<p style="margin:0;font-size:11px;color:#94a3b8;">&copy; ' . $year . ' E-Learning Resource Reservation System &middot; Brgy. F De Jesus, Unisan Quezon</p>';
        $html .= '</td></tr>';

        $html .= '</table></td></tr></table></body></html>';

        return $html;
    }

    private function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }
}