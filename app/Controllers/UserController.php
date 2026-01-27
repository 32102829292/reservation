<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ReservationModel;

class UserController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $userId = $session->get('user_id');

        $userModel = new UserModel();
        $reservationModel = new ReservationModel();

        $user = $userModel->find($userId);
        if (!$user) {
            $session->destroy();
            return redirect()->to('/login');
        }

        $reservations = $reservationModel->getUserReservations($userId);

        return view('user/dashboard', [
            'user' => $user,
            'reservations' => $reservations,
            'page' => 'dashboard'
        ]);
    }

    public function reservationList()
{
    $session = session();
    if (!$session->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    $userId = $session->get('user_id');
    $reservationModel = new ReservationModel();

    $reservations = $reservationModel
        ->where('user_id', $userId)
        ->orderBy('created_at', 'DESC')
        ->findAll();

    return view('user/reservation-list', [
        'reservations' => $reservations,
        'page' => 'reservation-list'
    ]);
}

public function profile()
{
    $session = session();
    if (!$session->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    $userId = $session->get('user_id');
    $userModel = new UserModel();
    $user = $userModel->find($userId);

    return view('user/profile', [
        'user' => $user,
        'page' => 'profile'
    ]);
}

public function updateProfile()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        $request = $this->request;

        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'phone' => $request->getPost('phone'),
        ];

        $password = $request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel = new UserModel();
        $userModel->update($userId, $data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


    public function eTicket()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('user/eticket', ['page' => 'etickets']);
    }

    public function ticket()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('user/ticket', ['page' => 'etickets']);
    }
}
