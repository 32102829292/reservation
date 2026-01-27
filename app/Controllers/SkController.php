<?php

namespace App\Controllers;

use App\Models\ReservationModel;

class SkController extends BaseController
{
    public function dashboard()
    {
        return view('sk/dashboard');
    }

    public function reservations()
    {
        $model = new ReservationModel();

        $status = $this->request->getGet('status');
        $date   = $this->request->getGet('date');

        if ($status) {
            $model->where('status', $status);
        }

        if ($date) {
            $model->where('reservation_date', $date);
        }

        $data['reservations'] = $model
            ->orderBy('reservation_date', 'DESC')
            ->findAll();

        return view('sk/reservations', $data);
    }

    public function approve()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new ReservationModel())->update($id, ['status' => 'approved']);
        }
        return redirect()->to('/sk/reservations');
    }

    public function decline()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new ReservationModel())->update($id, ['status' => 'canceled']);
        }
        return redirect()->to('/sk/reservations');
    }

    public function newReservation()
    {
        $resourceModel = new \App\Models\ResourceModel();
        $resources = $resourceModel->findAll();

        return view('sk/new-reservation', [
            'resources' => $resources
        ]);
    }

    public function createReservation()
    {
        $request = $this->request;
        $reservationModel = new ReservationModel();

        $visitorType = $request->getPost('visitor_type');
        $userEmail = $request->getPost('user_email');
        $visitorName = $request->getPost('visitor_name');
        $visitorContactEmail = $request->getPost('visitor_contact_email');
        $reservationDate = $request->getPost('reservation_date');
        $reservationType = $request->getPost('reservation_type');
        $startTime = $request->getPost('start_time');
        $endTime = $request->getPost('end_time');
        $purpose = $request->getPost('purpose');
        $pcsJson = $request->getPost('pcs');
        $pcs = json_decode($pcsJson, true) ?? [];

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

        // Create reservation entry
        $data = [
            'user_id' => $userEmail,
            'visitor_type' => $visitorType,
            'visitor_name' => $visitorName,
            'visitor_email' => $visitorContactEmail,
            'reservation_date' => $reservationDate,
            'reservation_type' => $reservationType,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $purpose,
            'pc_numbers' => json_encode($pcs),
            'status' => 'pending',
            'e_ticket_code' => $eTicket,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $reservationModel->insert($data);

        if ($request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Reservation created successfully! AI Fairness Check passed.', 'e_ticket' => $eTicket]);
        }

        return redirect()->to('/sk/reservations')->with('success', 'Reservation created successfully! AI Fairness Check passed.');
    }

    private function generateETicket($length = 8) {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ticket = '';
        for ($i = 0; $i < $length; $i++) {
            $ticket .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $ticket;
    }

    public function profile()
    {
        $user = (new \App\Models\UserModel())->find(session()->get('user_id'));
        return view('sk/profile', ['user' => $user]);
    }

    public function scanner()
    {
        return view('sk/scanner');
    }

    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        $updateData = [
            'name' => $name,
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
        $model = new ReservationModel();
        $reservations = $model->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=sk_reservations.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'ID',
            'User ID',
            'Resource ID',
            'Date',
            'Start Time',
            'End Time',
            'Purpose',
            'Status'
        ]);

        foreach ($reservations as $r) {
            fputcsv($output, [
                $r['id'],
                $r['user_id'],
                $r['resource_id'],
                $r['reservation_date'],
                $r['start_time'],
                $r['end_time'],
                $r['purpose'],
                $r['status']
            ]);
        }

        fclose($output);
        exit;
    }
}
