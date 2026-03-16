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

        // Get user data
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        // Get user's reservations
        $reservationModel = new ReservationModel();
        $reservations     = $reservationModel->getUserReservations($userId);

        // Get all reservations for calendar
        $allReservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name, resources.description as resource_description')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->orderBy('reservations.reservation_date', 'DESC')
            ->findAll();

        // Calculate stats
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

        // ── Books data for dashboard ──────────────────────
        $db       = \Config\Database::connect();
        $allBooks = $db->table('books')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $availableCount = count(array_filter($allBooks, fn($b) => (int)$b['available_copies'] > 0));
        $totalBooks     = count($allBooks);

        // Up to 5 books that still have copies
        $featuredBooks = array_values(
            array_slice(
                array_filter($allBooks, fn($b) => (int)$b['available_copies'] > 0),
                0, 5
            )
        );

        // Current user's borrowings (active + pending shown on dashboard)
        $myBorrowings = $db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.borrowed_at, bb.due_date, bb.returned_at, b.title, b.author')
            ->join('books b', 'b.id = bb.book_id')
            ->where('bb.user_id', $userId)
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();
        // ─────────────────────────────────────────────────

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
            // books
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

        $isBlocked = $reservationModel->isBlocked($userId);
        if ($isBlocked) {
            return redirect()->back()
                ->with('error', 'You are currently blocked from making reservations until ' . date('F j, Y', strtotime($isBlocked['blocked_until'])));
        }

        $fairness = $reservationModel->checkFairness($userId);
        if (!$fairness['fair']) {
            $message = isset($fairness['blocked'])
                ? 'You have reached the maximum of 3 reservations in a 2-week period. Blocked until ' . $fairness['until']
                : 'You have reached the maximum of 3 reservations in a 2-week period.';
            return redirect()->back()->with('error', $message);
        }

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

        $sameDayReservations = $reservationModel->getUserSameDayReservations(
            $userId,
            $request->getPost('reservation_date')
        );
        if (!empty($sameDayReservations)) {
            return redirect()->back()->withInput()
                ->with('error', 'You already have a reservation on this date. Only one reservation per day is allowed.');
        }

        $conflict = $reservationModel
            ->where('resource_id', $request->getPost('resource_id'))
            ->where('reservation_date', $request->getPost('reservation_date'))
            ->whereIn('status', ['pending', 'approved'])
            ->groupStart()
                ->where('start_time <=', $request->getPost('end_time'))
                ->where('end_time >=', $request->getPost('start_time'))
            ->groupEnd()
            ->first();

        if ($conflict) {
            return redirect()->back()->withInput()
                ->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $eTicketCode = 'SK' . strtoupper(uniqid()) . $userId;

        $data = [
            'user_id'          => $userId,
            'resource_id'      => $request->getPost('resource_id'),
            'pc_number'        => $request->getPost('pcs') ?: null,
            'reservation_date' => $request->getPost('reservation_date'),
            'start_time'       => $request->getPost('start_time'),
            'end_time'         => $request->getPost('end_time'),
            'purpose'          => $request->getPost('purpose'),
            'status'           => 'pending',
            'e_ticket_code'    => $eTicketCode,
            'created_at'       => date('Y-m-d H:i:s'),
            'visitor_type'     => 'User',
            'visitor_name'     => $session->get('name'),
            'user_email'       => $session->get('email'),
        ];

        if ($reservationModel->insert($data)) {
            $reservationId = $reservationModel->insertID();
            return redirect()->to('/reservation/success/' . $reservationId)
                ->with('success', 'Reservation created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create reservation. Please try again.');
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

        if ($request->isAJAX()) {
            $resourceId = $request->getPost('resource_id');
            $date       = $request->getPost('date');
            $startTime  = $request->getPost('start_time');
            $endTime    = $request->getPost('end_time');

            $reservationModel = new ReservationModel();
            $conflicts        = $reservationModel
                ->where('resource_id', $resourceId)
                ->where('reservation_date', $date)
                ->whereIn('status', ['pending', 'approved'])
                ->groupStart()
                    ->where('start_time <=', $endTime)
                    ->where('end_time >=', $startTime)
                ->groupEnd()
                ->findAll();

            return $this->response->setJSON(
                empty($conflicts)
                    ? ['available' => true,  'message' => '✓ This time slot is available']
                    : ['available' => false, 'message' => '✗ This time slot is already booked']
            );
        }
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