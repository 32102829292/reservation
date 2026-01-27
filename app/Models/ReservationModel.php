<?php
namespace App\Models;
use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'resource_id', 'pc_number', 'reservation_date',
        'start_time', 'end_time', 'purpose', 'status', 'approved_by',
        'e_ticket_code', 'created_at'
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
            r.pc_number,
            res.resource_name AS resource_name
        ')
        ->join('resources res', 'res.id = r.resource_id')
        ->where('r.user_id', $userId)
        ->orderBy('r.reservation_date', 'DESC')
        ->get()
        ->getResultArray();
}

    public function createReservation($data)
    {
        return $this->insert($data);
    }

    public function checkFairness($userId)
    {
        // Count reservations in the last 2 weeks
        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));
        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        if ($recentReservations >= 3) {
            // Block user for 2 weeks
            $this->blockUser($userId, 14); // 14 days = 2 weeks
            return false; // Block the reservation
        }

        return true; // Allow the reservation
    }

    public function getRemainingReservations($userId)
    {
        // Count reservations in the last 2 weeks
        $twoWeeksAgo = date('Y-m-d', strtotime('-2 weeks'));
        $recentReservations = $this->where('user_id', $userId)
            ->where('created_at >=', $twoWeeksAgo)
            ->countAllResults();

        return max(0, 3 - $recentReservations);
    }
}
