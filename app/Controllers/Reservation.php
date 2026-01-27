<?php
namespace App\Controllers;
use App\Models\ReservationModel;
use CodeIgniter\Controller;

class Reservation extends Controller {
    protected $reservationModel;

    public function __construct() {
        $this->reservationModel = new ReservationModel();
    }

    private function generateETicket($length = 8) {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ticket = '';
        for ($i = 0; $i < $length; $i++) {
            $ticket .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $ticket;
    }

    private function aiFairnessCheck($userId, $resourceId, $reservationDate) {
        $reservations = $this->reservationModel->getUserReservations($userId);
        $penalty = 0;
        foreach($reservations as $r){
            if($r['resource_id'] == $resourceId && $r['reservation_date'] == $reservationDate){
                $penalty += 0.5;
            }
        }
        return max(0, 1 - $penalty);
    }

    public function index() {
        return view('user/reservation', ['page' => 'reservation']);
    }

    public function reserve() {
        $request = \Config\Services::request();
        $userId = session()->get('user_id');
        $resourceId = $request->getPost('resource_id');
        $pcNumber = $request->getPost('pc_number') ?? null;
        $reservationDate = $request->getPost('reservation_date');
        $startTime = $request->getPost('start_time');
        $endTime = $request->getPost('end_time');
        $purpose = $request->getPost('purpose');

        // Check blocked
        if($this->reservationModel->isBlocked($userId)){
            return $this->response->setJSON(['status'=>'error','message'=>'You are currently blocked.']);
        }

        // Check active
        if($this->reservationModel->countActiveReservations($userId) >= 3){
            $this->reservationModel->blockUser($userId, 14);
            return $this->response->setJSON(['status'=>'error','message'=>'Reached 3 reservations. Blocked for 2 weeks.']);
        }

        // AI fairness
        if($this->aiFairnessCheck($userId, $resourceId, $reservationDate) < 0.3){
            return $this->response->setJSON(['status'=>'error','message'=>'Reservation denied due to fairness.']);
        }

        // Insert reservation
        $eTicket = $this->generateETicket();
        $this->reservationModel->createReservation([
            'user_id' => $userId,
            'resource_id' => $resourceId,
            'pc_number' => $pcNumber,
            'reservation_date' => $reservationDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $purpose,
            'status' => 'pending',
            'e_ticket_code' => $eTicket
        ]);

        return $this->response->setJSON(['status'=>'success','message'=>'Reservation successful!','e_ticket'=>$eTicket]);
    }

    public function reservationList() {
        $userId = session()->get('user_id');
        $data['reservations'] = $this->reservationModel->getUserReservations($userId);
        $data['page'] = 'reservation-list';
        return view('user/reservation-list', $data);
    }
}
