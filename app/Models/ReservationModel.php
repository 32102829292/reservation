<?php
namespace App\Models;
use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'resource_id', 'pc_number', 'reservation_date',
        'start_time', 'end_time', 'purpose', 'status', 'claimed', 'claimed_at', 'approved_by',
        'e_ticket_code', 'created_at', 'visitor_type',
        'visitor_name', 'user_email'
    ];

    protected $useTimestamps = false;

    public function countActiveReservations($userId)
    {
        return $this->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->countAllResults();
    }

    public function blockUser($userId, $days)
    {
        return $this->db->table('user_blocks')->insert([
            'user_id' => $userId,
            'blocked_until' => date('Y-m-d', strtotime("+$days days"))
        ]);
    }

    public function isBlocked($userId)
    {
        return $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();
    }

    public function getUserSameDayReservations($userId, $date)
    {
        return $this->where('user_id', $userId)
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
            ->where('r.user_id', $userId)
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

    public function checkFairness($userId)
    {
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
            return ['fair' => false, 'remaining' => 0, 'blocked' => true, 'until' => $block['blocked_until']];
        }

        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        if ($recentReservations >= 3) {
            $this->blockUser($userId, 14);
            return ['fair' => false, 'remaining' => 0, 'blocked' => true, 'until' => date('Y-m-d', strtotime('+14 days'))];
        }

        return ['fair' => true, 'remaining' => 3 - $recentReservations];
    }

    public function getRemainingReservations($userId)
    {
        $block = $this->db->table('user_blocks')
            ->where('user_id', $userId)
            ->where('blocked_until >=', date('Y-m-d'))
            ->get()
            ->getRowArray();

        if ($block) {
            $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));
            $recentReservations = $this->where('user_id', $userId)
                ->where('created_at >=', $twoWeeksAgo)
                ->countAllResults();

            if ($recentReservations < 3) {
                $this->db->table('user_blocks')
                    ->where('user_id', $userId)
                    ->delete();
                return 3 - $recentReservations;
            }
            return 0;
        }

        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));
        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        return max(0, 3 - $recentReservations);
    }
}