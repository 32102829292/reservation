<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ReservationModel;
use App\Models\ResourceModel;

class UserController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        $reservationModel = new ReservationModel();
        $reservations     = $reservationModel->getUserReservations($userId);

        $allReservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name, resources.description as resource_description')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->orderBy('reservations.reservation_date', 'DESC')
            ->findAll();

        $total = count($reservations);
        $pending = $approved = $declined = 0;
        foreach ($reservations as $res) {
            switch ($res['status']) {
                case 'pending':  $pending++;  break;
                case 'approved': $approved++; break;
                case 'declined':
                case 'canceled': $declined++; break;
            }
        }

        $db       = \Config\Database::connect();
        $allBooks = $db->table('books')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $availableCount = count(array_filter($allBooks, fn($b) => (int)$b['available_copies'] > 0));
        $totalBooks     = count($allBooks);

        $featuredBooks = array_values(
            array_slice(
                array_filter($allBooks, fn($b) => (int)$b['available_copies'] > 0),
                0, 5
            )
        );

        $myBorrowings = $db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.borrowed_at, bb.due_date, bb.returned_at, b.title, b.author')
            ->join('books b', 'b.id = bb.book_id')
            ->where('bb.user_id', $userId)
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        return view('user/dashboard', [
            'page'            => 'dashboard',
            'user'            => $user,
            'user_name'       => $user['name'] ?? '',
            'reservations'    => $reservations,
            'allReservations' => $allReservations,
            'total'           => $total,
            'pending'         => $pending,
            'approved'        => $approved,
            'declined'        => $declined,
            'availableCount'  => $availableCount,
            'totalBooks'      => $totalBooks,
            'featuredBooks'   => $featuredBooks,
            'myBorrowings'    => $myBorrowings,
        ]);
    }

    public function reservation()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            $session->destroy();
            return redirect()->to('/login');
        }

        $resourceModel = new ResourceModel();
        $resources     = $resourceModel->orderBy('name', 'ASC')->findAll();

        $db  = db_connect();
        $pcs = [];
        if ($db->tableExists('pcs')) {
            $pcs = $db->table('pcs')
                ->select('pc_number')
                ->where('status', 'available')
                ->orderBy('pc_number', 'ASC')
                ->get()->getResultArray();
        }

        $purposes = ['Work', 'Personal', 'Study', 'SK Activity', 'Others'];

        $reservationModel      = new ReservationModel();
        $remainingReservations = $reservationModel->getRemainingReservations($userId);
        $isBlocked             = $reservationModel->isBlocked($userId);

        return view('user/reservation', [
            'page'                  => 'reservation',
            'user'                  => $user,
            'user_name'             => $user['name'] ?? $session->get('name'),
            'resources'             => $resources,
            'pcs'                   => $pcs,
            'purposes'              => $purposes,
            'remainingReservations' => $remainingReservations,
            'isBlocked'             => $isBlocked,
        ]);
    }

    public function createReservation()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $request          = $this->request;
        $reservationModel = new ReservationModel();

        // ── Block check ──────────────────────────────────────────────────────
        $isBlocked = $reservationModel->isBlocked($userId);
        if ($isBlocked) {
            return redirect()->back()
                ->with('error', 'You are currently blocked from making reservations until ' . date('F j, Y', strtotime($isBlocked['blocked_until'])));
        }

        // ── Fairness / quota check ───────────────────────────────────────────
        $fairness = $reservationModel->checkFairness($userId);
        if (!$fairness['fair']) {
            $message = isset($fairness['blocked'])
                ? 'You have reached the maximum of 3 reservations in a 2-week period. Blocked until ' . $fairness['until']
                : 'You have reached the maximum of 3 reservations in a 2-week period.';
            return redirect()->back()->with('error', $message);
        }

        // ── Field validation ─────────────────────────────────────────────────
        $rules = [
            'resource_id'      => 'required|numeric',
            'reservation_date' => 'required|valid_date[Y-m-d]',
            'start_time'       => 'required',
            'end_time'         => 'required',
            'purpose'          => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill all required fields');
        }

        // ── Reject past dates ────────────────────────────────────────────────
        $reservationDate = $request->getPost('reservation_date');
        if ($reservationDate < date('Y-m-d')) {
            return redirect()->back()->withInput()
                ->with('error', 'You cannot make a reservation for a past date.');
        }

        // ── Validate end time is after start time ────────────────────────────
        $startTime = $request->getPost('start_time');
        $endTime   = $request->getPost('end_time');
        if ($startTime >= $endTime) {
            return redirect()->back()->withInput()
                ->with('error', 'End time must be after start time.');
        }

        // ── One reservation per day ──────────────────────────────────────────
        $sameDayReservations = $reservationModel->getUserSameDayReservations(
            $userId,
            $reservationDate
        );
        if (!empty($sameDayReservations)) {
            return redirect()->back()->withInput()
                ->with('error', 'You already have a reservation on this date. Only one reservation per day is allowed.');
        }

        // ── Double-booking guard (with DB lock via transaction) ──────────────
        $db = db_connect();
        $db->transStart();

        $conflict = $reservationModel
            ->where('resource_id', $request->getPost('resource_id'))
            ->where('reservation_date', $reservationDate)
            ->whereIn('status', ['pending', 'approved'])
            ->groupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >',  $startTime)
            ->groupEnd()
            ->first();

        if ($conflict) {
            $db->transRollback();
            return redirect()->back()->withInput()
                ->with('error', 'This time slot is already booked. Please choose another time.');
        }

        // ── Insert ───────────────────────────────────────────────────────────
        $eTicketCode = 'SK' . strtoupper(uniqid()) . $userId;

        $data = [
            'user_id'          => $userId,
            'resource_id'      => $request->getPost('resource_id'),
            'pc_number'        => $request->getPost('pcs') ?: null,
            'reservation_date' => $reservationDate,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'purpose'          => $request->getPost('purpose'),
            'status'           => 'pending',
            'e_ticket_code'    => $eTicketCode,
            'created_at'       => date('Y-m-d H:i:s'),
            'visitor_type'     => 'User',
            'visitor_name'     => $session->get('name'),
            'user_email'       => $session->get('email'),
        ];

        $inserted = $reservationModel->insert($data);
        $db->transComplete();

        if ($db->transStatus() === false || !$inserted) {
            return redirect()->back()->withInput()->with('error', 'Failed to create reservation. Please try again.');
        }

        $reservationId = $reservationModel->insertID();
        return redirect()->to('/reservation/success/' . $reservationId)
            ->with('success', 'Reservation created successfully!');
    }

    public function reservationList()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $reservationModel      = new ReservationModel();
        $reservations          = $reservationModel->getUserReservations($userId);
        $remainingReservations = $reservationModel->getRemainingReservations($userId);

        return view('user/reservation_list', [
            'page'                  => 'reservation-list',
            'reservations'          => $reservations,
            'remainingReservations' => $remainingReservations,
            'user_name'             => $session->get('name'),
        ]);
    }

    public function cancelReservation($id)
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $reservationModel = new ReservationModel();
        $reservation      = $reservationModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found');
        }

        if ($reservation['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Only pending reservations can be cancelled');
        }

        $reservationModel->update($id, ['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->back()->with('success', 'Reservation cancelled successfully');
    }

    public function checkAvailability()
    {
        $request = $this->request;

        if (!$request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $resourceId = $request->getPost('resource_id');
        $date       = $request->getPost('date');
        $startTime  = $request->getPost('start_time');
        $endTime    = $request->getPost('end_time');

        // ── Basic input validation ────────────────────────────────────────────
        if (!$resourceId || !$date || !$startTime || !$endTime) {
            return $this->response->setJSON(['available' => false, 'message' => 'Missing required fields.']);
        }

        // ── Reject past dates server-side too ─────────────────────────────────
        if ($date < date('Y-m-d')) {
            return $this->response->setJSON([
                'available'  => false,
                'message'    => '✗ Cannot book a past date.',
                'booked_pcs' => [],
            ]);
        }

        if ($startTime >= $endTime) {
            return $this->response->setJSON([
                'available'  => false,
                'message'    => '✗ End time must be after start time.',
                'booked_pcs' => [],
            ]);
        }

        $reservationModel = new ReservationModel();

        // ── Find all overlapping reservations for this resource/date/time ─────
        $conflicts = $reservationModel
            ->where('resource_id', $resourceId)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->groupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >',  $startTime)
            ->groupEnd()
            ->findAll();

        // ── Collect booked PC numbers from conflicts ───────────────────────────
        $bookedPcs = [];
        foreach ($conflicts as $c) {
            if (!empty($c['pc_number'])) {
                $pcs       = array_map('trim', explode(',', $c['pc_number']));
                $bookedPcs = array_merge($bookedPcs, $pcs);
            }
        }
        $bookedPcs = array_values(array_unique(array_filter($bookedPcs)));

        if (empty($conflicts)) {
            return $this->response->setJSON([
                'available'  => true,
                'message'    => '✓ This time slot is available',
                'booked_pcs' => [],
            ]);
        }

        return $this->response->setJSON([
            'available'  => false,
            'message'    => '✗ This time slot is already booked',
            'booked_pcs' => $bookedPcs,
        ]);
    }

    // ── NEW: Return all upcoming booked slots for a resource ─────────────────
    public function getBookedSlots()
    {
        $resourceId = $this->request->getGet('resource_id');

        if (!$resourceId) {
            return $this->response->setJSON(['slots' => []]);
        }

        $reservationModel = new ReservationModel();
        $slots = $reservationModel
            ->select('reservation_date, start_time, end_time')
            ->where('resource_id', $resourceId)
            ->whereIn('status', ['pending', 'approved'])
            ->where('reservation_date >=', date('Y-m-d'))
            ->findAll();

        return $this->response->setJSON(['slots' => $slots]);
    }

    public function reservationSuccess($id)
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $reservationModel = new ReservationModel();
        $reservation      = $reservationModel
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->where('reservations.id', $id)
            ->where('reservations.user_id', $userId)
            ->first();

        if (!$reservation) {
            return redirect()->to('/reservation-list')->with('error', 'Reservation not found');
        }

        return view('user/reservation_success', [
            'page'        => 'reservation',
            'reservation' => $reservation,
        ]);
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
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $userModel        = new UserModel();
        $user             = $userModel->find($userId);
        $reservationModel = new ReservationModel();
        $reservations     = $reservationModel->getUserReservations($userId);

        $total = count($reservations);
        $pending = $approved = $declined = 0;
        foreach ($reservations as $res) {
            switch ($res['status']) {
                case 'pending':   $pending++;  break;
                case 'approved':  $approved++; break;
                case 'declined':
                case 'cancelled': $declined++; break;
            }
        }

        $remainingReservations = $reservationModel->getRemainingReservations($userId);

        return view('user/profile', [
            'page'                  => 'profile',
            'user'                  => $user,
            'total'                 => $total,
            'pending'               => $pending,
            'approved'              => $approved,
            'declined'              => $declined,
            'remainingReservations' => $remainingReservations,
        ]);
    }

    public function updateProfile()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $request = $this->request;
        $rules   = ['name' => 'required|min_length[3]', 'email' => 'required|valid_email'];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please provide valid name and email');
        }

        $data = [
            'name'       => $request->getPost('name'),
            'email'      => $request->getPost('email'),
            'phone'      => $request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $password = $request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel = new UserModel();
        $userModel->update($userId, $data);

        $session->set('name',  $request->getPost('name'));
        $session->set('email', $request->getPost('email'));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function eTicket()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $code = $this->request->getGet('code');
        if (!$code) {
            return redirect()->to('/reservation-list')->with('error', 'No ticket code provided');
        }

        $reservationModel = new ReservationModel();
        $reservation      = $reservationModel
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->where('reservations.e_ticket_code', $code)
            ->where('reservations.user_id', $userId)
            ->first();

        if (!$reservation) {
            return redirect()->to('/reservation-list')->with('error', 'Ticket not found');
        }

        return view('user/eticket', ['page' => 'etickets', 'reservation' => $reservation]);
    }

    public function ticket()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $id = $this->request->getGet('id');
        if (!$id) {
            return redirect()->to('/reservation-list')->with('error', 'No reservation ID provided');
        }

        $reservationModel = new ReservationModel();
        $reservation      = $reservationModel
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->where('reservations.id', $id)
            ->where('reservations.user_id', $userId)
            ->first();

        if (!$reservation) {
            return redirect()->to('/reservation-list')->with('error', 'Reservation not found');
        }

        return view('user/ticket', ['page' => 'etickets', 'reservation' => $reservation]);
    }
}