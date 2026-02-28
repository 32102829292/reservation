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

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $resourceModel = new ResourceModel();
        $resources = $resourceModel->orderBy('name', 'ASC')->findAll();

        $db = db_connect();
        $pcs = [];
        if ($db->tableExists('pcs')) {
            $pcs = $db->table('pcs')
                ->select('pc_number')
                ->where('is_available', 1)
                ->orderBy('pc_number', 'ASC')
                ->get()
                ->getResultArray();
        }

        if (empty($pcs)) {
            for ($i = 1; $i <= 10; $i++) {
                $pcs[] = ['pc_number' => 'PC' . str_pad($i, 2, '0', STR_PAD_LEFT)];
            }
        }

        $purposes = ['Work', 'Personal', 'Study', 'SK Activity', 'Others'];

        return view('user/reservation', [
            'page' => 'reservation',
            'user' => $user,
            'user_name' => $user['name'] ?? '',
            'resources' => $resources,
            'pcs' => $pcs,
            'purposes' => $purposes
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

        $reservationModel = new ReservationModel();
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

        $data = [
            'user_id' => $userId,
            'resource_id' => $request->getPost('resource_id'),
            'reservation_date' => $request->getPost('reservation_date'),
            'start_time' => $request->getPost('start_time'),
            'end_time' => $request->getPost('end_time'),
            'purpose' => $request->getPost('purpose'),
            'pcs' => $request->getPost('pcs') ?: null,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Save to database
        if ($reservationModel->insert($data)) {
            $reservationId = $reservationModel->insertID();

            return redirect()->to('/reservation/success/' . $reservationId)
                ->with('success', 'Reservation created successfully!');
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
        $reservations = $reservationModel
            ->select('reservations.*, resources.name as resource_name')
            ->join('resources', 'resources.id = reservations.resource_id', 'left')
            ->where('reservations.user_id', $userId)
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll();

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
