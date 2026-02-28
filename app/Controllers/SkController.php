<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\ActivityLog;
use App\Models\ResourceModel;
use App\Models\PcModel;
use App\Models\UserModel;

class SkController extends BaseController
{
    public function dashboard()
{
    $model = new ReservationModel();
    $db = db_connect();

    // ── SK officer's own reservations (stats + recent list) ──────────────
    $skUserId      = session()->get('user_id');
    $myReservations = $model
        ->where('user_id', $skUserId)
        ->orderBy('reservation_date', 'DESC')
        ->findAll();

    // ── All reservations from everyone (admin + users + SK) for calendar ─
    $allReservations = $model->db->table('reservations r')
        ->select([
            'r.*',
            'COALESCE(u.name, r.visitor_name, r.user_id) AS full_name',
            'u.email                                      AS user_email',
            'res.name                                     AS resource_name',
            'res.description                              AS resource_description',
        ])
        ->join('users u',     'u.id = r.user_id',       'left')
        ->join('resources res', 'res.id = r.resource_id', 'left')
        ->orderBy('r.reservation_date', 'DESC')
        ->get()
        ->getResultArray();

    // ── Get all pending reservations for approval list ───────────────────
    $pendingReservations = $model->db->table('reservations r')
        ->select('
            r.*,
            resources.name as resource_name,
            users.name as visitor_name,
            users.email as user_email
        ')
        ->join('resources', 'resources.id = r.resource_id', 'left')
        ->join('users', 'users.id = r.user_id', 'left')
        ->where('r.status', 'pending')
        ->where('r.user_id !=', $skUserId)
        ->orderBy('r.created_at', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    // ── Stat counts (based on SK's own reservations) ─────────────────────
    $total    = count($myReservations);
    $pending  = count(array_filter($myReservations, fn($r) => $r['status'] === 'pending'));
    $approved = count(array_filter($myReservations, fn($r) => $r['status'] === 'approved'));
    $declined = count(array_filter($myReservations, fn($r) => in_array($r['status'], ['declined', 'canceled'])));
    
    // ── Additional analytics stats ───────────────────────────────────────
    // Total claimed reservations (all users)
    $claimed = $model->where('claimed', 1)->countAllResults();
    
    // Today's stats
    $today = date('Y-m-d');
    $todayTotal = $model->where('reservation_date', $today)->countAllResults();
    $todayApproved = $model->where('reservation_date', $today)->where('status', 'approved')->countAllResults();
    $todayPending = $model->where('reservation_date', $today)->where('status', 'pending')->countAllResults();
    $todayClaimed = $model->where('reservation_date', $today)->where('claimed', 1)->countAllResults();
    
    // Monthly total (last 30 days)
    $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
    $monthlyTotal = $model->where('reservation_date >=', $thirtyDaysAgo)->countAllResults();
    
    // Chart data for last 7 days
    $chartLabels = [];
    $chartData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $chartLabels[] = date('D', strtotime($date));
        $count = $model->where('reservation_date', $date)->countAllResults();
        $chartData[] = $count;
    }
    
    // Resource popularity data
    $resourceQuery = $db->table('reservations r')
        ->select('resources.name, COUNT(*) as count')
        ->join('resources', 'resources.id = r.resource_id')
        ->where('r.status', 'approved')
        ->groupBy('r.resource_id')
        ->orderBy('count', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();
    
    $resourceLabels = [];
    $resourceData = [];
    $topResources = [];
    
    foreach ($resourceQuery as $res) {
        $resourceLabels[] = $res['name'];
        $resourceData[] = (int)$res['count'];
        $topResources[] = [
            'name' => $res['name'],
            'count' => $res['count']
        ];
    }
    
    // If no data, provide defaults
    if (empty($resourceLabels)) {
        $resourceLabels = ['No Data'];
        $resourceData = [1];
        $topResources = [];
    }
    
    // Pending user count for badge
    $pendingUserCount = $model
        ->where('status', 'pending')
        ->where('user_id !=', $skUserId)
        ->countAllResults();
    
    // Approval and utilization rates
    $approvalRate = $total > 0 ? round(($approved / $total) * 100) : 0;
    $utilizationRate = $approved > 0 ? round(($claimed / $approved) * 100) : 0;

    return view('sk/dashboard', [
        'page'                 => 'dashboard',
        'sk_name'              => session()->get('name') ?? session()->get('username'),
        'reservations'         => $myReservations,        // SK's own → stats + recent list
        'allReservations'      => $allReservations,       // Everyone's → calendar
        'pendingReservations'  => $pendingReservations,   // For approval list
        'total'                => $total,
        'pending'              => $pending,
        'approved'             => $approved,
        'declined'             => $declined,
        'claimed'              => $claimed,
        'monthlyTotal'         => $monthlyTotal,
        'todayTotal'           => $todayTotal,
        'todayApproved'        => $todayApproved,
        'todayPending'         => $todayPending,
        'todayClaimed'         => $todayClaimed,
        'chartLabels'          => $chartLabels,
        'chartData'            => $chartData,
        'resourceLabels'       => $resourceLabels,
        'resourceData'         => $resourceData,
        'topResources'         => $topResources,
        'pendingUserCount'     => $pendingUserCount,
        'approvalRate'         => $approvalRate,
        'utilizationRate'      => $utilizationRate,
    ]);
}

    public function reservations()
{
    $model = new ReservationModel();
    $db = db_connect();

    $status = $this->request->getGet('status');
    $date   = $this->request->getGet('date');

    // Build the query
    $query = $model->db->table('reservations r')
        ->select('
            r.*,
            resources.name as resource_name,
            users.name as visitor_name,
            users.email as user_email
        ')
        ->join('resources', 'resources.id = r.resource_id', 'left')
        ->join('users', 'users.id = r.user_id', 'left')
        ->orderBy('r.reservation_date', 'DESC');

    // Apply filters
    if ($status) {
        if ($status === 'claimed') {
            $query->where('r.claimed', 1);
        } else {
            $query->where('r.status', $status);
        }
    }

    if ($date) {
        $query->where('r.reservation_date', $date);
    }

    $reservations = $query->get()->getResultArray();

    // Get total count for stats
    $total = count($reservations);
    
    // Get counts by status - using the same variable names as the view expects
    $pendingCount = $model->where('status', 'pending')->countAllResults();
    $approvedCount = $model->where('status', 'approved')->countAllResults();
    $claimedCount = $model->where('claimed', 1)->countAllResults();
    $declinedCount = $model->where('status', 'declined')->countAllResults();

    // Also create singular variables for backward compatibility
    $pending = $pendingCount;
    $approved = $approvedCount;
    $claimed = $claimedCount;
    $declined = $declinedCount;

    return view('sk/reservations', [
        'page' => 'reservations',
        'reservations' => $reservations,
        'total' => $total,
        'pending' => $pending,
        'approved' => $approved,
        'claimed' => $claimed,
        'declined' => $declined,
        'pendingCount' => $pendingCount,
        'approvedCount' => $approvedCount,
        'claimedCount' => $claimedCount,
        'declinedCount' => $declinedCount,
        'currentStatus' => $status,
        'currentDate' => $date
    ]);
}

    public function approve()
    {
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $reservationModel = new ReservationModel();
        $activityLog = new ActivityLog();
        
        // Start database transaction
        $db = db_connect();
        $db->transStart();
        
        try {
            // Check if reservation exists
            $reservation = $reservationModel->find($id);
            
            if (!$reservation) {
                throw new \Exception('Reservation not found');
            }
            
            // Update reservation status
            $updated = $reservationModel->update($id, [
                'status' => 'approved',
                'approved_by' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update reservation');
            }

            // Log activity
            try {
                $activityLog->insert([
                    'user_id'        => session()->get('user_id'),
                    'action'         => 'approve_user_request',
                    'reservation_id' => $id,
                    'created_at'     => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to log activity: ' . $e->getMessage());
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            return redirect()->back()->with('success', 'Reservation approved successfully!');
            
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Failed to approve reservation: ' . $e->getMessage());
        }
    }

    public function decline()
    {
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $reservationModel = new ReservationModel();
        $activityLog = new ActivityLog();
        
        // Start database transaction
        $db = db_connect();
        $db->transStart();
        
        try {
            // Check if reservation exists
            $reservation = $reservationModel->find($id);
            
            if (!$reservation) {
                throw new \Exception('Reservation not found');
            }
            
            // Update reservation status
            $updated = $reservationModel->update($id, [
                'status' => 'declined',
                'approved_by' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update reservation');
            }

            // Log activity
            try {
                $activityLog->insert([
                    'user_id'        => session()->get('user_id'),
                    'action'         => 'decline_user_request',
                    'reservation_id' => $id,
                    'created_at'     => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to log activity: ' . $e->getMessage());
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            return redirect()->back()->with('success', 'Reservation declined successfully!');
            
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Failed to decline reservation: ' . $e->getMessage());
        }
    }

    public function newReservation()
    {
        $resourceModel = new ResourceModel();
        $pcModel       = new PcModel();
        $userModel     = new UserModel();

        return view('sk/new-reservation', [
            'page'      => 'new-reservation',
            'resources' => $resourceModel->findAll(),
            'pcs'       => $pcModel->findAll(),
            'users'     => $userModel->findAll(),
        ]);
    }

    public function createReservation()
    {
        $request          = $this->request;
        $reservationModel = new ReservationModel();

        $visitorType         = $request->getPost('visitor_type');
        $userEmail           = $request->getPost('user_email');
        $visitorName         = $request->getPost('visitor_name');
        $visitorContactEmail = $request->getPost('visitor_contact_email');
        $reservationDate     = $request->getPost('reservation_date');
        $reservationType     = $request->getPost('reservation_type');
        $startTime           = $request->getPost('start_time');
        $endTime             = $request->getPost('end_time');
        $purpose             = $request->getPost('purpose');
        $pcsJson             = $request->getPost('pcs');
        $pcs                 = json_decode($pcsJson, true) ?? [];

        if (empty($pcs)) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Please select at least one PC']);
            }
            return redirect()->back()->with('error', 'Please select at least one PC');
        }

        // Check AI fairness
        if (!$reservationModel->checkFairness($userEmail)) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'AI Fairness Check: You have exceeded the reservation limit. You are blocked for 2 weeks.']);
            }
            return redirect()->back()->with('error', 'AI Fairness Check: You have exceeded the reservation limit. You are blocked for 2 weeks.');
        }

        // Generate E-Ticket
        $eTicket = $this->generateETicket();

        $data = [
            'user_id'          => $userEmail,
            'visitor_type'     => $visitorType,
            'visitor_name'     => $visitorName,
            'visitor_email'    => $visitorContactEmail,
            'reservation_date' => $reservationDate,
            'reservation_type' => $reservationType,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'purpose'          => $purpose,
            'pc_numbers'       => json_encode($pcs),
            'status'           => 'pending',
            'claimed'          => 0,
            'e_ticket_code'    => $eTicket,
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $reservationModel->insert($data);

        if ($request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Reservation created successfully! AI Fairness Check passed.', 'e_ticket' => $eTicket]);
        }

        return redirect()->to('/sk/reservations')->with('success', 'Reservation created successfully! AI Fairness Check passed.');
    }

    private function generateETicket($length = 8)
    {
        $chars  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ticket = '';
        for ($i = 0; $i < $length; $i++) {
            $ticket .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $ticket;
    }

    public function profile()
    {
        $user = (new UserModel())->find(session()->get('user_id'));
        return view('sk/profile', ['user' => $user]);
    }

    public function scanner()
    {
        // Load ALL reservations for the scanner
        $reservationModel = new ReservationModel();
        $allReservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users', 'users.id = reservations.user_id', 'left')
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();

        return view('sk/scanner', [
            'page' => 'scanner',
            'allReservations' => $allReservations
        ]);
    }

    /**
     * Validate E-Ticket and mark as claimed
     */
    public function validateETicket()
    {
        $code = $this->request->getPost('code');
        
        if (!$code) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No code provided'
            ]);
        }

        $reservationModel = new ReservationModel();
        
        // Find approved reservation that hasn't been claimed yet
        $reservation = $reservationModel
            ->where('e_ticket_code', $code)
            ->where('status', 'approved')
            ->where('claimed', 0)
            ->first();

        if ($reservation) {
            // Mark as claimed
            $reservationModel->update($reservation['id'], [
                'claimed' => 1,
                'claimed_at' => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'E-Ticket validated successfully! Reservation claimed.',
                'updated' => true
            ]);
        } else {
            // Check if it was already claimed
            $alreadyClaimed = $reservationModel
                ->where('e_ticket_code', $code)
                ->where('claimed', 1)
                ->first();
                
            if ($alreadyClaimed) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'This ticket has already been claimed.'
                ]);
            }
            
            // Check if it exists but not approved
            $exists = $reservationModel
                ->where('e_ticket_code', $code)
                ->first();
                
            if ($exists) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Reservation is not yet approved.'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Invalid E-Ticket code.'
            ]);
        }
    }

    public function updateProfile()
    {
        $userModel = new UserModel();
        $userId    = session()->get('user_id');
        $user      = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $name     = $this->request->getPost('name');
        $email    = $this->request->getPost('email');
        $phone    = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        $updateData = [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $updateData);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function downloadCsv()
    {
        $model        = new ReservationModel();
        $reservations = $model->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=sk_reservations.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['ID', 'User ID', 'Resource ID', 'Date', 'Start Time', 'End Time', 'Purpose', 'Status', 'Claimed', 'Claimed At']);

        foreach ($reservations as $r) {
            fputcsv($output, [
                $r['id'],
                $r['user_id'],
                $r['resource_id'],
                $r['reservation_date'],
                $r['start_time'],
                $r['end_time'],
                $r['purpose'],
                $r['status'],
                $r['claimed'] ? 'Yes' : 'No',
                $r['claimed_at'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function myReservations()
    {
        $model  = new ReservationModel();
        $userId = session()->get('user_id');

        $status = $this->request->getGet('status');
        $date   = $this->request->getGet('date');

        if ($status) $model->where('status', $status);
        if ($date)   $model->where('reservation_date', $date);

        $reservations = $model
            ->where('user_id', $userId)
            ->orderBy('reservation_date', 'DESC')
            ->findAll();

        return view('sk/my-reservations', [
            'page'         => 'my-reservations',
            'reservations' => $reservations,
        ]);
    }

    public function activityLogs()
    {
        $logs = (new ActivityLog())
            ->where('user_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('sk/activity-logs', [
            'page' => 'activity-logs',
            'logs' => $logs,
        ]);
    }

    /**
     * Display user reservation requests for approval
     */
    public function userRequests()
    {
        $reservationModel = new ReservationModel();
        
        // Get all user reservations (excluding SK's own)
        $userReservations = $this->getUserReservationsForApproval();
        
        // Count pending requests for badge
        $pendingUserCount = $reservationModel
            ->where('status', 'pending')
            ->where('user_id !=', session()->get('user_id'))
            ->countAllResults();
        
        return view('sk/user_requests', [
            'page' => 'user-requests',
            'userReservations' => $userReservations,
            'pendingUserCount' => $pendingUserCount
        ]);
    }

    /**
     * Get user reservations for approval (excluding SK's own)
     */
    private function getUserReservationsForApproval()
    {
        $db = db_connect();
        $skUserId = session()->get('user_id');
        
        return $db->table('reservations r')
            ->select('
                r.*,
                resources.name as resource_name,
                users.name as visitor_name,
                users.email as user_email,
                users.phone as visitor_phone
            ')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users', 'users.id = r.user_id', 'left')
            ->where('r.user_id IS NOT NULL')
            ->where('r.user_id !=', $skUserId)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check for new user reservation requests (AJAX endpoint for real-time notifications)
     */
    public function checkNewUserRequests()
    {
        $request = $this->request;
        
        if ($request->isAJAX()) {
            $lastCheck = $request->getPost('last_check');
            $skUserId = session()->get('user_id');
            
            if (!$lastCheck) {
                $lastCheck = date('Y-m-d H:i:s', strtotime('-1 hour'));
            }
            
            $db = db_connect();
            
            $newRequests = $db->table('reservations r')
                ->select('
                    r.*,
                    resources.name as resource_name,
                    users.name as visitor_name
                ')
                ->join('resources', 'resources.id = r.resource_id', 'left')
                ->join('users', 'users.id = r.user_id', 'left')
                ->where('r.status', 'pending')
                ->where('r.user_id !=', $skUserId)
                ->where('r.created_at >', $lastCheck)
                ->orderBy('r.created_at', 'DESC')
                ->get()
                ->getResultArray();
            
            return $this->response->setJSON([
                'new_requests' => $newRequests
            ]);
        }
    }

    /**
     * Get pending requests count (for AJAX polling)
     */
    public function getPendingCount()
    {
        if ($this->request->isAJAX()) {
            $skUserId = session()->get('user_id');
            $db = db_connect();
            
            $pendingCount = $db->table('reservations')
                ->where('status', 'pending')
                ->where('user_id !=', $skUserId)
                ->countAllResults();
            
            return $this->response->setJSON([
                'pending_count' => $pendingCount
            ]);
        }
    }

    /**
     * Display claimed reservations with export to Excel
     */
    public function claimedReservations()
    {
        $reservationModel = new ReservationModel();
        
        // Get all claimed reservations
        $claimedReservations = $reservationModel
            ->select('
                reservations.*,
                resources.name as resource_name,
                users.name as visitor_name,
                users.email as user_email
            ')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users', 'users.id = reservations.user_id', 'left')
            ->where('reservations.claimed', 1)
            ->orderBy('reservations.claimed_at', 'DESC')
            ->findAll();

        // Count statistics
        $totalClaimed = count($claimedReservations);
        $todayClaimed = array_filter($claimedReservations, function($r) {
            return $r['claimed_at'] && date('Y-m-d', strtotime($r['claimed_at'])) === date('Y-m-d');
        });
        $todayCount = count($todayClaimed);

        return view('sk/claimed_reservations', [
            'page' => 'claimed-reservations',
            'claimedReservations' => $claimedReservations,
            'totalClaimed' => $totalClaimed,
            'todayCount' => $todayCount
        ]);
    }

    /**
     * Export claimed reservations to Excel (CSV)
     */
    public function exportClaimedToExcel()
    {
        $reservationModel = new ReservationModel();
        
        $claimedReservations = $reservationModel
            ->select('
                reservations.*,
                resources.name as resource_name,
                users.name as visitor_name,
                users.email as user_email
            ')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users', 'users.id = reservations.user_id', 'left')
            ->where('reservations.claimed', 1)
            ->orderBy('reservations.claimed_at', 'DESC')
            ->findAll();

        // Set filename with current date
        $filename = 'claimed_reservations_' . date('Y-m-d_His') . '.csv';

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV Headers
        fputcsv($output, [
            'ID',
            'Reservation Code',
            'Visitor Name',
            'Email',
            'Resource',
            'Workstation',
            'Reservation Date',
            'Start Time',
            'End Time',
            'Purpose',
            'Status',
            'Claimed At',
            'Claimed Date',
            'Claimed Time'
        ]);

        // Add data rows
        foreach ($claimedReservations as $res) {
            // Parse workstation numbers
            $pcNumbers = '';
            if (!empty($res['pc_numbers'])) {
                try {
                    $arr = json_decode($res['pc_numbers'], true);
                    $pcNumbers = is_array($arr) ? implode(', ', $arr) : $res['pc_numbers'];
                } catch (\Exception $e) {
                    $pcNumbers = $res['pc_numbers'];
                }
            } elseif (!empty($res['pc_number'])) {
                $pcNumbers = $res['pc_number'];
            }

            // Split claimed_at into date and time
            $claimedDate = '';
            $claimedTime = '';
            if (!empty($res['claimed_at'])) {
                $claimedDate = date('Y-m-d', strtotime($res['claimed_at']));
                $claimedTime = date('H:i:s', strtotime($res['claimed_at']));
            }

            fputcsv($output, [
                $res['id'],
                $res['e_ticket_code'] ?? '—',
                $res['visitor_name'] ?? $res['full_name'] ?? 'Guest',
                $res['visitor_email'] ?? $res['user_email'] ?? '—',
                $res['resource_name'] ?? ('Resource #' . $res['resource_id']),
                $pcNumbers ?: '—',
                $res['reservation_date'] ?? '—',
                $res['start_time'] ?? '—',
                $res['end_time'] ?? '—',
                $res['purpose'] ?? '—',
                ucfirst($res['status'] ?? 'pending'),
                $res['claimed_at'] ?? '—',
                $claimedDate,
                $claimedTime
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Check for new reservations (for dashboard notifications)
     */
    public function checkNewReservations()
    {
        $request = $this->request;
        
        if ($request->isAJAX()) {
            $lastCheck = $request->getPost('last_check');
            $skUserId = session()->get('user_id');
            
            if (!$lastCheck) {
                $lastCheck = date('Y-m-d H:i:s', strtotime('-1 hour'));
            }
            
            $db = db_connect();
            
            $newReservations = $db->table('reservations r')
                ->select('
                    r.*,
                    resources.name as resource_name,
                    users.name as visitor_name
                ')
                ->join('resources', 'resources.id = r.resource_id', 'left')
                ->join('users', 'users.id = r.user_id', 'left')
                ->where('r.status', 'pending')
                ->where('r.user_id !=', $skUserId)
                ->where('r.created_at >', $lastCheck)
                ->orderBy('r.created_at', 'DESC')
                ->get()
                ->getResultArray();
            
            return $this->response->setJSON([
                'new_reservations' => $newReservations
            ]);
        }
    }
}