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
        $db    = db_connect();

        $skUserId = session()->get('user_id');

        $myReservations = $model->db->table('reservations r')
            ->select([
                'r.*',
                'COALESCE(u.name, r.visitor_name) AS visitor_name',
                'u.email                           AS user_email',
                'res.name                          AS resource_name',
            ])
            ->join('users u',       'u.id = r.user_id',       'left')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->where('r.user_id', $skUserId)
            ->orderBy('r.reservation_date', 'DESC')
            ->get()
            ->getResultArray();

        $allReservations = $model->db->table('reservations r')
            ->select([
                'r.*',
                'COALESCE(u.name, r.visitor_name, r.user_id::text) AS full_name',
                'u.email                                      AS user_email',
                'res.name                                     AS resource_name',
                'res.description                              AS resource_description',
            ])
            ->join('users u',       'u.id = r.user_id',       'left')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->orderBy('r.reservation_date', 'DESC')
            ->get()
            ->getResultArray();

        $pendingReservations = $model->db->table('reservations r')
            ->select('r.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users',     'users.id = r.user_id',         'left')
            ->where('r.status', 'pending')
            ->where('r.user_id !=', $skUserId)
            ->orderBy('r.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $total    = count($myReservations);
        $pending  = count(array_filter($myReservations, fn($r) => ($r['status'] ?? '') === 'pending'));
        $approved = count(array_filter($myReservations, fn($r) => ($r['status'] ?? '') === 'approved'));
        $declined = count(array_filter($myReservations, fn($r) => in_array($r['status'] ?? '', ['declined', 'canceled'])));

        $claimed = $model
            ->where('user_id', $skUserId)
            ->where('claimed', true)
            ->countAllResults();

        $today         = date('Y-m-d');
        $todayTotal    = $model->where('reservation_date', $today)->countAllResults();
        $todayApproved = $model->where('reservation_date', $today)->where('status', 'approved')->countAllResults();
        $todayPending  = $model->where('reservation_date', $today)->where('status', 'pending')->countAllResults();
        $todayClaimed  = $model->where('reservation_date', $today)->where('claimed', true)->countAllResults();

        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $monthlyTotal  = $model
            ->where('user_id', $skUserId)
            ->where('reservation_date >=', $thirtyDaysAgo)
            ->countAllResults();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            $chartData[]   = $model
                ->where('user_id', $skUserId)
                ->where('reservation_date', $date)
                ->countAllResults();
        }

        $resourceQuery = $db->table('reservations r')
            ->select('resources.name, COUNT(*) as count')
            ->join('resources', 'resources.id = r.resource_id')
            ->where('r.user_id', $skUserId)
            ->where('r.status', 'approved')
            ->groupBy('r.resource_id, resources.name')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $resourceLabels = [];
        $resourceData   = [];
        $topResources   = [];
        foreach ($resourceQuery as $res) {
            $resourceLabels[] = $res['name'];
            $resourceData[]   = (int) $res['count'];
            $topResources[]   = ['name' => $res['name'], 'count' => $res['count']];
        }
        if (empty($resourceLabels)) {
            $resourceLabels = ['No Data'];
            $resourceData   = [1];
            $topResources   = [];
        }

        $pendingUserCount = $model
            ->where('status', 'pending')
            ->where('user_id !=', $skUserId)
            ->countAllResults();

        $approvalRate    = $total > 0    ? round(($approved / $total) * 100)   : 0;
        $utilizationRate = $approved > 0 ? round(($claimed  / $approved) * 100) : 0;

        // ── Books ──
        $allBooks = $db->table('books')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $totalBooks     = count($allBooks);
        $availableCount = count(array_filter($allBooks, fn($b) => (int)($b['available_copies'] ?? 0) > 0));

        // ── My Borrowings ──
        $myBorrowings = $db->table('book_borrowings bb')
            ->select('bb.*, b.title as book_title, b.author, b.genre, bb.due_date')
            ->join('books b', 'b.id = bb.book_id', 'left')
            ->where('bb.user_id', $skUserId)
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        // ── All borrow requests ──
        $dashBorrowReqs = $db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.created_at, b.title as book_title, u.name as resident_name')
            ->join('books b', 'b.id = bb.book_id', 'left')
            ->join('users u', 'u.id = bb.user_id', 'left')
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        $pendingBorrowings = count(array_filter($dashBorrowReqs, fn($b) => ($b['status'] ?? '') === 'pending'));

        // ── Fairness quota ──
        $startOfMonth     = date('Y-m-01');
        $endOfMonth       = date('Y-m-t');
        $maxMonthlySlots  = 3;
        $usedThisMonth    = $model
            ->where('user_id', $skUserId)
            ->where('status', 'approved')
            ->where('reservation_date >=', $startOfMonth)
            ->where('reservation_date <=', $endOfMonth)
            ->countAllResults();
        $remainingReservations = max(0, $maxMonthlySlots - $usedThisMonth);

        return view('sk/dashboard', [
            'page'                  => 'dashboard',
            'user_name'             => session()->get('name') ?? session()->get('username'),
            'sk_name'               => session()->get('name') ?? session()->get('username'),
            'reservations'          => $myReservations,
            'allReservations'       => $allReservations,
            'pendingReservations'   => $pendingReservations,
            'total'                 => $total,
            'pending'               => $pending,
            'approved'              => $approved,
            'declined'              => $declined,
            'claimed'               => $claimed,
            'monthlyTotal'          => $monthlyTotal,
            'todayTotal'            => $todayTotal,
            'todayApproved'         => $todayApproved,
            'todayPending'          => $todayPending,
            'todayClaimed'          => $todayClaimed,
            'chartLabels'           => $chartLabels,
            'chartData'             => $chartData,
            'resourceLabels'        => $resourceLabels,
            'resourceData'          => $resourceData,
            'topResources'          => $topResources,
            'pendingUserCount'      => $pendingUserCount,
            'approvalRate'          => $approvalRate,
            'utilizationRate'       => $utilizationRate,
            'dashBooks'             => $allBooks,
            'featuredBooks'         => $allBooks,
            'availableCount'        => $availableCount,
            'totalBooks'            => $totalBooks,
            'myBorrowings'          => $myBorrowings,
            'dashBorrowReqs'        => $dashBorrowReqs,
            'pendingBorrowings'     => $pendingBorrowings,
            'remainingReservations' => $remainingReservations,
            'usedThisMonth'         => $usedThisMonth,
            'maxMonthlySlots'       => $maxMonthlySlots,
        ]);
    }

    public function reservations()
    {
        $model = new ReservationModel();
        $db    = db_connect();

        $status = $this->request->getGet('status');
        $date   = $this->request->getGet('date');

        $query = $model->db->table('reservations r')
            ->select('r.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email,
                approver.name AS approver_name, approver.email AS approver_email')
            ->join('resources', 'resources.id = r.resource_id',     'left')
            ->join('users',     'users.id = r.user_id',             'left')
            ->join('users approver', 'approver.id = r.approved_by', 'left')
            ->orderBy('r.reservation_date', 'DESC');

        if ($status) {
            if ($status === 'claimed') {
                $query->where('r.claimed', true);
            } else {
                $query->where('r.status', $status);
            }
        }

        if ($date) {
            $query->where('r.reservation_date', $date);
        }

        $reservations = $query->get()->getResultArray();
        $total        = count($reservations);

        $pendingCount  = $model->where('status', 'pending')->countAllResults();
        $approvedCount = $model->where('status', 'approved')->countAllResults();
        $claimedCount  = $model->where('claimed', true)->countAllResults();
        $declinedCount = $model->where('status', 'declined')->countAllResults();

        $rawPrintLogs = $db->table('print_logs')
            ->select('reservation_id, printed, pages, printed_at')
            ->orderBy('printed_at', 'DESC')
            ->get()
            ->getResultArray();

        $printLogMap = [];
        foreach ($rawPrintLogs as $pl) {
            $printLogMap[(int)$pl['reservation_id']] = $pl;
        }

        return view('sk/reservations', [
            'page'          => 'reservations',
            'reservations'  => $reservations,
            'printLogMap'   => $printLogMap,
            'total'         => $total,
            'pending'       => $pendingCount,
            'approved'      => $approvedCount,
            'claimed'       => $claimedCount,
            'declined'      => $declinedCount,
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'claimedCount'  => $claimedCount,
            'declinedCount' => $declinedCount,
            'currentStatus' => $status,
            'currentDate'   => $date,
        ]);
    }

    public function approve()
    {
        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $reservationModel = new ReservationModel();
        $db               = db_connect();

        try {
            $reservation = $reservationModel->find($id);
            if (!$reservation) {
                return redirect()->back()->with('error', 'Reservation not found');
            }

            // ── Use transStart/transComplete to match UserController pattern ──
            $db->transStart();
            $reservationModel->update($id, [
                'status'      => 'approved',
                'approved_by' => session()->get('user_id'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to approve reservation. Please try again.');
            }

            try {
                $db->table('activity_logs')->insert([
                    'user_id'        => session()->get('user_id'),
                    'action'         => 'approve_user_request',
                    'reservation_id' => $id,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to log approve activity: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Reservation approved successfully!');

        } catch (\Exception $e) {
            log_message('error', 'Approve failed: ' . $e->getMessage());
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
        $db               = db_connect();

        try {
            $reservation = $reservationModel->find($id);
            if (!$reservation) {
                return redirect()->back()->with('error', 'Reservation not found');
            }

            // ── Use transStart/transComplete to match UserController pattern ──
            $db->transStart();
            $reservationModel->update($id, [
                'status'      => 'declined',
                'approved_by' => session()->get('user_id'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to decline reservation. Please try again.');
            }

            try {
                $db->table('activity_logs')->insert([
                    'user_id'        => session()->get('user_id'),
                    'action'         => 'decline_user_request',
                    'reservation_id' => $id,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to log decline activity: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Reservation declined successfully!');

        } catch (\Exception $e) {
            log_message('error', 'Decline failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to decline reservation: ' . $e->getMessage());
        }
    }

    public function logPrint()
    {
        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }

        $reservationId = (int)$this->request->getPost('reservation_id');
        $printed       = ($this->request->getPost('printed') == '1');
        $pages         = (int)$this->request->getPost('pages');

        if (!$reservationId) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Missing reservation_id']);
        }

        $db  = db_connect();
        $res = $db->table('reservations')
            ->select('e_ticket_code, user_id')
            ->where('id', $reservationId)
            ->get()->getRowArray();

        if (!$res) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Reservation not found']);
        }

        $payload = [
            'printed'    => $printed,
            'pages'      => $pages,
            'printed_at' => date('Y-m-d H:i:s'),
            'printed_by' => session()->get('user_id'),
        ];

        try {
            $existing = $db->table('print_logs')
                ->where('reservation_id', $reservationId)
                ->get()->getRowArray();

            if ($existing) {
                $db->table('print_logs')
                    ->where('reservation_id', $reservationId)
                    ->update($payload);
            } else {
                $db->table('print_logs')->insert(array_merge($payload, [
                    'reservation_id' => $reservationId,
                    'user_id'        => $res['user_id']       ?? null,
                    'e_ticket_code'  => $res['e_ticket_code'] ?? null,
                ]));
            }
        } catch (\Exception $e) {
            log_message('error', 'SK logPrint — print_logs failed: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'error' => 'DB error: ' . $e->getMessage()]);
        }

        try {
            $db->table('activity_logs')->insert([
                'user_id'        => session()->get('user_id'),
                'action'         => 'print',
                'reservation_id' => $reservationId,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'SK logPrint — activity_logs failed: ' . $e->getMessage());
        }

        return $this->response->setJSON(['ok' => true]);
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
        $db               = db_connect();

        $visitorType     = $request->getPost('visitor_type');
        $userId          = (int) $request->getPost('user_id');
        $visitorName     = $request->getPost('visitor_name');
        $reservationDate = $request->getPost('reservation_date');
        $startTime       = $request->getPost('start_time');
        $endTime         = $request->getPost('end_time');
        $purpose         = $request->getPost('purpose');
        $resourceId      = $request->getPost('resource_id');

        $pcsJson = $request->getPost('pcs');
        $pcsArr  = json_decode($pcsJson, true) ?? [];
        $pcValue = !empty($pcsArr) ? implode(', ', $pcsArr) : null;

        if ($visitorType === 'User' && empty($userId)) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Please select a registered user.']);
            }
            return redirect()->back()->with('error', 'Please select a registered user.');
        }

        // ── Resolve user details from DB using integer user_id ───────────────
        $resolvedEmail = null;
        if ($visitorType === 'User' && $userId > 0) {
            $userRow       = $db->table('users')->select('email, name')->where('id', $userId)->get()->getRowArray();
            $resolvedEmail = $userRow['email'] ?? null;
            if (empty($visitorName) && !empty($userRow['name'])) {
                $visitorName = $userRow['name'];
            }
        }

        // ── Block check (mirrors UserController) ─────────────────────────────
        if ($visitorType === 'User' && $userId > 0) {
            $isBlocked = $reservationModel->isBlocked($userId);
            if ($isBlocked) {
                $msg = 'This user is currently blocked from making reservations until ' . date('F j, Y', strtotime($isBlocked['blocked_until']));
                if ($request->isAJAX()) {
                    return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        // ── Fairness / quota check — pass $userId (int), not email ───────────
        if ($visitorType === 'User' && $userId > 0) {
            $fairness = $reservationModel->checkFairness($userId);
            if (!$fairness['fair']) {
                $message = isset($fairness['blocked'])
                    ? 'AI Fairness Check: Exceeded limit. Blocked until ' . $fairness['until']
                    : 'AI Fairness Check: Exceeded limit. Blocked for 2 weeks.';
                if ($request->isAJAX()) {
                    return $this->response->setJSON(['status' => 'error', 'message' => $message]);
                }
                return redirect()->back()->with('error', $message);
            }
        }

        // ── Field validation (mirrors UserController) ─────────────────────────
        $rules = [
            'resource_id'      => 'required|numeric',
            'reservation_date' => 'required|valid_date[Y-m-d]',
            'start_time'       => 'required',
            'end_time'         => 'required',
            'purpose'          => 'required',
        ];

        if (!$this->validate($rules)) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Please fill all required fields.']);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill all required fields.');
        }

        // ── Reject past dates (mirrors UserController) ────────────────────────
        if ($reservationDate < date('Y-m-d')) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'You cannot make a reservation for a past date.']);
            }
            return redirect()->back()->withInput()->with('error', 'You cannot make a reservation for a past date.');
        }

        // ── Validate end time is after start time (mirrors UserController) ────
        if ($startTime >= $endTime) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'End time must be after start time.']);
            }
            return redirect()->back()->withInput()->with('error', 'End time must be after start time.');
        }

        // ── One reservation per day (mirrors UserController) ──────────────────
        // Only enforce for registered users; walk-in visitors may have multiple
        if ($visitorType === 'User' && $userId > 0) {
            $sameDayReservations = $reservationModel->getUserSameDayReservations($userId, $reservationDate);
            if (!empty($sameDayReservations)) {
                $msg = 'This user already has a reservation on this date. Only one reservation per day is allowed.';
                if ($request->isAJAX()) {
                    return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
                }
                return redirect()->back()->withInput()->with('error', $msg);
            }
        }

        // ── Double-booking guard with DB lock via transaction (mirrors UserController) ──
        $db->transStart();

        $conflict = $reservationModel
            ->where('resource_id', $resourceId)
            ->where('reservation_date', $reservationDate)
            ->whereIn('status', ['pending', 'approved'])
            ->groupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >',  $startTime)
            ->groupEnd()
            ->first();

        if ($conflict) {
            $db->transRollback();
            $msg = 'This time slot is already booked. Please choose another time.';
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // ── Insert ────────────────────────────────────────────────────────────
        $eTicket = $this->generateETicket();

        $data = [
            'user_id'          => ($visitorType === 'User' && $userId > 0) ? $userId : null,
            'visitor_type'     => $visitorType,
            'visitor_name'     => $visitorName,
            'user_email'       => $resolvedEmail,
            'resource_id'      => !empty($resourceId) ? (int)$resourceId : null,
            'reservation_date' => $reservationDate,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'purpose'          => $purpose,
            'pc_number'        => $pcValue,
            'status'           => 'pending',
            'claimed'          => false,
            'e_ticket_code'    => $eTicket,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $inserted = $reservationModel->insert($data);
        $db->transComplete();

        if ($db->transStatus() === false || !$inserted) {
            if ($request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save reservation. Please try again.']);
            }
            return redirect()->back()->withInput()->with('error', 'Failed to save reservation. Please try again.');
        }

        if ($request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'Reservation created successfully!',
                'e_ticket' => $eTicket,
            ]);
        }

        return redirect()->to('/sk/reservations')->with('success', 'Reservation created successfully!');
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
        return view('sk/profile', ['user' => $user, 'page' => 'profile']);
    }

    public function deleteProfile()
    {
        $userId = session()->get('user_id');

        $userModel = new \App\Models\UserModel();
        $userModel->delete($userId);

        session()->destroy();

        return redirect()->to('/login')->with('success', 'Account deleted successfully.');
    }

    public function scanner()
    {
        $reservationModel = new ReservationModel();
        $allReservations  = $reservationModel
            ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users',     'users.id = reservations.user_id',         'left')
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();

        return view('sk/scanner', [
            'page'            => 'scanner',
            'allReservations' => $allReservations,
        ]);
    }

    public function validateETicket()
    {
        $code = $this->request->getPost('code');
        if (!$code) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No code provided']);
        }

        $reservationModel = new ReservationModel();

        $reservation = $reservationModel
            ->where('e_ticket_code', $code)
            ->where('status', 'approved')
            ->where('claimed', false)
            ->first();

        if ($reservation) {
            $reservationModel->update($reservation['id'], [
                'claimed'    => true,
                'claimed_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'E-Ticket validated! Reservation claimed.',
                'updated' => true,
            ]);
        }

        if ($reservationModel->where('e_ticket_code', $code)->where('claimed', true)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ticket already claimed.']);
        }

        if ($reservationModel->where('e_ticket_code', $code)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Reservation not yet approved.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid E-Ticket code.']);
    }

    public function updateProfile()
    {
        $userModel = new UserModel();
        $userId    = session()->get('user_id');
        $user      = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        $password = $this->request->getPost('password');
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
                $r['id'],               $r['user_id'],
                $r['resource_id'],      $r['reservation_date'],
                $r['start_time'],       $r['end_time'],
                $r['purpose'],          $r['status'],
                $r['claimed'] ? 'Yes' : 'No',
                $r['claimed_at'] ?? '',
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

        $allowedStatuses = ['pending', 'approved', 'rejected'];

        if ($status && in_array($status, $allowedStatuses)) {
            $model->where('status', $status);
        }

        if ($date) {
            $model->where('reservation_date', $date);
        }

        $reservations = $model
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
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

        return view('sk/activity-logs', ['page' => 'activity-logs', 'logs' => $logs]);
    }

    public function userRequests()
    {
        $reservationModel = new ReservationModel();
        $userReservations = $this->getUserReservationsForApproval();

        $pendingUserCount = $reservationModel
            ->where('status', 'pending')
            ->where('user_id !=', session()->get('user_id'))
            ->countAllResults();

        return view('sk/user_requests', [
            'page'             => 'user-requests',
            'userReservations' => $userReservations,
            'pendingUserCount' => $pendingUserCount,
        ]);
    }

    private function getUserReservationsForApproval()
    {
        $db       = db_connect();
        $skUserId = session()->get('user_id');

        return $db->table('reservations r')
            ->select('r.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email, users.phone as visitor_phone')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users',     'users.id = r.user_id',         'left')
            ->where('r.user_id IS NOT NULL')
            ->where('r.user_id !=', $skUserId)
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function checkNewUserRequests()
    {
        if (!$this->request->isAJAX()) return;

        $lastCheck = $this->request->getPost('last_check') ?: date('Y-m-d H:i:s', strtotime('-1 hour'));
        $skUserId  = session()->get('user_id');
        $db        = db_connect();

        $newRequests = $db->table('reservations r')
            ->select('r.*, resources.name as resource_name, users.name as visitor_name')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users',     'users.id = r.user_id',         'left')
            ->where('r.status', 'pending')
            ->where('r.user_id !=', $skUserId)
            ->where('r.created_at >', $lastCheck)
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['new_requests' => $newRequests]);
    }

    public function getPendingCount()
    {
        if (!$this->request->isAJAX()) return;

        $skUserId     = session()->get('user_id');
        $pendingCount = db_connect()->table('reservations')
            ->where('status', 'pending')
            ->where('user_id !=', $skUserId)
            ->countAllResults();

        return $this->response->setJSON(['pending_count' => $pendingCount]);
    }

    public function claimedReservations()
    {
        $reservationModel    = new ReservationModel();
        $claimedReservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users',     'users.id = reservations.user_id',         'left')
            ->where('reservations.claimed', true)
            ->orderBy('reservations.claimed_at', 'DESC')
            ->findAll();

        $totalClaimed = count($claimedReservations);
        $todayCount   = count(array_filter($claimedReservations, function ($r) {
            return $r['claimed_at'] && date('Y-m-d', strtotime($r['claimed_at'])) === date('Y-m-d');
        }));

        return view('sk/claimed_reservations', [
            'page'                => 'claimed-reservations',
            'claimedReservations' => $claimedReservations,
            'totalClaimed'        => $totalClaimed,
            'todayCount'          => $todayCount,
        ]);
    }

    public function exportClaimedToExcel()
    {
        $reservationModel    = new ReservationModel();
        $claimedReservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users',     'users.id = reservations.user_id',         'left')
            ->where('reservations.claimed', true)
            ->orderBy('reservations.claimed_at', 'DESC')
            ->findAll();

        $filename = 'claimed_reservations_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, [
            'ID', 'Reservation Code', 'Visitor Name', 'Email', 'Resource',
            'Workstation', 'Reservation Date', 'Start Time', 'End Time',
            'Purpose', 'Status', 'Claimed At', 'Claimed Date', 'Claimed Time',
        ]);

        foreach ($claimedReservations as $res) {
            $pcNumbers = '';
            if (!empty($res['pc_numbers'])) {
                $arr       = json_decode($res['pc_numbers'], true);
                $pcNumbers = is_array($arr) ? implode(', ', $arr) : $res['pc_numbers'];
            } elseif (!empty($res['pc_number'])) {
                $pcNumbers = $res['pc_number'];
            }

            $claimedDate = $claimedTime = '';
            if (!empty($res['claimed_at'])) {
                $claimedDate = date('Y-m-d', strtotime($res['claimed_at']));
                $claimedTime = date('H:i:s', strtotime($res['claimed_at']));
            }

            fputcsv($output, [
                $res['id'],
                $res['e_ticket_code']    ?? '—',
                $res['visitor_name']     ?? $res['full_name'] ?? 'Guest',
                $res['visitor_email']    ?? $res['user_email'] ?? '—',
                $res['resource_name']    ?? ('Resource #' . $res['resource_id']),
                $pcNumbers               ?: '—',
                $res['reservation_date'] ?? '—',
                $res['start_time']       ?? '—',
                $res['end_time']         ?? '—',
                $res['purpose']          ?? '—',
                ucfirst($res['status']   ?? 'pending'),
                $res['claimed_at']       ?? '—',
                $claimedDate,
                $claimedTime,
            ]);
        }

        fclose($output);
        exit;
    }

    public function checkNewReservations()
    {
        if (!$this->request->isAJAX()) return;

        $lastCheck = $this->request->getPost('last_check') ?: date('Y-m-d H:i:s', strtotime('-1 hour'));
        $skUserId  = session()->get('user_id');
        $db        = db_connect();

        $newReservations = $db->table('reservations r')
            ->select('r.*, resources.name as resource_name, users.name as visitor_name')
            ->join('resources', 'resources.id = r.resource_id', 'left')
            ->join('users',     'users.id = r.user_id',         'left')
            ->where('r.status', 'pending')
            ->where('r.user_id !=', $skUserId)
            ->where('r.created_at >', $lastCheck)
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['new_reservations' => $newReservations]);
    }

    public function extractPdfWithAI()
    {
        try {
            return $this->_doExtractPdfWithAI();
        } catch (\Throwable $e) {
            log_message('error', 'extractPdfWithAI crashed: ' . $e->getMessage()
                . ' in ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'PHP error: ' . $e->getMessage()
                         . ' (line ' . $e->getLine()
                         . ' in ' . basename($e->getFile()) . ')',
            ]);
        }
    }

    private function _doExtractPdfWithAI(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['ok' => false, 'error' => 'Invalid request.']);
        }

        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized. Please log in again.']);
        }

        $json      = $this->request->getJSON(true) ?? [];
        $pdfBase64 = $json['pdf_base64'] ?? null;

        if (!$pdfBase64) {
            return $this->response->setJSON(['ok' => false, 'error' => 'No PDF data received.']);
        }

        if (strlen($pdfBase64) > 14000000) {
            return $this->response->setJSON(['ok' => false, 'error' => 'PDF is too large. Maximum is 10MB.']);
        }

        if (!function_exists('curl_init')) {
            return $this->response->setJSON(['ok' => false, 'error' => 'cURL is not enabled on this server.']);
        }

        $apiKey = env('GROQ_API_KEY')
               ?: getenv('GROQ_API_KEY')
               ?: ($_ENV['GROQ_API_KEY']    ?? null)
               ?: ($_SERVER['GROQ_API_KEY'] ?? null);

        if (!$apiKey) {
            return $this->response->setJSON(['ok' => false, 'error' => 'GROQ_API_KEY is not set in your .env file.']);
        }

        $pdfBinary = base64_decode($pdfBase64, true);
        if ($pdfBinary === false || strlen($pdfBinary) < 10) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Invalid base64 PDF data.']);
        }

        $tmpDir  = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        $tmpPath = $tmpDir . 'sk_pdf_' . uniqid('', true) . '.pdf';

        if (@file_put_contents($tmpPath, $pdfBinary) === false) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Could not write temp file to: ' . $tmpDir]);
        }

        $method  = 'unknown';
        $pdfText = $this->extractTextFromPdf($tmpPath, $method);

        if (file_exists($tmpPath)) {
            @unlink($tmpPath);
        }

        if (strlen(trim($pdfText)) < 20) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'Could not extract readable text from this PDF (method: ' . $method . '). '
                         . 'The file may be a scanned image or encrypted.',
            ]);
        }

        $pdfText = mb_substr(trim($pdfText), 0, 8000);

        $prompt = 'You are a book cataloging assistant. Extract bibliographic information from this PDF text.

