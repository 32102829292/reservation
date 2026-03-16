<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * NotificationController
 *
 * Handles:
 *  - GET  /notifications/check       — JS polls this every 30s for live banner/push
 *  - POST /notifications/save-push   — saves browser push subscription to DB
 *  - POST /notifications/send-email-warning — called by cron for email alerts
 */
class NotificationController extends BaseController
{
    // ──────────────────────────────────────────────────────
    // GET /notifications/check
    // Returns active reservation that ends within 60 minutes
    // ──────────────────────────────────────────────────────
    public function check(): ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->json(['warning' => false]);
        }

        $now     = date('Y-m-d H:i:s');
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $db = db_connect();

        // Find approved reservation happening right now or starting within 60 min
        $reservation = $db->table('reservations r')
            ->select('r.id, r.reservation_date, r.start_time, r.end_time,
                      res.name AS resource_name, r.pc_number')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->where('r.user_id', $userId)
            ->where('r.status', 'approved')
            ->where('r.reservation_date', $nowDate)
            // started already OR starts within 60 min
            ->where('r.start_time <=', date('H:i:s', strtotime('+60 minutes')))
            ->where('r.end_time >', $nowTime)
            ->orderBy('r.start_time', 'ASC')
            ->limit(1)
            ->get()->getRowArray();

        if (!$reservation) {
            return $this->json(['warning' => false]);
        }

        $endTs      = strtotime($reservation['reservation_date'] . ' ' . $reservation['end_time']);
        $startTs    = strtotime($reservation['reservation_date'] . ' ' . $reservation['start_time']);
        $secondsLeft = $endTs - time();
        $minutesLeft = ceil($secondsLeft / 60);
        $hasStarted  = time() >= $startTs;

        // Severity: urgent = ≤10 min, warning = ≤30 min, notice = ≤60 min
        $severity = 'notice';
        if ($minutesLeft <= 10) $severity = 'urgent';
        elseif ($minutesLeft <= 30) $severity = 'warning';

        return $this->json([
            'warning'       => true,
            'severity'      => $severity,
            'minutesLeft'   => (int) $minutesLeft,
            'secondsLeft'   => (int) $secondsLeft,
            'hasStarted'    => $hasStarted,
            'resourceName'  => $reservation['resource_name'] ?? 'Resource',
            'pcNumber'      => $reservation['pc_number'] ?? null,
            'startTime'     => date('g:i A', $startTs),
            'endTime'       => date('g:i A', $endTs),
            'reservationId' => $reservation['id'],
        ]);
    }

    // ──────────────────────────────────────────────────────
    // POST /notifications/save-push
    // Body: { endpoint, keys: { p256dh, auth } }
    // ──────────────────────────────────────────────────────
    public function savePush(): ResponseInterface
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $this->request->getJSON(true);
        if (empty($data['endpoint'])) {
            return $this->json(['success' => false, 'message' => 'Invalid subscription'], 422);
        }

        $db = db_connect();

        // Upsert — one subscription per user (replace if they re-subscribe)
        $existing = $db->table('push_subscriptions')
            ->where('user_id', $userId)
            ->get()->getRowArray();

        $payload = [
            'user_id'  => $userId,
            'endpoint' => $data['endpoint'],
            'p256dh'   => $data['keys']['p256dh']  ?? '',
            'auth'     => $data['keys']['auth']     ?? '',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $db->table('push_subscriptions')->where('user_id', $userId)->update($payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $db->table('push_subscriptions')->insert($payload);
        }

        return $this->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────
    // POST /notifications/send-email-warning  (called by cron)
    // Sends 30-min warning emails to all users with upcoming reservations
    // Protect with a secret token in production
    // ──────────────────────────────────────────────────────
    public function sendEmailWarning(): ResponseInterface
    {
        // Basic cron protection — set CRON_SECRET in your .env
        $secret = $this->request->getHeaderLine('X-Cron-Secret')
               ?: $this->request->getGet('secret');

        $expected = env('CRON_SECRET', '');
        if ($expected && $secret !== $expected) {
            return $this->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $db      = db_connect();
        $nowDate = date('Y-m-d');

        // Find reservations ending in 25–35 minutes (cron runs every 5 min)
        $targetFrom = date('H:i:s', strtotime('+25 minutes'));
        $targetTo   = date('H:i:s', strtotime('+35 minutes'));

        $reservations = $db->table('reservations r')
            ->select('r.id, r.reservation_date, r.start_time, r.end_time,
                      r.pc_number, res.name AS resource_name,
                      u.email, u.name AS user_name,
                      COALESCE(NULLIF(u.full_name,""), u.name) AS display_name')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->join('users u',       'u.id  = r.user_id',      'left')
            ->where('r.status', 'approved')
            ->where('r.reservation_date', $nowDate)
            ->where('r.end_time >=', $targetFrom)
            ->where('r.end_time <=', $targetTo)
            // Don't re-send (track in a simple column or separate table)
            ->where('r.warning_email_sent IS NULL OR r.warning_email_sent', 0)
            ->get()->getResultArray();

        $sent = 0;
        foreach ($reservations as $res) {
            if (empty($res['email'])) continue;

            $ok = $this->sendWarningEmail(
                $res['email'],
                $res['display_name'] ?: $res['user_name'] ?: 'Resident',
                $res['resource_name'] ?? 'Resource',
                $res['pc_number'],
                $res['end_time']
            );

            if ($ok) {
                // Mark as sent so cron doesn't resend
                $db->table('reservations')
                    ->where('id', $res['id'])
                    ->update(['warning_email_sent' => 1]);
                $sent++;
            }
        }

        return $this->json(['success' => true, 'sent' => $sent]);
    }

    // ──────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────
    private function sendWarningEmail(
        string $toEmail,
        string $name,
        string $resource,
        ?string $pcNumber,
        string $endTime
    ): bool {
        $emailService = \Config\Services::email();
        $emailService->setTo($toEmail);
        $emailService->setSubject('⏰ Your reservation ends in 30 minutes');
        $emailService->setMessage($this->buildWarningEmailTemplate($name, $resource, $pcNumber, $endTime));
        return $emailService->send(false);
    }

    private function buildWarningEmailTemplate(
        string $name,
        string $resource,
        ?string $pcNumber,
        string $endTime
    ): string {
        $year       = date('Y');
        $name       = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $resource   = htmlspecialchars($resource, ENT_QUOTES, 'UTF-8');
        $endFormatted = date('g:i A', strtotime($endTime));
        $pcLine     = $pcNumber
            ? '<p style="margin:0 0 6px;font-size:13px;color:#64748b;">PC / Station: <strong style="color:#1e293b;">' . htmlspecialchars($pcNumber, ENT_QUOTES, 'UTF-8') . '</strong></p>'
            : '';

        $html  = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
        $html .= '<meta name="viewport" content="width=device-width,initial-scale=1.0">';
        $html .= '<title>Reservation Ending Soon</title></head>';
        $html .= '<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">';
        $html .= '<tr><td align="center">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">';

        // Logo
        $html .= '<tr><td align="center" style="padding-bottom:24px;">';
        $html .= '<div style="width:52px;height:52px;background:#16a34a;border-radius:16px;display:inline-block;text-align:center;line-height:52px;font-size:24px;">🎓</div>';
        $html .= '<div style="margin-top:10px;font-size:13px;color:#64748b;font-weight:600;">E-Learning Resource Reservation System</div>';
        $html .= '<div style="font-size:11px;color:#94a3b8;margin-top:2px;">Brgy. F De Jesus, Unisan Quezon</div>';
        $html .= '</td></tr>';

        // Card
        $html .= '<tr><td style="background:white;border-radius:24px;border:1px solid #e2e8f0;overflow:hidden;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr><td style="height:4px;background:linear-gradient(90deg,#f59e0b,#d97706);"></td></tr>';
        $html .= '<tr><td style="padding:36px;">';

        // Icon + title
        $html .= '<div style="text-align:center;margin-bottom:20px;">';
        $html .= '<div style="font-size:48px;line-height:1;">⏰</div>';
        $html .= '<p style="margin:12px 0 4px;font-size:20px;font-weight:800;color:#0f172a;">Time Check!</p>';
        $html .= '<p style="margin:0;font-size:14px;color:#64748b;">Your reservation ends in about <strong style="color:#d97706;">30 minutes</strong></p>';
        $html .= '</div>';

        // Details box
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">';
        $html .= '<tr><td style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:16px 20px;">';
        $html .= '<p style="margin:0 0 6px;font-size:13px;color:#64748b;">Hi <strong style="color:#1e293b;">' . $name . '</strong>, your current session details:</p>';
        $html .= '<p style="margin:0 0 6px;font-size:13px;color:#64748b;">Resource: <strong style="color:#1e293b;">' . $resource . '</strong></p>';
        $html .= $pcLine;
        $html .= '<p style="margin:0;font-size:13px;color:#64748b;">Ends at: <strong style="color:#d97706;">' . $endFormatted . '</strong></p>';
        $html .= '</td></tr></table>';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">';
        $html .= '<tr><td style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px 16px;">';
        $html .= '<p style="margin:0;font-size:13px;color:#991b1b;font-weight:600;">Please save your work and prepare to wrap up your session on time.</p>';
        $html .= '</td></tr></table>';

        $html .= '<p style="margin:0 0 24px;font-size:13px;color:#64748b;line-height:1.6;">Thank you for using the E-Learning Resource Reservation System. Please ensure the workstation is left clean for the next user.</p>';
        $html .= '<hr style="border:none;border-top:1px solid #f1f5f9;margin:0 0 20px;">';
        $html .= '<p style="margin:0;font-size:12px;color:#94a3b8;">This is an automated reminder. Please do not reply to this email.</p>';
        $html .= '</td></tr></table></td></tr>';

        // Footer
        $html .= '<tr><td align="center" style="padding-top:20px;">';
        $html .= '<p style="margin:0;font-size:11px;color:#94a3b8;">&copy; ' . $year . ' E-Learning Resource Reservation System &middot; Brgy. F De Jesus, Unisan Quezon</p>';
        $html .= '</td></tr>';

        $html .= '</table></td></tr></table></body></html>';
        return $html;
    }

    private function json(array $data, int $status = 200): ResponseInterface
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }
}