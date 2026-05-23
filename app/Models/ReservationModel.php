<?php
namespace App\Models;
use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table      = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'resource_id',
        'reservation_date',
        'start_time',
        'end_time',
        'purpose',
        'pc_number',
        'visitor_type',
        'visitor_name',
        'user_email',
        'status',
        'claimed',
        'claimed_at',
        'approved_by',
        'e_ticket_code',
        'created_at',
        'updated_at',
        'warning_email_sent',
    ];

    protected $useTimestamps = false;

    // ═══════════════════════════════════════════════════════════════════════
    //  BASIC HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    public function countActiveReservations($userId)
    {
        return $this->where('user_id', (int) $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->countAllResults();
    }

    public function blockUser($userId, $days)
    {
        return $this->db->table('user_blocks')->insert([
            'user_id'       => (int) $userId,
            'blocked_until' => date('Y-m-d', strtotime("+$days days")),
        ]);
    }

    public function isBlocked($userId)
    {
        return $this->db->table('user_blocks')
            ->where('user_id', (int) $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();
    }

    public function getUserSameDayReservations($userId, $date)
    {
        return $this->where('user_id', (int) $userId)
            ->where('reservation_date', $date)
            ->findAll();
    }

    public function getUserReservations($userId)
    {
        return $this->db->table('reservations r')
            ->select('
                r.id,
                r.e_ticket_code,
                r.reservation_date,
                r.start_time,
                r.end_time,
                r.purpose,
                r.status,
                r.claimed,
                r.claimed_at,
                r.pc_number,
                res.name AS resource_name
            ')
            ->join('resources res', 'res.id = r.resource_id')
            ->where('r.user_id', (int) $userId)
            ->orderBy('r.reservation_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getUserRequests()
    {
        return $this->db->table('reservations r')
            ->select('
                r.*,
                resources.name as resource_name,
                users.name as visitor_name,
                users.email as user_email
            ')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users', 'users.id = r.user_id', 'left')
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function createReservation($data)
    {
        return $this->insert($data);
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  REGISTERED USER FAIRNESS
    //  3 reservations per rolling 2-week window, enforced via user_blocks.
    //  Accepts an integer user_id OR an email string.
    // ═══════════════════════════════════════════════════════════════════════

    public function checkFairness($userIdOrEmail): array
    {
        // Resolve email → user_id if a string was passed
        if (is_string($userIdOrEmail) && !is_numeric($userIdOrEmail)) {
            $userRow = $this->db->table('users')
                ->select('id')
                ->where('email', $userIdOrEmail)
                ->get()
                ->getRowArray();

            if (!$userRow) {
                return ['fair' => true, 'remaining' => 3];
            }
            $userId = (int) $userRow['id'];
        } else {
            $userId = (int) $userIdOrEmail;
        }

        if ($userId <= 0) {
            return ['fair' => true, 'remaining' => 3];
        }

        // FIX: use reservation_date (date-only) to avoid timezone skew on created_at
        $twoWeeksAgo = date('Y-m-d', strtotime('-14 days'));

        // Check existing block first
        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        if ($block) {
            // Re-count in case old records have aged out of the window
            $recentCount = $this->where('user_id', $userId)
                ->where('reservation_date >=', $twoWeeksAgo)
                ->countAllResults();

            if ($recentCount < 3) {
                // Block is stale — lift it
                $this->db->table('user_blocks')
                    ->where('user_id', $userId)
                    ->delete();
                return ['fair' => true, 'remaining' => 3 - $recentCount];
            }

            return [
                'fair'      => false,
                'remaining' => 0,
                'blocked'   => true,
                'until'     => $block['blocked_until'],
            ];
        }

        // No active block — count recent reservations
        $recentCount = $this->where('user_id', $userId)
            ->where('reservation_date >=', $twoWeeksAgo)
            ->countAllResults();

        if ($recentCount >= 3) {
            $this->blockUser($userId, 14);
            return [
                'fair'      => false,
                'remaining' => 0,
                'blocked'   => true,
                'until'     => date('Y-m-d', strtotime('+14 days')),
            ];
        }

        return ['fair' => true, 'remaining' => 3 - $recentCount];
    }

    public function getRemainingReservations($userId): int
    {
        $userId      = (int) $userId;
        $twoWeeksAgo = date('Y-m-d', strtotime('-14 days'));

        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        $recentCount = $this->where('user_id', $userId)
            ->where('reservation_date >=', $twoWeeksAgo)
            ->countAllResults();

        if ($block) {
            if ($recentCount < 3) {
                $this->db->table('user_blocks')
                    ->where('user_id', $userId)
                    ->delete();
                return 3 - $recentCount;
            }
            return 0;
        }

        return max(0, 3 - $recentCount);
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  WALK-IN FAIRNESS  — 3 reservations per rolling 14-day window
    //
    //  KEY FIXES vs the old version:
    //  1. Filter changed from:
    //       AND LOWER(visitor_type) != 'user'
    //     to:
    //       AND user_id IS NULL
    //     Reason: visitor_type is a free-text field that can be stored as
    //     "Visitor", "visitor", "Walk-in", "Guest", etc., causing missed or
    //     double-counted rows.  user_id IS NULL is the only reliable signal
    //     that a row is a true walk-in — it is always NULL for guests and
    //     always set for registered users.
    //
    //  2. Date window changed from:
    //       AND created_at >= ? (datetime)
    //     to:
    //       AND reservation_date >= ? (date only)
    //     Reason: created_at is a datetime stored in server time; comparing
    //     it to a 14-days-ago timestamp drifts with timezone offsets and can
    //     silently exclude same-day records.  reservation_date is a plain
    //     DATE column and is timezone-agnostic.
    //
    //  3. Walk-in name lookup is now case-insensitive TRIM on both sides so
    //     "Juan dela Cruz", "juan dela cruz", and "  Juan Dela Cruz  " all
    //     resolve to the same person.
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Check the 3-per-14-day fairness quota for a walk-in visitor by name.
     *
     * @param  string $visitorName  The walk-in's full name as entered.
     * @return array{fair:bool, remaining:int, used:int, reset:string|null}
     */
    public function checkWalkInFairness(string $visitorName): array
    {
        $name = trim($visitorName);

        if (empty($name)) {
            return ['fair' => true, 'remaining' => 3, 'used' => 0, 'reset' => null];
        }

        // FIX: use reservation_date (date column) for the window —
        // no timezone drift, consistent with checkGuestLimit() in the controller.
        $twoWeeksAgo = date('Y-m-d', strtotime('-14 days'));

        // FIX: filter by user_id IS NULL (true walk-ins only) instead of
        // LOWER(visitor_type) != 'user', which breaks on inconsistent stored values.
        $usedQuery = $this->db->query("
            SELECT COUNT(*) AS total
            FROM reservations
            WHERE LOWER(TRIM(visitor_name)) = LOWER(TRIM(?))
              AND status NOT IN ('declined', 'canceled')
              AND user_id IS NULL
              AND reservation_date >= ?
        ", [$name, $twoWeeksAgo]);

        $used      = (int) ($usedQuery->getRowArray()['total'] ?? 0);
        $remaining = max(0, 3 - $used);

        if ($used >= 3) {
            // Find the oldest qualifying reservation to tell the visitor
            // exactly when their earliest slot rolls off the 14-day window.
            $oldestQuery = $this->db->query("
                SELECT reservation_date
                FROM reservations
                WHERE LOWER(TRIM(visitor_name)) = LOWER(TRIM(?))
                  AND status NOT IN ('declined', 'canceled')
                  AND user_id IS NULL
                  AND reservation_date >= ?
                ORDER BY reservation_date ASC
                LIMIT 1
            ", [$name, $twoWeeksAgo]);

            $oldest    = $oldestQuery->getRowArray();
            $resetDate = $oldest
                ? date('F j, Y', strtotime($oldest['reservation_date'] . ' +15 days'))
                : date('F j, Y', strtotime('+14 days'));

            return [
                'fair'      => false,
                'remaining' => 0,
                'used'      => $used,
                'reset'     => $resetDate,
            ];
        }

        return [
            'fair'      => true,
            'remaining' => $remaining,
            'used'      => $used,
            'reset'     => null,
        ];
    }

    /**
     * Convenience wrapper — returns only the number of remaining slots.
     */
    public function getWalkInRemaining(string $visitorName): int
    {
        return $this->checkWalkInFairness($visitorName)['remaining'];
    }
}