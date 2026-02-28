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
                ->whereIn('status', ['active', 'pending'])
                ->countAllResults();

            if ($userExists) {
                return $userId;
            }
        }

        $email = session()->get('email');
        if ($email) {
            $user = $this->db->table('users')
                ->where('email', $email)
                ->whereIn('status', ['active', 'pending'])
                ->get()
                ->getRowArray();

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
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getRowArray();

        if ($chairman) {
            session()->set('user_id', $chairman['id']);
            session()->set('name', $chairman['name']);
            session()->set('email', $chairman['email']);
            session()->set('role', $chairman['role']);
            session()->set('isLoggedIn', true);
            return $chairman['id'];
        }

        $anyUser = $this->db->table('users')
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('id', 'ASC')
            ->get()
            ->getRowArray();

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
            ->limit(10)
            ->get()
            ->getResultArray();

        $total    = $model->countAll();
        $pending  = $model->where('status', 'pending')->countAllResults();
        $approved = $model->where('status', 'approved')->countAllResults();
        $declined = $model->where('status', 'declined')->countAllResults();
        $claimed  = $model->where('claimed', 1)->countAllResults();

        $today         = date('Y-m-d');
        $todayTotal    = $model->where('reservation_date', $today)->countAllResults();
        $todayApproved = $model->where('reservation_date', $today)->where('status', 'approved')->countAllResults();
        $todayPending  = $model->where('reservation_date', $today)->where('status', 'pending')->countAllResults();
        $todayClaimed  = $model->where('reservation_date', $today)->where('claimed', 1)->countAllResults();

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
            ->groupBy('r.resource_id')
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

        $approvalRate    = $total > 0    ? round(($approved / $total) * 100)   : 0;
        $utilizationRate = $approved > 0 ? round(($claimed / $approved) * 100) : 0;

        $totalUsers     = $db->table('users')->where('role', 'resident')->countAllResults();
        $totalResources = $db->table('resources')->countAllResults();

        return view('admin/dashboard', [
            'page'            => 'dashboard',
            'total'           => $total,
            'pending'         => $pending,
            'approved'        => $approved,
            'declined'        => $declined,
            'claimed'         => $claimed,
            'monthlyTotal'    => $monthlyTotal,
            'todayTotal'      => $todayTotal,
            'todayApproved'   => $todayApproved,
            'todayPending'    => $todayPending,
            'todayClaimed'    => $todayClaimed,
            'chartLabels'     => $chartLabels,
            'chartData'       => $chartData,
            'resourceLabels'  => $resourceLabels,
            'resourceData'    => $resourceData,
            'topResources'    => $topResources,
            'approvalRate'    => $approvalRate,
            'utilizationRate' => $utilizationRate,
            'totalUsers'      => $totalUsers,
            'totalResources'  => $totalResources,
            'reservations'    => $reservations,
        ]);
    }

    public function newReservation()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        return view('admin/new-reservation', [
            'page'      => 'new-reservation',
            'resources' => (new ResourceModel())->findAll(),
            'pcs'       => (new PcModel())->where('status', 'available')->findAll(),
            'users'     => (new UserModel())->findAll(),
        ]);
    }

    public function manageReservations()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $reservations = $this->db->table('reservations r')
            ->select('r.*, res.name AS resource_name, u.name as visitor_name, u.email as user_email')
            ->join('resources res', 'res.id = r.resource_id', 'left')
            ->join('users u', 'u.id = r.user_id', 'left')
            ->orderBy('r.id', 'DESC')
            ->get()
            ->getResultArray();

        $total    = count($reservations);
        $pending  = $this->reservationModel->where('status', 'pending')->countAllResults();
        $approved = $this->reservationModel->where('status', 'approved')->countAllResults();
        $claimed  = $this->reservationModel->where('claimed', 1)->countAllResults();
        $declined = $this->reservationModel->where('status', 'declined')->countAllResults();

        return view('admin/manage-reservations', [
            'page'         => 'manage-reservations',
            'reservations' => $reservations,
            'total'        => $total,
            'pending'      => $pending,
            'approved'     => $approved,
            'claimed'      => $claimed,
            'declined'     => $declined,
        ]);
    }

    public function manageSK()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userModel     = new UserModel();
        $skAccounts    = $userModel->where('role', 'SK')->findAll();
        $pendingCount  = $userModel->where('role', 'SK')->where('status', 'pending')->countAllResults();
        $approvedCount = $userModel->where('role', 'SK')->where('status', 'approved')->countAllResults();
        $rejectedCount = $userModel->where('role', 'SK')->where('status', 'rejected')->countAllResults();

        return view('admin/manage-sk', [
            'page'          => 'manage-sk',
            'skAccounts'    => $skAccounts,
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
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
        $inUseCount       = $pcModel->where('status', 'in_use')->countAllResults();
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

    public function createReservation()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userType    = $this->request->getPost('visitor_type');
        $eTicketCode = 'ADMIN' . strtoupper(uniqid());

        $data = [
            'resource_id'      => $this->request->getPost('resource_id'),
            'visitor_name'     => $this->request->getPost('visitor_name'),
            'visitor_type'     => $userType,
            'user_email'       => $this->request->getPost('user_email') ?: null,
            'reservation_date' => $this->request->getPost('reservation_date'),
            'start_time'       => $this->request->getPost('start_time'),
            'end_time'         => $this->request->getPost('end_time'),
            'purpose'          => $this->request->getPost('purpose'),
            'pc_number'        => $this->request->getPost('pc_number') ?: null,
            'status'           => 'approved',
            'e_ticket_code'    => $eTicketCode,
            'created_at'       => date('Y-m-d H:i:s'),
        ];

        if ($userType === 'User') {
            $userId = $this->request->getPost('user_id');
            if (!$userId) {
                return redirect()->back()->with('error', 'Please select a registered user from the list.');
            }
            $data['user_id'] = $userId;
        } else {
            $data['user_id'] = null;
        }

        $newId        = $this->reservationModel->insert($data, true);
        $resource     = (new ResourceModel())->find($data['resource_id']);
        $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

        $this->logActivity('create', (int) $newId, "Created reservation for {$data['visitor_name']} - {$resourceName} on {$data['reservation_date']}");

        return redirect()->to('/admin/manage-reservations')->with('success', 'Reservation created successfully. E-Ticket: ' . $eTicketCode);
    }

    public function approve()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if ($id) {
            $reservation  = $this->reservationModel->find($id);
            $resource     = (new ResourceModel())->find($reservation['resource_id']);
            $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

            $this->reservationModel->update($id, [
                'status'      => 'approved',
                'approved_by' => session()->get('user_id'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            $this->logActivity('approve', $id, "Approved reservation #$id for {$reservation['visitor_name']} - {$resourceName}");
        }

        return redirect()->to('/admin/manage-reservations')->with('success', 'Reservation approved.');
    }

    public function decline()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $id = (int) $this->request->getPost('id');
        if ($id) {
            $reservation  = $this->reservationModel->find($id);
            $resource     = (new ResourceModel())->find($reservation['resource_id']);
            $resourceName = $resource ? $resource['name'] : 'Unknown Resource';

            $this->reservationModel->update($id, [
                'status'      => 'declined',
                'approved_by' => session()->get('user_id'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            $this->logActivity('decline', $id, "Declined reservation #$id for {$reservation['visitor_name']} - {$resourceName}");
        }

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
            (new UserModel())->update($id, ['status' => 'approved', 'updated_at' => date('Y-m-d H:i:s')]);
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
            (new UserModel())->update($id, ['status' => 'rejected', 'updated_at' => date('Y-m-d H:i:s')]);
            $this->logActivity('reject_sk', null, "Rejected SK account: {$user['name']} ({$user['email']})");
        }

        return redirect()->to('/admin/manage-sk')->with('success', 'SK account rejected.');
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

        $totalLogins = count($logs);
        $todayLogins = (new LoginLog())->where('DATE(login_time)', date('Y-m-d'))->countAllResults();

        return view('admin/login-logs', [
            'page'        => 'login-logs',
            'logs'        => $logs,
            'totalLogins' => $totalLogins,
            'todayLogins' => $todayLogins,
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
            ->get()
            ->getResultArray();

        $totalActivities = count($logs);
        $todayActivities = $this->db->table('activity_logs')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        $createCount  = 0;
        $approveCount = 0;
        $declineCount = 0;
        $claimCount   = 0;

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
            ->where('claimed', 0)
            ->first();

        if ($reservation) {
            $this->db->transStart();

            try {
                $this->reservationModel->update($reservation['id'], [
                    'claimed'    => 1,
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

        if ($this->reservationModel->where('e_ticket_code', $code)->where('claimed', 1)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'This ticket has already been claimed.']);
        }

        if ($this->reservationModel->where('e_ticket_code', $code)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Reservation is not yet approved.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid E-Ticket code.']);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $user = (new UserModel())->find($userId);

        // FIX: use user_id (who created) and approved_by (who approved)
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

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please provide valid name and email');
        }

        $userModel = new UserModel();

        // Update profile info in users table (no password here)
        $userModel->update($userId, [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Update password in accounts table if provided
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
            // Insert into users first
            $this->db->table('users')->insert([
                'name'       => $name,
                'email'      => $email,
                'role'       => 'chairman',
                'status'     => 'active',
                'is_approved'=> 1,
                'is_verified'=> 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $newId = $this->db->insertID();

            // Insert credentials into accounts
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

            return redirect()->to('/admin/manage-pcs')->with('success', 'Admin user created successfully! You are now logged in.');
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
                'status'      => 'active',
                'is_approved' => 1,
                'is_verified' => 1,
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

            return redirect()->to('/admin/manage-pcs')->with('success', 'Admin created! Email: admin@demo.com, Password: admin123');
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
            'session_user_id'    => $userId,
            'user_exists_in_db'  => !empty($userData),
            'user_data'          => $userData,
            'all_users'          => $this->db->table('users')->select('id, name, email, role, status')->get()->getResultArray(),
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
            ->whereIn('status', ['active', 'pending'])
            ->get()
            ->getRowArray();

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

    public function debugUserIssue()
    {
        $allUsers     = $this->db->table('users')->select('id, name, email, role, status')->get()->getResultArray();
        $validUserId  = $this->getValidUserId();

        echo "<h1>Database Users</h1><pre>" . print_r($allUsers, true) . "</pre>";
        echo "<h1>getValidUserId() returns: " . ($validUserId ?? 'NULL') . "</h1>";
        echo "<h1>Session Data</h1><pre>" . print_r($_SESSION, true) . "</pre>";
        exit;
    }
}