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
        $db = \Config\Database::connect();

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

        // ── Books data ────────────────────────────────────────────────────
        $allBooks = $db->table('books')
            ->select('id, title, author, genre, available_copies, total_copies, preface')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $data['dashBooks']      = $allBooks;
        $data['bookTotalCount'] = count($allBooks);
        $data['bookAvailCount'] = count(array_filter(
            $allBooks,
            fn($b) => (int)($b['available_copies'] ?? 0) > 0
        ));

        // Pending borrow requests with book title + resident name
        $data['dashBorrowReqs'] = $db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.created_at,
                      b.title AS book_title,
                      COALESCE(NULLIF(u.full_name, ""), u.name) AS resident_name')
            ->join('books b', 'b.id = bb.book_id')
            ->join('users u', 'u.id = bb.user_id')
            ->where('bb.status', 'pending')
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        $data['pendingBorrowings'] = count($data['dashBorrowReqs']);
        // ─────────────────────────────────────────────────────────────────

        $data['page'] = 'sk-dashboard';

        return view('sk/dashboard', $data);
    }
}