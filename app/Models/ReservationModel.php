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

    /**
     * Check fairness quota for a registered user.
     * Accepts either an integer user_id OR an email string.
     * Email is resolved to an integer ID before any WHERE user_id query.
     */
    public function checkFairness($userIdOrEmail): array
    {
        // ── Resolve to integer user_id ────────────────────────────────────
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
        // ─────────────────────────────────────────────────────────────────

        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));

        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        if ($block) {
            $recentReservations = $this->where('user_id', $userId)
                ->where('created_at >=', $twoWeeksAgo)
                ->countAllResults();

            if ($recentReservations < 3) {
                $this->db->table('user_blocks')
                    ->where('user_id', $userId)
                    ->delete();
                return ['fair' => true, 'remaining' => 3 - $recentReservations];
            }

            return [
                'fair'      => false,
                'remaining' => 0,
                'blocked'   => true,
                'until'     => $block['blocked_until'],
            ];
        }

        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        if ($recentReservations >= 3) {
            $this->blockUser($userId, 14);
            return [
                'fair'      => false,
                'remaining' => 0,
                'blocked'   => true,
                'until'     => date('Y-m-d', strtotime('+14 days')),
            ];
        }

        return ['fair' => true, 'remaining' => 3 - $recentReservations];
    }

    public function getRemainingReservations($userId)
    {
        $userId      = (int) $userId;
        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));

        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        if ($block) {
            if ($recentReservations < 3) {
                $this->db->table('user_blocks')
                    ->where('user_id', $userId)
                    ->delete();
                return 3 - $recentReservations;
            }
            return 0;
        }

        return max(0, 3 - $recentReservations);
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  WALK-IN FAIRNESS  — 3 reservations per rolling 2-week window
    //  Keyed on normalised visitor_name (case-insensitive, trimmed).
    //  Declined / canceled reservations do NOT count toward the quota.
    //  Only rows where visitor_type != 'user' are counted, so a registered
    //  user who shares a name with a walk-in cannot inflate the quota.
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Check the 3-per-2-week fairness quota for a walk-in visitor by name.
     *
     * @param  string $visitorName  The walk-in's full name as entered.
     * @return array{fair:bool, remaining:int, used:int, reset:string|null}
     */
    public function checkWalkInFairness(string $visitorName): array
    {
        $name        = mb_strtolower(trim($visitorName));
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-14 days'));

        if (empty($name)) {
            return ['fair' => true, 'remaining' => 3, 'used' => 0, 'reset' => null];
        }

        // Use raw SQL expression to avoid CI4 quoting the function call as a column name
        $used = $this->db->table('reservations')
            ->where("LOWER(TRIM(visitor_name)) = '{$name}'")
            ->whereNotIn('status', ['declined', 'canceled'])
            ->where("LOWER(visitor_type) != 'user'")
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        $remaining = max(0, 3 - $used);

        if ($used >= 3) {
            $oldest = $this->db->table('reservations')
                ->select('created_at')
                ->where("LOWER(TRIM(visitor_name)) = '{$name}'")
                ->whereNotIn('status', ['declined', 'canceled'])
                ->where("LOWER(visitor_type) != 'user'")
                ->where('created_at >=', $twoWeeksAgo)
                ->orderBy('created_at', 'ASC')
                ->limit(1)
                ->get()
                ->getRowArray();

            $resetDate = $oldest
                ? date('F j, Y', strtotime($oldest['created_at'] . ' +14 days'))
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