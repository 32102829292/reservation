<?php

namespace App\Controllers\Traits;

/**
 * StopSessionTrait
 *
 * Shared by AdminController and SkController.
 * Exposes a single POST endpoint:  stopSession()
 *
 * POST body (JSON or form-encoded):
 *   reservation_id   int     required
 *   ended_at_ms      int     required  – client-side Date.now() at click time (ms)
 *
 * Response JSON:
 *   { ok: true,  actual_minutes: 48, message: "Session stopped." }
 *   { ok: false, error: "…" }
 *
 * Drop this file into:
 *   app/Controllers/Traits/StopSessionTrait.php
 *
 * Then add to AdminController AND SkController, inside the class body:
 *   use \App\Controllers\Traits\StopSessionTrait;
 */
trait StopSessionTrait
{
    public function stopSession(): \CodeIgniter\HTTP\ResponseInterface
    {
        // ── Auth guard ───────────────────────────────────────────────────
        if (!session()->has('user_id')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized.']);
        }

        // ── Parse input (supports both JSON and form POST) ───────────────
        $json = $this->request->getJSON(true);
        if ($json) {
            $reservationId = (int)($json['reservation_id'] ?? 0);
            $endedAtMs     = (float)($json['ended_at_ms']   ?? 0);
        } else {
            $reservationId = (int)$this->request->getPost('reservation_id');
            $endedAtMs     = (float)$this->request->getPost('ended_at_ms');
        }

        if (!$reservationId) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Missing reservation_id.']);
        }

        if ($endedAtMs <= 0) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Missing or invalid ended_at_ms.']);
        }

        $db = db_connect();

        // ── Fetch reservation ────────────────────────────────────────────
        $reservation = $db->table('reservations')
            ->where('id', $reservationId)
            ->get()
            ->getRowArray();

        if (!$reservation) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Reservation not found.']);
        }

        if (($reservation['status'] ?? '') !== 'approved') {
            return $this->response->setJSON(['ok' => false, 'error' => 'Reservation is not approved.']);
        }

        $isClaimed = in_array($reservation['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
        if (!$isClaimed) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Reservation has not been claimed yet.']);
        }

        // Prevent double-stop
        if (!empty($reservation['session_ended_at'])) {
            return $this->response->setJSON([
                'ok'             => false,
                'error'          => 'Session was already stopped.',
                'actual_minutes' => (int)($reservation['actual_duration_minutes'] ?? 0),
            ]);
        }

        // ── Calculate actual duration ────────────────────────────────────
        // ended_at_ms is a JS timestamp (UTC milliseconds).
        // We convert to seconds for PHP, then compute against claimed_at.
        $endedAtTs = (int)($endedAtMs / 1000); // seconds since epoch

        // Use claimed_at as the session start (when QR was scanned).
        // Fall back to start_time on reservation_date if claimed_at missing.
        $claimedAt = !empty($reservation['claimed_at'])
            ? strtotime($reservation['claimed_at'])
            : null;

        if (!$claimedAt) {
            // Build start from reservation_date + start_time
            $dateStr = (string)($reservation['reservation_date'] ?? '');
            $timeStr = (string)($reservation['start_time']       ?? '00:00:00');
            // Strip T-prefix if stored as datetime
            $dateStr = substr($dateStr, 0, 10);
            $claimedAt = strtotime($dateStr . ' ' . $timeStr . ' Asia/Manila');
        }

        $actualSeconds = max(0, $endedAtTs - (int)$claimedAt);
        $actualMinutes = (int)ceil($actualSeconds / 60);

        // Cap at scheduled duration + 30 min buffer to guard against bad clocks
        $scheduledEnd   = null;
        $dateStr        = substr((string)($reservation['reservation_date'] ?? ''), 0, 10);
        $endTimeStr     = (string)($reservation['end_time'] ?? '');
        if ($dateStr && $endTimeStr) {
            $scheduledEnd = strtotime($dateStr . ' ' . $endTimeStr . ' Asia/Manila');
        }
        if ($scheduledEnd) {
            $maxSeconds    = ($scheduledEnd - (int)$claimedAt) + 30 * 60;
            $actualSeconds = min($actualSeconds, max(0, $maxSeconds));
            $actualMinutes = (int)ceil($actualSeconds / 60);
        }

        // ── Persist ──────────────────────────────────────────────────────
        $endedAtFormatted = gmdate('Y-m-d H:i:s', $endedAtTs); // stored as UTC; DB should handle TZ

        $db->transStart();

        $db->table('reservations')
            ->where('id', $reservationId)
            ->update([
                'session_ended_at'        => $endedAtFormatted,
                'actual_duration_minutes' => $actualMinutes,
                'updated_at'              => date('Y-m-d H:i:s'),
            ]);

        // Activity log (best-effort)
        try {
            $visitorName = (string)(
                $reservation['visitor_name'] ??
                $reservation['full_name']    ??
                'Guest'
            );
            $db->table('activity_logs')->insert([
                'user_id'        => session()->get('user_id'),
                'action'         => 'stop_session',
                'reservation_id' => $reservationId,
                'details'        => "Stopped session for {$visitorName} — actual: {$actualMinutes} min",
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'StopSessionTrait — activity_logs failed: ' . $e->getMessage());
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'Database error while stopping session.',
            ]);
        }

        return $this->response->setJSON([
            'ok'             => true,
            'actual_minutes' => $actualMinutes,
            'message'        => "Session stopped. Actual usage: {$actualMinutes} min.",
        ]);
    }
}