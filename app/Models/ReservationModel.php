<?php
namespace App\Models;
use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'resource_id', 'pc_number', 'pc_numbers', 'reservation_date',
        'start_time', 'end_time', 'purpose', 'status', 'claimed', 'claimed_at', 'approved_by',
        'e_ticket_code', 'created_at', 'updated_at', 'visitor_type',
        'visitor_name', 'visitor_email', 'user_email', 'reservation_type',
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

    /**
     * Get all user reservation requests (for SK approval)
     */
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
     * Check fairness quota for a user.
     *
     * Accepts either an integer user ID or an email string.
     * If an email is passed, it is resolved to an integer user_id first
     * so that every downstream query uses WHERE user_id = <integer>,
     * which prevents the PostgreSQL "invalid input syntax for type integer" error.
     *
     * @param  int|string $userIdOrEmail  Integer user_id OR email address string
     * @return array  ['fair' => bool, 'remaining' => int, ...]
     */
    public function checkFairness($userIdOrEmail): array
    {
        // ── Resolve to an integer user_id ─────────────────────────────────
        if (is_string($userIdOrEmail) && !is_numeric($userIdOrEmail)) {
            // An email string was passed — look up the integer ID
            $userRow = $this->db->table('users')
                ->select('id')
                ->where('email', $userIdOrEmail)   // string = string: safe
                ->get()
                ->getRowArray();

            if (!$userRow) {
                // Unknown email — no history, allow the reservation
                return ['fair' => true, 'remaining' => 3];
            }

            $userId = (int) $userRow['id'];
        } else {
            // Already an integer (or a numeric string) — cast to int
            $userId = (int) $userIdOrEmail;
        }

        if ($userId <= 0) {
            return ['fair' => true, 'remaining' => 3];
        }
        // ─────────────────────────────────────────────────────────────────

        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));

        // All queries below use the integer $userId — no type mismatch possible
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
        $userId = (int) $userId;

        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        $twoWeeksAgo        = date('Y-m-d', strtotime('-2 weeks'));
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
}