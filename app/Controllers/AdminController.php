<?php
namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\UserModel;
use App\Models\AccountModel;
use App\Models\LoginLog;
use App\Models\ActivityLog;
use App\Models\ResourceModel;
use App\Models\PcModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
    use \App\Controllers\Traits\StopSessionTrait;

    protected $reservationModel;
    protected $db;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    private function logActivity(string $action, ?int $reservationId = null, ?string $details = null): void
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            log_message('error', 'Attempted to log activity without user session');
            return;
        }

        try {
            $this->db->table('activity_logs')->insert([
                'user_id'        => $userId,
                'action'         => $action,
                'reservation_id' => $reservationId,
                'details'        => $details,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to log activity: ' . $e->getMessage());
        }
    }

    private function getValidUserId()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $userExists = $this->db->table('users')
                ->where('id', $userId)
                ->whereIn('status', ['approved', 'pending'])
                ->countAllResults();

            if ($userExists) return $userId;
        }

        $email = session()->get('email');
        if ($email) {
            $user = $this->db->table('users')
                ->where('email', $email)
                ->whereIn('status', ['approved', 'pending'])
                ->get()->getRowArray();

            if ($user) {
                session()->set('user_id', $user['id']);
                session()->set('name', $user['name']);
                session()->set('email', $user['email']);
                session()->set('role', $user['role']);
                return $user['id'];
            }
        }

        $chairman = $this->db->table('users')
            ->where('role', 'chairman')
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('id', 'ASC')
            ->get()->getRowArray();

        if ($chairman) {
            session()->set('user_id', $chairman['id']);
            session()->set('name', $chairman['name']);
            session()->set('email', $chairman['email']);
            session()->set('role', $chairman['role']);
            session()->set('isLoggedIn', true);
            return $chairman['id'];
        }

        $anyUser = $this->db->table('users')
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('id', 'ASC')
            ->get()->getRowArray();

        if ($anyUser) {
            session()->set('user_id', $anyUser['id']);
            session()->set('name', $anyUser['name']);
            session()->set('email', $anyUser['email']);
            session()->set('role', $anyUser['role'] ?? 'user');
            session()->set('isLoggedIn', true);
            return $anyUser['id'];
        }

        return null;
    }

    public function manageReservations()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $db = $this->db;

        // FIX: Use COALESCE so walk-in visitor_name is not overwritten by the
        // NULL result of the left-join when user_id is NULL (walk-in guests).
        $reservations = $db->table('reservations r')
            ->select('r.*,
                      res.name AS resource_name,
                      COALESCE(u.name,  r.visitor_name) AS visitor_name,
                      COALESCE(u.email, r.user_email)   AS visitor_email,
                      approver.name  AS approver_name,
                      approver.email AS approver_email')
            ->join('resources res',  'res.id = r.resource_id',     'left')
            ->join('users u',        'u.id = r.user_id',           'left')
            ->join('users approver', 'approver.id = r.approved_by', 'left')
            ->orderBy('r.id', 'DESC')
            ->get()->getResultArray();

        $printLogMap = [];
        foreach ($db->table('print_logs')->get()->getResultArray() as $pl) {
            $printLogMap[(int)$pl['reservation_id']] = $pl;
        }

        $walkInQuotaMap = [];
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
        $rows = $db->query("
            SELECT LOWER(TRIM(visitor_name)) AS name_key,
                   COUNT(*) AS used,
                   MIN(reservation_date) AS oldest_date
            FROM reservations
            WHERE user_id IS NULL
              AND status NOT IN ('declined','canceled')
              AND reservation_date >= ?
            GROUP BY LOWER(TRIM(visitor_name))
        ", [$threeDaysAgo])->getResultArray();

        foreach ($rows as $row) {
            $used  = (int)$row['used'];
            $reset = $row['oldest_date']
                ? date('F j, Y', strtotime($row['oldest_date'] . ' +4 days'))
                : null;
            $walkInQuotaMap[$row['name_key']] = [
                'used'      => $used,
                'remaining' => max(0, 3 - $used),
                'fair'      => $used < 3,
                'reset'     => $reset,
            ];
        }

        return view('admin/manage-reservations', [
            'page'           => 'manage-reservations',
            'reservations'   => $reservations,
            'printLogMap'    => $printLogMap,
            'walkInQuotaMap' => $walkInQuotaMap,
        ]);
    }

    public function newReservation()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $resources = $this->db->table('resources')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $pcs = $this->db->table('pcs')
            ->orderBy('pc_number', 'ASC')
            ->get()->getResultArray();

        $users = $this->db->table('users')
            ->whereIn('status', ['approved', 'pending'])
            ->where('role', 'user')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        return view('admin/new-reservation', [
            'page'      => 'new-reservation',
            'resources' => $resources,
            'pcs'       => $pcs,
            'users'     => $users,
        ]);
    }

    private function sendSKDecisionEmail(string $to, string $name, string $decision): void
    {
        $apiKey = env('BREVO_API_KEY', '');

        if (empty($apiKey)) {
            log_message('error', '[AdminController] BREVO_API_KEY is not set');
            return;
        }

        $subject = $decision === 'approved'
            ? 'Your SK Account Has Been Approved'
            : 'Update on Your SK Account Application';

        $body = view('emails/sk_decision', [
            'name'     => $name,
            'decision' => $decision,
            'loginUrl' => base_url('login'),
        ]);

        $payload = json_encode([
            'sender'      => ['name' => env('EMAIL_FROM_NAME', 'E-Learning System'), 'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph')],
            'to'          => [['email' => $to, 'name' => $name]],
            'subject'     => $subject,
            'htmlContent' => $body,
        ]);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'api-key: ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus !== 201) {
            log_message('error', '[AdminController] SK decision email failed: HTTP ' . $httpStatus . ' | ' . $response);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  DASHBOARD
    // ═══════════════════════════════════════════════════════════════════════
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $model = $this->reservationModel;
        $db    = $this->db;

        $reservations = $db->table('reservations r')
            ->select('r.*, res.name AS resource_name, u.name as visitor_name, u.email as user_email')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->join('users u', 'u.id = r.user_id', 'left')
            ->orderBy('r.id', 'DESC')
            ->get()->getResultArray();

        $total    = $model->countAll();
        $pending  = $model->where('status', 'pending')->countAllResults();
        $approved = $model->where('status', 'approved')->countAllResults();
        $declined = $model->where('status', 'declined')->countAllResults();
        $claimed  = $model->where('claimed', true)->countAllResults();

        $today         = date('Y-m-d');
        $todayTotal    = $model->where('reservation_date', $today)->countAllResults();
        $todayApproved = $model->where('reservation_date', $today)->where('status', 'approved')->countAllResults();
        $todayPending  = $model->where('reservation_date', $today)->where('status', 'pending')->countAllResults();
        $todayClaimed  = $model->where('reservation_date', $today)->where('claimed', true)->countAllResults();

        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $monthlyTotal  = $model->where('reservation_date >=', $thirtyDaysAgo)->countAllResults();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            $chartData[]   = $model->where('reservation_date', $date)->countAllResults();
        }

        $resourceQuery = $db->table('reservations r')
            ->select('resources.name, COUNT(*) as count')
            ->join('resources', 'resources.id = r.resource_id')
            ->where('r.status', 'approved')
            ->groupBy('r.resource_id, resources.name')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

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

        $approvalRate    = $total    > 0 ? round(($approved / $total)    * 100) : 0;
        $utilizationRate = $approved > 0 ? round(($claimed / $approved) * 100) : 0;

        $totalUsers     = $db->table('users')->where('role', 'user')->countAllResults();
        $totalResources = $db->table('resources')->countAllResults();

        $pendingSKCount = $db->table('users')
            ->where('role', 'sk')
            ->where('is_approved', false)
            ->where('is_verified', true)
            ->countAllResults();

        $allBooks = $db->table('books')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $bookTotalCount = count($allBooks);
        $bookAvailCount = count(array_filter($allBooks, fn($b) => (int)($b['available_copies'] ?? 0) > 0));

        $dashBorrowReqs = $db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.created_at, b.title as book_title, u.name as resident_name')
            ->join('books b', 'b.id = bb.book_id', 'left')
            ->join('users u', 'u.id = bb.user_id',  'left')
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        $pendingBorrowings = count(array_filter($dashBorrowReqs, fn($b) => ($b['status'] ?? '') === 'pending'));

        $printLogs = $db->table('print_logs pl')
            ->select('pl.id, pl.reservation_id, pl.printed, pl.pages,
                      pl.printed_at, pl.e_ticket_code,
                      COALESCE(u.name, r.visitor_name, \'Guest\') AS visitor_name,
                      res.name AS resource_name,
                      r.reservation_date, r.start_time')
            ->join('reservations r',  'r.id = pl.reservation_id', 'left')
            ->join('resources res',   'res.id = r.resource_id',   'left')
            ->join('users u',         'u.id = r.user_id',         'left')
            ->orderBy('pl.printed_at', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        return view('admin/dashboard', [
            'page'             => 'dashboard',
            'total'            => $total,
            'pending'          => $pending,
            'approved'         => $approved,
            'declined'         => $declined,
            'claimed'          => $claimed,
            'monthlyTotal'     => $monthlyTotal,
            'todayTotal'       => $todayTotal,
            'todayApproved'    => $todayApproved,
            'todayPending'     => $todayPending,
            'todayClaimed'     => $todayClaimed,
            'chartLabels'      => $chartLabels,
            'chartData'        => $chartData,
            'resourceLabels'   => $resourceLabels,
            'resourceData'     => $resourceData,
            'topResources'     => $topResources,
            'approvalRate'     => $approvalRate,
            'utilizationRate'  => $utilizationRate,
            'totalUsers'       => $totalUsers,
            'totalResources'   => $totalResources,
            'reservations'     => $reservations,
            'pendingSKCount'   => $pendingSKCount,
            'dashBooks'        => $allBooks,
            'dashBorrowReqs'   => $dashBorrowReqs,
            'bookTotalCount'   => $bookTotalCount,
            'bookAvailCount'   => $bookAvailCount,
            'pendingBorrowings'=> $pendingBorrowings,
            'printLogs'        => $printLogs,
        ]);
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

        $res = $this->db->table('reservations')
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
            $existing = $this->db->table('print_logs')
                ->where('reservation_id', $reservationId)
                ->get()->getRowArray();

            if ($existing) {
                $this->db->table('print_logs')
                    ->where('reservation_id', $reservationId)
                    ->update($payload);
            } else {
                $this->db->table('print_logs')->insert(array_merge($payload, [
                    'reservation_id' => $reservationId,
                    'user_id'        => $res['user_id']       ?? null,
                    'e_ticket_code'  => $res['e_ticket_code'] ?? null,
                ]));
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin logPrint — print_logs failed: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'error' => 'DB error: ' . $e->getMessage()]);
        }

        try {
            $this->db->table('activity_logs')->insert([
                'user_id'        => session()->get('user_id'),
                'action'         => 'print',
                'reservation_id' => $reservationId,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Admin logPrint — activity_logs failed: ' . $e->getMessage());
        }

        return $this->response->setJSON(['ok' => true]);
    }

    public function manageSK()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userModel = new UserModel();

        $allSK = $userModel
            ->select('users.*, accounts.is_verified AS email_verified')
            ->join('accounts', 'accounts.user_id = users.id', 'left')
            ->where('users.role', 'sk')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        $pending  = array_values(array_filter($allSK, fn($u) => ($u['status'] ?? '') === 'pending'));
        $approved = array_values(array_filter($allSK, fn($u) => ($u['status'] ?? '') === 'approved'));
        $rejected = array_values(array_filter($allSK, fn($u) => ($u['status'] ?? '') === 'rejected'));

        return view('admin/manage-sk', [
            'page'          => 'manage-sk',
            'skAccounts'    => $allSK,
            'pending'       => $pending,
            'approved'      => $approved,
            'rejected'      => $rejected,
            'pendingCount'  => count($pending),
            'approvedCount' => count($approved),
            'rejectedCount' => count($rejected),
        ]);
    }

    public function managePCs()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $pcModel          = new PcModel();
        $pcs              = $pcModel->orderBy('pc_number', 'ASC')->findAll();
        $availableCount   = $pcModel->where('status', 'available')->countAllResults();
        $inUseCount       = $pcModel->where('status', 'out_of_order')->countAllResults();
        $maintenanceCount = $pcModel->where('status', 'maintenance')->countAllResults();

        return view('admin/manage-pcs', [
            'page'             => 'manage-pcs',
            'pcs'              => $pcs,
            'availableCount'   => $availableCount,
            'inUseCount'       => $inUseCount,
            'maintenanceCount' => $maintenanceCount,
        ]);
    }

    public function addPC()
    {
        $pcNumber = $this->request->getPost('pc_number');
        $status   = $this->request->getPost('status') ?? 'available';

        if (empty($pcNumber)) {
            return redirect()->back()->with('error', 'PC number is required');
        }

        $existingPC = $this->db->table('pcs')->where('pc_number', $pcNumber)->get()->getRow();
        if ($existingPC) {
            return redirect()->back()->with('error', 'PC number already exists');
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            $anyUser = $this->db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRow();
            if ($anyUser) {
                $userId = $anyUser->id;
                session()->set('user_id', $userId);
            } else {
                return redirect()->back()->with('error', 'No users found in system');
            }
        }

        try {
            $this->db->table('pcs')->insert([
                'pc_number'  => $pcNumber,
                'status'     => $status,
                'added_by'   => (int) $userId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $this->logActivity('add_pc', null, "Added PC: $pcNumber");
            return redirect()->to('/admin/manage-pcs')->with('success', 'PC added successfully.');
        } catch (\Exception $e) {
            log_message('error', 'PC insert error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    public function updatePCStatus()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!$id || !$status) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $pcModel = new PcModel();

        try {
            $pc = $pcModel->find($id);
            if (!$pc) {
                return redirect()->back()->with('error', 'PC not found.');
            }

            $pcModel->update($id, ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
            $this->logActivity('update_pc_status', null, "Updated PC {$pc['pc_number']} status to: $status");
            return redirect()->to('/admin/manage-pcs')->with('success', 'PC status updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error updating PC status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update PC status. Please try again.');
        }
    }

    public function deletePC($id)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        if (!is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid PC ID.');
        }

        $pcModel = new PcModel();

        try {
            $pc = $pcModel->find($id);
            if (!$pc) {
                return redirect()->back()->with('error', 'PC not found.');
            }

            $activeReservations = $this->db->table('reservations')
                ->where('pc_number', $pc['pc_number'])
                ->whereIn('status', ['pending', 'approved'])
                ->countAllResults();

            if ($activeReservations > 0) {
                return redirect()->back()->with('error', 'Cannot delete PC with active or pending reservations.');
            }

            $pcModel->delete($id);
            $this->logActivity('delete_pc', null, "Deleted PC: {$pc['pc_number']}");
            return redirect()->to('/admin/manage-pcs')->with('success', 'PC deleted successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting PC: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete PC. Please try again.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  CHECK AVAILABILITY (AJAX)
    // ═══════════════════════════════════════════════════════════════════════
    public function checkAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $resourceId = (int) $this->request->getGet('resource_id');
        $date       = $this->request->getGet('date');
        $startTime  = $this->request->getGet('start_time');
        $endTime    = $this->request->getGet('end_time');
        $excludeId  = (int) $this->request->getGet('exclude_id');
        $pcNumber   = trim($this->request->getGet('pc_number') ?? '');

        if (!$resourceId || !$date) {
            return $this->response->setJSON([
                'available'    => true,
                'booked_slots' => [],
                'has_conflict' => false,
            ]);
        }

        $query = $this->db->table('reservations')
            ->select('start_time, end_time, status, pc_number')
            ->where('resource_id', $resourceId)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'approved']);

        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }

        $bookedSlots = $query->get()->getResultArray();

        $hasConflict = false;
        if ($startTime && $endTime) {
            $pcsRequested = !empty($pcNumber)
                ? array_map('trim', explode(',', $pcNumber))
                : [];

            foreach ($bookedSlots as $slot) {
                if ($startTime >= $slot['end_time'] || $endTime <= $slot['start_time']) {
                    continue;
                }

                if (!empty($pcsRequested) && !empty($slot['pc_number'])) {
                    $slotPcs = array_map('trim', explode(',', $slot['pc_number']));
                    if (empty(array_intersect($pcsRequested, $slotPcs))) {
                        continue;
                    }
                }

                $hasConflict = true;
                break;
            }
        }

        return $this->response->setJSON([
            'available'    => !$hasConflict,
            'booked_slots' => $bookedSlots,
            'has_conflict' => $hasConflict,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  CREATE RESERVATION
    // ═══════════════════════════════════════════════════════════════════════
    public function createReservation()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $request          = $this->request;
        $reservationModel = $this->reservationModel;
        $db               = $this->db;

        $visitorType     = $request->getPost('visitor_type');
        $userId          = (int) $request->getPost('user_id');
        $visitorName     = trim($request->getPost('visitor_name') ?? '');
        $reservationDate = $request->getPost('reservation_date');
        $startTime       = $request->getPost('start_time');
        $endTime         = $request->getPost('end_time');
        $resourceId      = $request->getPost('resource_id');

        $pcsJson = $request->getPost('pcs');
        $pcsArr  = json_decode($pcsJson, true) ?? [];
        $pcValue = !empty($pcsArr) ? implode(', ', $pcsArr) : ($request->getPost('pc_number') ?: null);

        if ($visitorType === 'User' && empty($userId)) {
            return redirect()->back()->with('error', 'Please select a registered user from the list.');
        }

        $resolvedEmail = null;
        if ($visitorType === 'User' && $userId > 0) {
            $userRow       = $db->table('users')->select('email, name')->where('id', $userId)->get()->getRowArray();
            $resolvedEmail = $userRow['email'] ?? null;
            if (empty($visitorName) && !empty($userRow['name'])) {
                $visitorName = $userRow['name'];
            }
        }

        $selectedResource = !empty($resourceId)
            ? $db->table('resources')->where('id', $resourceId)->get()->getRowArray()
            : null;
        $isWifi = $selectedResource && stripos($selectedResource['name'], 'wifi') !== false;

        if (!$isWifi && $visitorType === 'User' && $userId > 0) {
            $isBlocked = $reservationModel->isBlocked($userId);
            if ($isBlocked) {
                return redirect()->back()->with('error',
                    'This user is currently blocked from making reservations until ' .
                    date('F j, Y', strtotime($isBlocked['blocked_until']))
                );
            }
        }

        if (!$isWifi && $visitorType === 'User' && $userId > 0) {
            $fairness = $reservationModel->checkFairness($userId);
            if (!$fairness['fair']) {
                $message = isset($fairness['blocked'])
                    ? 'Fairness Check: User has exceeded their reservation limit. Blocked until ' . $fairness['until']
                    : 'Fairness Check: User has reached the maximum of 3 reservations in a 2-week period.';
                return redirect()->back()->with('error', $message);
            }
        }

        if (!$isWifi && strtolower($visitorType) !== 'user') {
            if (empty($visitorName)) {
                return redirect()->back()->withInput()
                    ->with('error', 'Walk-in visitor name is required.');
            }

            $nameMatch = $db->query("
                SELECT id, name FROM users
                WHERE LOWER(TRIM(name)) = LOWER(TRIM(?))
                   OR LOWER(TRIM(full_name)) = LOWER(TRIM(?))
                LIMIT 1
            ", [$visitorName, $visitorName])->getRowArray();

            if ($nameMatch) {
                return redirect()->back()->withInput()->with('error',
                    "\"{$visitorName}\" is a registered resident. " .
                    'Please use the Registered User toggle and select them from the dropdown ' .
                    'so the reservation is recorded under their account.'
                );
            }

            $walkInCheck = $reservationModel->checkWalkInFairness($visitorName);
            if (!$walkInCheck['fair']) {
                return redirect()->back()->withInput()->with('error',
                    "Walk-in visitor \"{$visitorName}\" has already used all 3 reservation slots " .
                    "in the last 3 days. They may book again on or after {$walkInCheck['reset']}."
                );
            }
        }

        if (!$this->validate([
            'resource_id'      => 'required|numeric',
            'reservation_date' => 'required|valid_date[Y-m-d]',
            'start_time'       => 'required',
            'end_time'         => 'required',
            'purpose'          => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Please fill all required fields.');
        }

        if ($reservationDate < date('Y-m-d')) {
            return redirect()->back()->withInput()
                ->with('error', 'You cannot make a reservation for a past date.');
        }

        if ($startTime >= $endTime) {
            return redirect()->back()->withInput()
                ->with('error', 'End time must be after start time.');
        }

        $db->transStart();

        $conflictQuery = $reservationModel
            ->where('resource_id', $resourceId)
            ->where('reservation_date', $reservationDate)
            ->whereIn('status', ['pending', 'approved'])
            ->groupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >',  $startTime)
            ->groupEnd();

        if (!empty($pcValue)) {
            $pcsRequested = array_map('trim', explode(',', $pcValue));
            $conflictQuery->groupStart();
            foreach ($pcsRequested as $i => $pc) {
                if ($i === 0) {
                    $conflictQuery->like('pc_number', $pc, 'none');
                } else {
                    $conflictQuery->orLike('pc_number', $pc, 'none');
                }
            }
            $conflictQuery->groupEnd();
        }

        $conflict = $conflictQuery->first();

        if ($conflict) {
            $db->transRollback();
            $errorMsg = !empty($pcValue)
                ? 'One or more of the selected PCs is already booked for this time slot. Please choose different PCs or a different time.'
                : 'This time slot is already booked. Please choose another time.';
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $eTicketCode = 'ADMIN' . strtoupper(uniqid());

        $data = [
            'resource_id'      => !empty($resourceId) ? (int)$resourceId : null,
            'visitor_name'     => $visitorName,
            'visitor_type'     => $visitorType,
            'user_id'          => ($visitorType === 'User' && $userId > 0) ? $userId : null,
            'user_email'       => $resolvedEmail,
            'reservation_date' => $reservationDate,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'purpose'          => $request->getPost('purpose'),
            'pc_number'        => $pcValue,
            'status'           => 'approved',
            'claimed'          => false,
            'approved_by'      => session()->get('user_id'),
            'e_ticket_code'    => $eTicketCode,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $newId = $reservationModel->insert($data, true);
        $db->transComplete();

        if ($db->transStatus() === false || !$newId) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create reservation. Please try again.');
        }

        $resource     = (new ResourceModel())->find($resourceId);
        $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

        $this->logActivity('create', (int) $newId,
            "Created reservation for {$visitorName} - {$resourceName} on {$reservationDate}");

        return redirect()->to('/admin/manage-reservations')
            ->with('success', 'Reservation created successfully. E-Ticket: ' . $eTicketCode);
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  CHECK GUEST LIMIT
    // ═══════════════════════════════════════════════════════════════════════
    public function checkGuestLimit()
    {
        date_default_timezone_set('Asia/Manila');

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $name        = trim($this->request->getGet('name')          ?? '');
        $visitorType = strtolower(trim($this->request->getGet('visitor_type') ?? 'visitor'));

        if ($visitorType === 'user') {
            return $this->response->setJSON([
                'count'         => 0,
                'limit'         => 3,
                'blocked'       => false,
                'reset'         => null,
                'is_new'        => false,
                'is_registered' => false,
                'skip_quota'    => true,
            ]);
        }

        if (empty($name)) {
            return $this->response->setJSON([
                'count'         => 0,
                'limit'         => 3,
                'blocked'       => false,
                'reset'         => null,
                'is_new'        => true,
                'is_registered' => false,
            ]);
        }

        $db = $this->db;

        $registeredUser = $db->query("
            SELECT id, name, email FROM users
            WHERE LOWER(TRIM(name)) = LOWER(TRIM(?))
               OR LOWER(TRIM(full_name)) = LOWER(TRIM(?))
            LIMIT 1
        ", [$name, $name])->getRowArray();

        if ($registeredUser) {
            return $this->response->setJSON([
                'count'           => 0,
                'limit'           => 3,
                'blocked'         => false,
                'reset'           => null,
                'is_new'          => false,
                'is_registered'   => true,
                'registered_id'   => $registeredUser['id'],
                'registered_name' => $registeredUser['name'],
                'skip_quota'      => true,
            ]);
        }

        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

        $usedRow = $db->query("
            SELECT COUNT(*) AS total FROM reservations
            WHERE LOWER(TRIM(visitor_name)) = LOWER(TRIM(?))
              AND status NOT IN ('declined', 'canceled')
              AND user_id IS NULL
              AND reservation_date >= ?
        ", [$name, $threeDaysAgo])->getRowArray();

        $used = (int) ($usedRow['total'] ?? 0);

        $reset = null;
        if ($used >= 3) {
            $oldest = $db->query("
                SELECT reservation_date FROM reservations
                WHERE LOWER(TRIM(visitor_name)) = LOWER(TRIM(?))
                  AND status NOT IN ('declined', 'canceled')
                  AND user_id IS NULL
                  AND reservation_date >= ?
                ORDER BY reservation_date ASC
                LIMIT 1
            ", [$name, $threeDaysAgo])->getRowArray();

            $reset = $oldest
                ? date('F j, Y', strtotime($oldest['reservation_date'] . ' +4 days'))
                : date('F j, Y', strtotime('+3 days'));
        }

        return $this->response->setJSON([
            'count'         => $used,
            'limit'         => 3,
            'blocked'       => $used >= 3,
            'reset'         => $reset,
            'is_new'        => $used === 0,
            'is_registered' => false,
        ]);
    }

    public function approve()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $reservation = $this->reservationModel->find($id);
        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found');
        }

        $this->db->transStart();
        $this->reservationModel->update($id, [
            'status'      => 'approved',
            'approved_by' => session()->get('user_id'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to approve reservation. Please try again.');
        }

        $resource     = (new ResourceModel())->find($reservation['resource_id']);
        $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

        $this->logActivity('approve', $id,
            "Approved reservation #$id for {$reservation['visitor_name']} - {$resourceName}");

        return redirect()->to('/admin/manage-reservations')->with('success', 'Reservation approved.');
    }

    public function decline()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $reservation = $this->reservationModel->find($id);
        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found');
        }

        $this->db->transStart();
        $this->reservationModel->update($id, [
            'status'      => 'declined',
            'approved_by' => session()->get('user_id'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to decline reservation. Please try again.');
        }

        $resource     = (new ResourceModel())->find($reservation['resource_id']);
        $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

        $this->logActivity('decline', $id,
            "Declined reservation #$id for {$reservation['visitor_name']} - {$resourceName}");

        return redirect()->to('/admin/manage-reservations')->with('success', 'Reservation declined.');
    }

    public function approveSK()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = $this->request->getPost('id');
        if ($id) {
            $user = (new UserModel())->find($id);
            (new UserModel())->update($id, [
                'status'      => 'approved',
                'is_approved' => true,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $this->sendSKDecisionEmail($user['email'], $user['name'], 'approved');
            $this->logActivity('approve_sk', null, "Approved SK account: {$user['name']} ({$user['email']})");
        }

        return redirect()->to('/admin/manage-sk')->with('success', 'SK account approved.');
    }

    public function rejectSK()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = $this->request->getPost('id');
        if ($id) {
            $user = (new UserModel())->find($id);
            (new UserModel())->update($id, [
                'status'      => 'rejected',
                'is_approved' => false,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $this->sendSKDecisionEmail($user['email'], $user['name'], 'rejected');
            $this->logActivity('reject_sk', null, "Rejected SK account: {$user['name']} ({$user['email']})");
        }

        return redirect()->to('/admin/manage-sk')->with('success', 'SK account rejected.');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  DELETE SK
    // ═══════════════════════════════════════════════════════════════════════
    public function deleteSK()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $user = $this->db->table('users')
            ->where('id', $id)
            ->where('role', 'sk')
            ->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'SK account not found or cannot be deleted.');
        }

        try {
            $this->db->transStart();

            $this->db->table('accounts')->where('user_id', $id)->delete();

            $this->db->table('reservations')->where('user_id', $id)->set([
                'user_id'    => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ])->update();

            $this->db->table('activity_logs')->where('user_id', $id)->delete();

            $this->db->table('users')->where('id', $id)->delete();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            $this->logActivity(
                'delete_sk',
                null,
                "Permanently deleted SK account: {$user['name']} (#{$id}, {$user['email']})"
            );

            return redirect()->to('/admin/manage-sk')
                ->with('success', "SK account \"{$user['name']}\" has been permanently deleted.");

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'deleteSK error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete SK account. Please try again.');
        }
    }

    public function loginLogs()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $logs = (new LoginLog())
            ->select('login_logs.*, users.name, users.email, users.role')
            ->join('users', 'users.id = login_logs.user_id', 'left')
            ->orderBy('login_logs.login_time', 'DESC')
            ->findAll();

        return view('admin/login-logs', [
            'page'        => 'login-logs',
            'logs'        => $logs,
            'totalLogins' => count($logs),
            'todayLogins' => (new LoginLog())->where('DATE(login_time)', date('Y-m-d'))->countAllResults(),
        ]);
    }

    public function activityLogs()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $logs = $this->db->table('activity_logs al')
            ->select('al.id, al.action, al.reservation_id, al.details, al.created_at, u.name, u.email, u.role')
            ->join('users u', 'u.id = al.user_id', 'left')
            ->orderBy('al.created_at', 'DESC')
            ->limit(500)
            ->get()->getResultArray();

        $totalActivities = count($logs);
        $todayActivities = $this->db->table('activity_logs')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        $createCount = $approveCount = $declineCount = $claimCount = 0;
        foreach ($logs as $log) {
            switch (strtolower(trim($log['action'] ?? ''))) {
                case 'create':  $createCount++;  break;
                case 'approve': $approveCount++; break;
                case 'decline': $declineCount++; break;
                case 'claim':   $claimCount++;   break;
            }
        }

        return view('admin/activity-logs', [
            'page'            => 'activity-logs',
            'logs'            => $logs,
            'totalActivities' => $totalActivities,
            'todayActivities' => $todayActivities,
            'createCount'     => $createCount,
            'approveCount'    => $approveCount,
            'declineCount'    => $declineCount,
            'claimCount'      => $claimCount,
        ]);
    }

    public function scanner()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $allReservations = $this->reservationModel
            ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->join('users', 'users.id = reservations.user_id', 'left')
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();

        return view('admin/scanner', [
            'page'            => 'scanner',
            'allReservations' => $allReservations,
        ]);
    }

    public function validateETicket()
    {
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please login first']);
        }

        $code = $this->request->getPost('code');
        if (!$code) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No code provided']);
        }

        $reservation = $this->reservationModel
            ->where('e_ticket_code', $code)
            ->where('status', 'approved')
            ->where('claimed', false)
            ->first();

        if ($reservation) {
            $this->db->transStart();

            try {
                $this->reservationModel->update($reservation['id'], [
                    'claimed'    => true,
                    'claimed_at' => date('Y-m-d H:i:s'),
                ]);

                $resource     = (new ResourceModel())->find($reservation['resource_id']);
                $resourceName = $resource ? $resource['name'] : 'Unknown Resource';
                $userName     = $reservation['visitor_name'] ?? $reservation['full_name'] ?? 'Guest';

                $this->db->table('activity_logs')->insert([
                    'user_id'        => session()->get('user_id'),
                    'action'         => 'claim',
                    'reservation_id' => $reservation['id'],
                    'details'        => "Claimed e-ticket for {$userName} - {$resourceName} (ID: {$reservation['id']})",
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                return $this->response->setJSON(['status' => 'success', 'message' => 'E-Ticket validated successfully! Reservation claimed.']);
            } catch (\Exception $e) {
                $this->db->transRollback();
                log_message('error', 'Failed to log claim: ' . $e->getMessage());
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error processing claim: ' . $e->getMessage()]);
            }
        }

        if ($this->reservationModel->where('e_ticket_code', $code)->where('claimed', true)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'This ticket has already been claimed.']);
        }

        if ($this->reservationModel->where('e_ticket_code', $code)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Reservation is not yet approved.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid E-Ticket code.']);
    }

    public function deleteProfile()
    {
        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();
        $userModel->delete($userId);
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Account deleted successfully.');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $user          = (new UserModel())->find($userId);
        $createdCount  = $this->reservationModel->where('user_id', $userId)->countAllResults();
        $approvedCount = $this->reservationModel->where('approved_by', $userId)->countAllResults();

        return view('admin/profile', [
            'page'          => 'profile',
            'user'          => $user,
            'createdCount'  => $createdCount,
            'approvedCount' => $approvedCount,
        ]);
    }

    public function profileUpdate()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        if (!$this->validate(['name' => 'required|min_length[3]', 'email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('error', 'Please provide valid name and email');
        }

        $userModel = new UserModel();
        $userModel->update($userId, [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $accountModel = new AccountModel();
            $account      = $accountModel->where('user_id', $userId)->first();
            if ($account) {
                $accountModel->update($account['id'], [
                    'password'   => password_hash($password, PASSWORD_DEFAULT),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        session()->set('name', $this->request->getPost('name'));
        session()->set('email', $this->request->getPost('email'));
        $this->logActivity('profile_update', null, "Updated profile for " . $this->request->getPost('name'));

        return redirect()->to('/admin/profile')->with('success', 'Profile updated successfully.');
    }

    public function checkNewReservations()
    {
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if ($this->request->isAJAX()) {
            $lastCheck = $this->request->getPost('last_check') ?? date('Y-m-d H:i:s', strtotime('-1 hour'));

            $newReservations = $this->reservationModel
                ->select('reservations.*, resources.name as resource_name, users.name as visitor_name')
                ->join('resources', 'resources.id = reservations.resource_id', 'left')
                ->join('users', 'users.id = reservations.user_id', 'left')
                ->where('reservations.status', 'pending')
                ->where('reservations.created_at >', $lastCheck)
                ->orderBy('reservations.created_at', 'DESC')
                ->findAll();

            return $this->response->setJSON(['new_reservations' => $newReservations]);
        }
    }

    public function getPendingCount()
    {
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'pending_count' => $this->reservationModel->where('status', 'pending')->countAllResults(),
            ]);
        }
    }

    public function createInitialAdmin()
    {
        $userCount = $this->db->table('users')->countAllResults();

        if ($userCount > 0) {
            return redirect()->to('/login')->with('info', 'Users already exist. Please login.');
        }

        $name     = $this->request->getPost('name')     ?? 'Administrator';
        $email    = $this->request->getPost('email')    ?? 'admin@example.com';
        $password = $this->request->getPost('password') ?? 'admin123';

        try {
            $this->db->table('users')->insert([
                'name'        => $name,
                'email'       => $email,
                'role'        => 'chairman',
                'status'      => 'approved',
                'is_approved' => true,
                'is_verified' => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $newId = $this->db->insertID();

            $this->db->table('accounts')->insert([
                'user_id'    => $newId,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $newId,
                'name'       => $name,
                'email'      => $email,
                'role'       => 'chairman',
            ]);

            return redirect()->to('/admin/manage-pcs')
                ->with('success', 'Admin user created successfully! You are now logged in.');
        } catch (\Exception $e) {
            log_message('error', 'Failed to create admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create admin user: ' . $e->getMessage());
        }
    }

    public function emergencyFix()
    {
        $userCount = $this->db->table('users')->countAllResults();

        if ($userCount == 0) {
            $this->db->table('users')->insert([
                'name'        => 'Admin',
                'email'       => 'admin@demo.com',
                'role'        => 'chairman',
                'status'      => 'approved',
                'is_approved' => true,
                'is_verified' => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $newId = $this->db->insertID();

            $this->db->table('accounts')->insert([
                'user_id'    => $newId,
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $newId,
                'name'       => 'Admin',
                'email'      => 'admin@demo.com',
                'role'       => 'chairman',
            ]);

            return redirect()->to('/admin/manage-pcs')
                ->with('success', 'Admin created! Email: admin@demo.com, Password: admin123');
        }

        $firstUser = $this->db->table('users')->orderBy('id', 'ASC')->get()->getRowArray();
        if ($firstUser) {
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $firstUser['id'],
                'name'       => $firstUser['name'],
                'email'      => $firstUser['email'],
                'role'       => $firstUser['role'] ?? 'chairman',
            ]);
            return redirect()->to('/admin/manage-pcs')->with('success', 'Logged in as existing user!');
        }

        return redirect()->back()->with('error', 'Could not create or find any user');
    }

    public function debugUserSession()
    {
        $userId   = session()->get('user_id');
        $userData = null;

        if ($userId) {
            $userData = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        }

        return $this->response->setJSON([
            'session_user_id'   => $userId,
            'user_exists_in_db' => !empty($userData),
            'user_data'         => $userData,
            'all_users'         => $this->db->table('users')
                ->select('id, name, email, role, status')->get()->getResultArray(),
        ]);
    }

    public function fixSession()
    {
        $userEmail = session()->get('email');
        if (!$userEmail) {
            return $this->response->setJSON(['error' => 'No email in session']);
        }

        $user = $this->db->table('users')
            ->where('email', $userEmail)
            ->whereIn('status', ['approved', 'pending'])
            ->get()->getRowArray();

        if ($user) {
            session()->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);
            return $this->response->setJSON(['success' => true, 'message' => 'Session fixed', 'user' => $user]);
        }

        return $this->response->setJSON(['error' => 'No active user found with that email']);
    }

    public function setupUsers()
    {
        return view('admin/setup-users', [
            'page'      => 'setup',
            'userCount' => $this->db->table('users')->countAllResults(),
        ]);
    }

    public function extractPdfWithAI()
    {
        try {
            return $this->_doExtractPdfWithAI();
        } catch (\Throwable $e) {
            log_message('error', 'Admin extractPdfWithAI crashed: ' . $e->getMessage()
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
        $tmpPath = $tmpDir . 'admin_pdf_' . uniqid('', true) . '.pdf';

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

        $wordChars  = preg_match_all('/[a-zA-Z0-9 ]/', $pdfText);
        $totalChars = max(1, strlen($pdfText));
        if (($wordChars / $totalChars) < 0.40) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => 'This PDF appears to contain only images or encoded fonts with no readable text '
                         . '(method: ' . $method . '). Please use a text-based PDF.',
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
            $msg = $groqData['error']['message'] ?? ('Groq HTTP ' . $httpStatus . ': ' . substr($response, 0, 200));
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

    // ═══════════════════════════════════════════════════════════════════════
    //  RESIDENT ACCOUNTS
    // ═══════════════════════════════════════════════════════════════════════
    public function residentAccounts()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $db = $this->db;

        $residents = $db->table('users u')
            ->select('u.*, a.is_verified AS email_verified')
            ->join('accounts a', 'a.user_id = u.id', 'left')
            ->where('u.role', 'user')
            ->orderBy('u.created_at', 'DESC')
            ->get()->getResultArray();

        $reservationCounts = [];
        if (!empty($residents)) {
            $ids = array_column($residents, 'id');
            $rows = $db->table('reservations')
                ->select('user_id, COUNT(*) as total')
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()->getResultArray();
            foreach ($rows as $row) {
                $reservationCounts[(int)$row['user_id']] = (int)$row['total'];
            }
        }

        $total      = count($residents);
        $verified   = count(array_filter($residents, fn($r) => !empty($r['email_verified'])));
        $unverified = $total - $verified;

        return view('admin/resident-accounts', [
            'page'              => 'resident-accounts',
            'residents'         => $residents,
            'reservationCounts' => $reservationCounts,
            'total'             => $total,
            'verified'          => $verified,
            'unverified'        => $unverified,
        ]);
    }

    public function deleteResident()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $user = $this->db->table('users')
            ->where('id', $id)
            ->where('role', 'user')
            ->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Resident not found or cannot be deleted.');
        }

        try {
            $this->db->transStart();

            $this->db->table('accounts')->where('user_id', $id)->delete();
            $this->db->table('reservations')->where('user_id', $id)->set([
                'user_id'    => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ])->update();
            $this->db->table('users')->where('id', $id)->delete();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            $this->logActivity(
                'delete_resident',
                null,
                "Deleted resident: {$user['name']} (#{$id}, {$user['email']})"
            );

            return redirect()->to('/admin/resident-accounts')
                ->with('success', "Resident \"{$user['name']}\" has been permanently deleted.");

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'deleteResident error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete resident. Please try again.');
        }
    }

    public function exportResidents()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $db = $this->db;

        $residents = $db->table('users u')
            ->select('u.id, u.name, u.email, u.phone, u.created_at, a.is_verified AS email_verified')
            ->join('accounts a', 'a.user_id = u.id', 'left')
            ->where('u.role', 'user')
            ->orderBy('u.created_at', 'DESC')
            ->get()->getResultArray();

        $reservationCounts = [];
        if (!empty($residents)) {
            $ids = array_column($residents, 'id');
            $rows = $db->table('reservations')
                ->select('user_id, COUNT(*) as total')
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()->getResultArray();
            foreach ($rows as $row) {
                $reservationCounts[(int)$row['user_id']] = (int)$row['total'];
            }
        }

        $filename = 'residents_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, ['ID', 'Full Name', 'Email', 'Phone', 'Registered', 'Email Verified', 'Reservations']);

        foreach ($residents as $r) {
            fputcsv($out, [
                $r['id'],
                $r['name'] ?? 'Unknown',
                $r['email'] ?? '',
                $r['phone'] ?? '',
                !empty($r['created_at']) ? date('Y-m-d', strtotime($r['created_at'])) : '',
                !empty($r['email_verified']) ? 'Yes' : 'No',
                $reservationCounts[(int)$r['id']] ?? 0,
            ]);
        }

        fclose($out);
        $this->logActivity('export_residents', null, 'Exported resident accounts CSV');
        exit;
    }
    // ═══════════════════════════════════════════════════════════════════════
    //  APPROVE / REJECT RESIDENT
    // ═══════════════════════════════════════════════════════════════════════
    public function approveResident()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $user = (new UserModel())->find($id);
        if (!$user || $user['role'] !== 'user') {
            return redirect()->back()->with('error', 'Resident not found.');
        }

        (new UserModel())->update($id, [
            'status'      => 'approved',
            'is_approved' => true,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->sendResidentDecisionEmail($user['email'], $user['name'], 'approved');
        $this->logActivity('approve_resident', null, "Approved resident account: {$user['name']} ({$user['email']})");

        return redirect()->to('/admin/resident-accounts')
            ->with('success', "Resident \"{$user['name']}\" has been approved.");
    }

    public function rejectResident()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $user = (new UserModel())->find($id);
        if (!$user || $user['role'] !== 'user') {
            return redirect()->back()->with('error', 'Resident not found.');
        }

        (new UserModel())->update($id, [
            'status'      => 'rejected',
            'is_approved' => false,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->sendResidentDecisionEmail($user['email'], $user['name'], 'rejected');
        $this->logActivity('reject_resident', null, "Rejected resident account: {$user['name']} ({$user['email']})");

        return redirect()->to('/admin/resident-accounts')
            ->with('success', "Resident \"{$user['name']}\" has been rejected.");
    }

    private function sendResidentDecisionEmail(string $to, string $name, string $decision): void
    {
        $apiKey = env('BREVO_API_KEY', '');
        if (empty($apiKey)) {
            log_message('error', '[AdminController] BREVO_API_KEY is not set');
            return;
        }

        $subject = $decision === 'approved'
            ? 'Your Resident Account Has Been Approved'
            : 'Update on Your Resident Account Application';

        $body = view('emails/sk_decision', [
            'name'     => $name,
            'decision' => $decision,
            'loginUrl' => base_url('login'),
        ]);

        $payload = json_encode([
            'sender'      => [
                'name'  => env('EMAIL_FROM_NAME', 'E-Learning System'),
                'email' => env('EMAIL_FROM_ADDRESS', 'noreply@elearning.edu.ph'),
            ],
            'to'          => [['email' => $to, 'name' => $name]],
            'subject'     => $subject,
            'htmlContent' => $body,
        ]);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'api-key: ' . $apiKey,
            ],
        ]);

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus !== 201) {
            log_message('error', '[AdminController] Resident decision email failed: HTTP ' . $httpStatus . ' | ' . $response);
        }
    }
}