Return ONLY a raw JSON object — no markdown, no backticks, no explanation before or after. Exactly these keys:
{"title":"","author":"","genre":"","published_year":"","isbn":"","preface":""}

PDF TEXT:
' . $pdfText;

        $payload = json_encode([
            'model'       => 'llama-3.3-70b-versatile',
            'max_tokens'  => 800,
            'temperature' => 0,
            'messages'    => [['role' => 'user', 'content' => $prompt]],
        ]);

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Network error reaching Groq: ' . $curlError]);
        }

        if (empty($response)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq returned an empty response.']);
        }

        $groqData = json_decode($response, true);

        if ($httpStatus !== 200) {
            $msg = $groqData['error']['message'] ?? ('Groq HTTP ' . $httpStatus);
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq error: ' . $msg]);
        }

        $rawText = $groqData['choices'][0]['message']['content'] ?? '';

        if (empty(trim($rawText))) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Groq returned empty content.']);
        }

        $clean = trim(preg_replace('/^```(?:json)?\s*/m', '', $rawText));
        $clean = trim(preg_replace('/```\s*$/m', '', $clean));

        if (preg_match('/\{[\s\S]*\}/m', $clean, $m)) {
            $clean = $m[0];
        }

        $extracted = json_decode($clean, true);

        if (!is_array($extracted)) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'AI response was not valid JSON. Got: ' . mb_substr($rawText, 0, 150),
            ]);
        }

        foreach (['title', 'author', 'genre', 'published_year', 'isbn', 'preface'] as $key) {
            if (!isset($extracted[$key])) $extracted[$key] = '';
        }

        return $this->response->setJSON(['ok' => true, 'data' => $extracted]);
    }

    private function extractTextFromPdf(string $filePath, string &$method = ''): string
    {
        $shellDisabled = array_map('trim', explode(',', ini_get('disable_functions')));
        if (function_exists('shell_exec') && !in_array('shell_exec', $shellDisabled)) {
            $which = trim((string) @shell_exec('which pdftotext 2>/dev/null'));
            if (!empty($which)) {
                $escaped = escapeshellarg($filePath);
                $out     = (string) @shell_exec("pdftotext -layout -enc UTF-8 {$escaped} - 2>/dev/null");
                if (strlen(trim($out)) > 20) {
                    $method = 'pdftotext';
                    return $out;
                }
            }
        }

        $method  = 'php-zlib';
        $content = @file_get_contents($filePath);
        if ($content === false || strlen($content) < 10) {
            $method = 'unreadable';
            return '';
        }

        $text = '';
        preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $content, $streamMatches);
        foreach ($streamMatches[1] as $streamData) {
            $decompressed = $this->tryDecompress($streamData);
            $streamText   = $this->parseTextFromPdfStream($decompressed);
            if (strlen(trim($streamText)) > 0) {
                $text .= $streamText . "\n";
            }
        }

        if (strlen(trim($text)) < 50) {
            $method = 'php-ascii-fallback';
            $text   = $this->extractAsciiFromPdf($content);
        }

        $text = preg_replace('/[ \t]{2,}/', ' ', $text ?? '');
        $text = preg_replace('/(\r\n|\r|\n){3,}/', "\n\n", $text);

        return trim($text ?? '');
    }

    private function tryDecompress(string $data): string
    {
        if (strlen($data) >= 2) {
            $result = @gzuncompress($data);
            if ($result !== false && strlen($result) > 0) return $result;
        }
        if (strlen($data) >= 2) {
            $result = @gzinflate($data);
            if ($result !== false && strlen($result) > 0) return $result;
        }
        if (strlen($data) >= 10 && substr($data, 0, 2) === "\x1f\x8b") {
            $result = @gzdecode($data);
            if ($result !== false && strlen($result) > 0) return $result;
        }
        if (strlen($data) > 2) {
            $result = @gzinflate(substr($data, 2));
            if ($result !== false && strlen($result) > 0) return $result;
        }
        return $data;
    }

    private function parseTextFromPdfStream(string $stream): string
    {
        $text = '';
        preg_match_all('/BT\s*(.*?)\s*ET/s', $stream, $btBlocks);
        foreach ($btBlocks[1] as $block) {
            preg_match_all('/\(([^)\\\\]*(?:\\\\.[^)\\\\]*)*)\)\s*T[jJ]/m', $block, $tjM);
            foreach ($tjM[1] as $s) {
                $s = $this->decodePdfString($s);
                if (strlen(trim($s)) > 0) $text .= $s . ' ';
            }
            preg_match_all('/\[((?:[^[\]]*|\[.*?\])*)\]\s*TJ/s', $block, $arrM);
            foreach ($arrM[1] as $arr) {
                preg_match_all('/\(([^)\\\\]*(?:\\\\.[^)\\\\]*)*)\)/', $arr, $sub);
                foreach ($sub[1] as $s) {
                    $s = $this->decodePdfString($s);
                    if (strlen(trim($s)) > 0) $text .= $s;
                }
                $text .= ' ';
            }
            preg_match_all('/<([0-9a-fA-F\s]+)>\s*T[jJ]/m', $block, $hexM);
            foreach ($hexM[1] as $hex) {
                $hex = preg_replace('/\s+/', '', $hex);
                if (strlen($hex) % 2 !== 0) continue;
                $decoded = @hex2bin($hex);
                if ($decoded !== false) {
                    if (substr($decoded, 0, 2) === "\xFE\xFF") {
                        $decoded = mb_convert_encoding(substr($decoded, 2), 'UTF-8', 'UTF-16BE');
                    }
                    $readable = preg_replace('/[^\x20-\x7E\xA0-\xFF]/', ' ', $decoded);
                    if (strlen(trim($readable)) > 1) $text .= $readable . ' ';
                }
            }
            $text = rtrim($text) . "\n";
        }
        return $text;
    }

    private function decodePdfString(string $s): string
    {
        $s = str_replace(
            ['\\n', '\\r', '\\t', '\\b', '\\f', '\\(', '\\)', '\\\\'],
            ["\n",  "\r",  "\t",  "\x08", "\x0C", '(',  ')',   '\\'],
            $s
        );
        $s = preg_replace_callback('/\\\\([0-7]{1,3})/', function ($m) {
            return chr(octdec($m[1]));
        }, $s);
        if (substr($s, 0, 2) === "\xFE\xFF") {
            $s = mb_convert_encoding(substr($s, 2), 'UTF-8', 'UTF-16BE');
        }
        $s = preg_replace('/[^\x20-\x7E\xA0-\xFF\n\r\t]/', ' ', $s);
        return trim($s);
    }

    private function extractAsciiFromPdf(string $content): string
    {
        preg_match_all('/[\x20-\x7E]{4,}/', $content, $runs);
        $filtered = array_filter($runs[0] ?? [], function ($s) {
            $wordChars  = preg_match_all('/[a-zA-Z0-9\s]/', $s);
            $totalChars = strlen($s);
            return $totalChars > 0 && ($wordChars / $totalChars) > 0.5;
        });
        $text = implode(' ', $filtered);
        $text = preg_replace(
            '/\b(?:obj|endobj|stream|endstream|xref|trailer|startxref|BT|ET|Tf|Td|TD|Tm|TJ|Tj|TL|TS|Tc|Tw|Tz|Tr|PDF|Linearized|FlateDecode|DCTDecode|Length|Width|Height|BBox|Matrix|Filter|Subtype|Type|Font|Page|Pages|Resources|MediaBox|CropBox|Rotate|Annot|Annots|Action|URI|Link|Rect|Border)\b/',
            ' ',
            $text
        );
        return $text;
    }
}