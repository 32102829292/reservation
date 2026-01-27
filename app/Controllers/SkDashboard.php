<?php

namespace App\Controllers;

use App\Models\ReservationModel;

class SkDashboard extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('isLoggedIn') || $session->get('role') !== 'sk') {
            return redirect()->to('/login');
        }

        $reservationModel = new ReservationModel();

        $data = [
            'total'    => (clone $reservationModel)->countAllResults(),
            'approved' => (clone $reservationModel)->where('status', 'approved')->countAllResults(),
            'pending'  => (clone $reservationModel)->where('status', 'pending')->countAllResults(),
            'claimed'  => (clone $reservationModel)->where('status', 'claimed')->countAllResults(),
        ];

        $data['reservations'] = $reservationModel
            ->select('
                reservations.*,
                users.name AS user_name,
                resources.resource_name
            ')
            ->join('users', 'users.id = reservations.user_id')
            ->join('resources', 'resources.id = reservations.resource_id')
            ->orderBy('reservation_date', 'ASC')
            ->orderBy('start_time', 'ASC')
            ->findAll();

        $data['page'] = 'sk-dashboard';

        return view('sk/dashboard', $data);
    }
}
