<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\ResourceModel;
use App\Models\UserModel;

class Reservation extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        // Get user data
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $resourceModel = new ResourceModel();
        $resources = $resourceModel->orderBy('name', 'ASC')->findAll();

        $db = db_connect();
        $pcs = [];
        
        if ($db->tableExists('pcs')) {
            $pcs = $db->table('pcs')
                     ->select('pc_number')
                     ->where('status', 'available')
                     ->orderBy('pc_number', 'ASC')
                     ->get()
                     ->getResultArray();
        }
        $purposes = ['Work', 'Personal', 'Study', 'SK Activity', 'Others'];
        
        if ($db->tableExists('purposes')) {
            $purposeResults = $db->table('purposes')
                                ->select('name')
                                ->where('is_active', 1)
                                ->orderBy('name', 'ASC')
                                ->get()
                                ->getResultArray();
            
            if (!empty($purposeResults)) {
                $purposes = array_column($purposeResults, 'name');
            }
        }

        $reservationModel = new ReservationModel();
        $remainingReservations = $reservationModel->getRemainingReservations($userId);

        $isBlocked = $reservationModel->isBlocked($userId);

        return view('user/reservation', [
            'page' => 'reservation',
            'user' => $user,
            'user_name' => $user['name'] ?? '',
            'resources' => $resources,
            'pcs' => $pcs,
            'purposes' => $purposes,
            'remainingReservations' => $remainingReservations,
            'isBlocked' => $isBlocked
        ]);
    }

    public function reserve()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $request = $this->request;
        $reservationModel = new ReservationModel();

        $isBlocked = $reservationModel->isBlocked($userId);
        if ($isBlocked) {
            return redirect()->back()
                           ->with('error', 'You are currently blocked from making reservations.');
        }

        $fairness = $reservationModel->checkFairness($userId);
        if (!$fairness['fair']) {
            $message = isset($fairness['blocked']) 
                ? 'You have reached the maximum of 3 reservations in a 2-week period. Blocked until ' . $fairness['until']
                : 'You have reached the maximum of 3 reservations in a 2-week period.';
            return redirect()->back()
                           ->with('error', $message);
        }

        $rules = [
            'resource_id' => 'required|numeric',
            'reservation_date' => 'required|valid_date[Y-m-d]',
            'start_time' => 'required',
            'end_time' => 'required',
            'purpose' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Please fill all required fields');
        }

        $sameDayReservations = $reservationModel->getUserSameDayReservations(
            $userId, 
            $request->getPost('reservation_date')
        );
        
        if (!empty($sameDayReservations)) {
            return redirect()->back()
                           ->withInput()
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
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $eTicketCode = 'SK' . strtoupper(uniqid()) . $userId;

        $data = [
            'user_id' => $userId,
            'resource_id' => $request->getPost('resource_id'),
            'pc_number' => $request->getPost('pcs') ?: null,
            'reservation_date' => $request->getPost('reservation_date'),
            'start_time' => $request->getPost('start_time'),
            'end_time' => $request->getPost('end_time'),
            'purpose' => $request->getPost('purpose'),
            'status' => 'pending',
            'e_ticket_code' => $eTicketCode,
            'qr_used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'visitor_type' => 'User',
            'visitor_name' => $session->get('name'),
            'user_email' => $session->get('email')
        ];

        if ($reservationModel->insert($data)) {
            $reservationId = $reservationModel->insertID();
            
            return redirect()->to('/reservation/success/' . $reservationId)
                           ->with('success', 'Reservation created successfully! Your e-ticket code is: ' . $eTicketCode);
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create reservation. Please try again.');
        }
    }

    public function checkAvailability()
    {
        $request = $this->request;
        
        if ($request->isAJAX()) {
            $resourceId = $request->getPost('resource_id');
            $date = $request->getPost('date');
            $startTime = $request->getPost('start_time');
            $endTime = $request->getPost('end_time');
            
            $reservationModel = new ReservationModel();
            
            $conflicts = $reservationModel
                ->where('resource_id', $resourceId)
                ->where('reservation_date', $date)
                ->whereIn('status', ['pending', 'approved'])
                ->groupStart()
                    ->where('start_time <=', $endTime)
                    ->where('end_time >=', $startTime)
                ->groupEnd()
                ->findAll();
            
            if (empty($conflicts)) {
                return $this->response->setJSON([
                    'available' => true, 
                    'message' => '✓ This time slot is available'
                ]);
            } else {
                return $this->response->setJSON([
                    'available' => false, 
                    'message' => '✗ This time slot is already booked'
                ]);
            }
        }
    }

    public function reservationList()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $reservationModel = new ReservationModel();
        $reservations = $reservationModel->getUserReservations($userId);

        return view('user/reservation_list', [
            'page' => 'reservation-list',
            'reservations' => $reservations
        ]);
    }

    public function success($id)
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $reservationModel = new ReservationModel();
        $reservation = $reservationModel
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->where('reservations.id', $id)
            ->where('reservations.user_id', $userId)
            ->first();

        if (!$reservation) {
            return redirect()->to('/reservation-list')
                           ->with('error', 'Reservation not found');
        }

        return view('user/reservation_success', [
            'page' => 'reservation',
            'reservation' => $reservation
        ]);
    }
}