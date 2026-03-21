<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * BookController
 *
 * Tables:
 *   books           — id, title, author, genre, preface, isbn, published_year,
 *                     call_number, cover_image, total_copies, available_copies, status
 *   book_borrowings — id, book_id, user_id, status, borrowed_at, due_date,
 *                     returned_at, notes, created_at, updated_at
 */
class BookController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ══════════════════════════════════════════════════
    //  RESIDENT — browse catalog + my borrowings
    // ══════════════════════════════════════════════════

    public function index()
    {
        $userId = session()->get('user_id');

        $books = $this->db->table('books')
            ->select('id, title, author, genre, preface, isbn, published_year,
                      call_number, cover_image, total_copies, available_copies, status')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $genres = array_values(array_unique(array_filter(array_column($books, 'genre'))));

        $myBorrowings = $this->db->table('book_borrowings bb')
            ->select('bb.id, bb.status, bb.borrowed_at, bb.due_date, bb.returned_at, bb.notes,
                      b.title, b.author')
            ->join('books b', 'b.id = bb.book_id')
            ->where('bb.user_id', $userId)
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        return view('user/books', [
            'books'        => $books,
            'genres'       => $genres,
            'myBorrowings' => $myBorrowings,
        ]);
    }

    public function borrow($bookId)
    {
        $userId = session()->get('user_id');

        $book = $this->db->table('books')
            ->where('id', $bookId)
            ->where('status', 'active')
            ->get()->getRowArray();

        if (!$book) {
            return redirect()->to('/books')->with('error', 'Book not found.');
        }

        if ((int)$book['available_copies'] < 1) {
            return redirect()->to('/books')->with('error', 'Sorry, no copies are currently available.');
        }

        $existing = $this->db->table('book_borrowings')
            ->where('book_id', $bookId)
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->get()->getRowArray();

        if ($existing) {
            return redirect()->to('/books')->with('error', 'You already have an active borrow request for this book.');
        }

        $this->db->table('book_borrowings')->insert([
            'book_id'     => $bookId,
            'user_id'     => $userId,
            'status'      => 'pending',
            'borrowed_at' => date('Y-m-d H:i:s'),
            'due_date'    => date('Y-m-d', strtotime('+14 days')),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/books')->with('success', 'Borrow request submitted! You will be notified once approved.');
    }

    public function myBorrowings()
    {
        return $this->index();
    }

    // ══════════════════════════════════════════════════
    //  SK / ADMIN — manage books catalog
    // ══════════════════════════════════════════════════

    private function getManageData(): array
    {
        $books = $this->db->table('books')
            ->select('id, title, author, genre, preface, isbn, published_year,
                      call_number, cover_image, total_copies, available_copies, status, created_at')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $borrowings = $this->db->table('book_borrowings bb')
            ->select("
                bb.id,
                bb.status,
                bb.borrowed_at,
                bb.due_date,
                bb.returned_at,
                bb.notes,
                bb.created_at,
                b.title  AS book_title,
                b.author AS book_author,
                COALESCE(NULLIF(u.full_name, ''), u.name) AS resident_name,
                u.email
            ")
            ->join('books b', 'b.id = bb.book_id')
            ->join('users u', 'u.id = bb.user_id')
            ->orderBy('bb.created_at', 'DESC')
            ->get()->getResultArray();

        return compact('books', 'borrowings');
    }

    public function manage()
    {
        $data = $this->getManageData();
        $view = $this->isAdmin() ? 'admin/books' : 'sk/books';
        return view($view, $data);
    }

    public function borrowings()
    {
        return $this->manage();
    }

    // ── CRUD ──────────────────────────────────────────

    public function create()
    {
        $view = $this->isAdmin() ? 'admin/books_form' : 'sk/books_form';
        return view($view, ['book' => null, 'isEdit' => false]);
    }

    public function store()
    {
        $totalCopies = (int)($this->request->getPost('total_copies') ?: 1);

        $this->db->table('books')->insert([
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'genre'            => $this->request->getPost('genre'),
            'preface'          => $this->request->getPost('preface'),
            'isbn'             => $this->request->getPost('isbn'),
            'published_year'   => $this->request->getPost('published_year') ?: null,
            'call_number'      => $this->request->getPost('call_number') ?: null,   // ★
            'total_copies'     => $totalCopies,
            'available_copies' => $totalCopies,
            'status'           => 'active',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        $prefix = $this->isAdmin() ? '/admin' : '/sk';
        return redirect()->to($prefix . '/books')->with('success', 'Book added successfully.');
    }

    public function edit($bookId)
    {
        $book = $this->db->table('books')->where('id', $bookId)->get()->getRowArray();

        if (!$book) {
            return redirect()->back()->with('error', 'Book not found.');
        }

        $view = $this->isAdmin() ? 'admin/books_form' : 'sk/books_form';
        return view($view, ['book' => $book, 'isEdit' => true]);
    }

    public function update($bookId)
    {
        $book = $this->db->table('books')->where('id', $bookId)->get()->getRowArray();

        if (!$book) {
            return redirect()->back()->with('error', 'Book not found.');
        }

        $data = [
            'title'          => $this->request->getPost('title')          ?? $book['title'],
            'author'         => $this->request->getPost('author')         ?? $book['author'],
            'genre'          => $this->request->getPost('genre')          ?? $book['genre'],
            'preface'        => $this->request->getPost('preface')        ?? $book['preface'],
            'isbn'           => $this->request->getPost('isbn')           ?? $book['isbn'],
            'published_year' => $this->request->getPost('published_year') ?? $book['published_year'],
            'call_number'    => $this->request->getPost('call_number')    ?? $book['call_number'] ?? null,  // ★
            'status'         => $this->request->getPost('status')         ?? $book['status'],
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if ($this->request->getPost('total_copies') !== null) {
            $newTotal                 = (int)$this->request->getPost('total_copies');
            $diff                     = $newTotal - (int)$book['total_copies'];
            $data['total_copies']     = $newTotal;
            $data['available_copies'] = max(0, (int)$book['available_copies'] + $diff);
        }

        $this->db->table('books')->where('id', $bookId)->update($data);

        $prefix = $this->isAdmin() ? '/admin' : '/sk';
        return redirect()->to($prefix . '/books')->with('success', 'Book updated successfully.');
    }

    public function delete($bookId)
    {
        $this->db->table('books')->where('id', $bookId)->delete();

        $prefix = $this->isAdmin() ? '/admin' : '/sk';
        return redirect()->to($prefix . '/books')->with('success', 'Book deleted successfully.');
    }

    // ★ Inline copies editor — AJAX endpoint
    public function updateCopies($bookId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }

        $data = $this->request->getJSON(true) ?? [];
        $val  = max(0, (int)($data['available_copies'] ?? 0));

        $book = $this->db->table('books')->where('id', $bookId)->get()->getRowArray();
        if (!$book) {
            return $this->response->setStatusCode(404)
                ->setJSON(['ok' => false, 'error' => 'Book not found']);
        }

        // Clamp to total_copies so available never exceeds total
        $val = min($val, (int)($book['total_copies'] ?? $val));

        $this->db->table('books')->where('id', $bookId)->update([
            'available_copies' => $val,
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return $this->response
            ->setHeader('X-CSRF-TOKEN', csrf_hash())   // ★ CSRF refresh
            ->setJSON(['ok' => true, 'available_copies' => $val]);
    }

    // ══════════════════════════════════════════════════
    //  BORROWING ACTIONS
    // ══════════════════════════════════════════════════

    public function approveBorrowing($borrowingId)
    {
        $borrowing = $this->db->table('book_borrowings')
            ->where('id', $borrowingId)
            ->where('status', 'pending')
            ->get()->getRowArray();

        if (!$borrowing) {
            return redirect()->back()->with('error', 'Request not found or already processed.');
        }

        $this->db->table('book_borrowings')->where('id', $borrowingId)->update([
            'status'     => 'approved',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->query(
            'UPDATE books SET available_copies = GREATEST(0, available_copies - 1),
             updated_at = NOW() WHERE id = ?',
            [$borrowing['book_id']]
        );

        return redirect()->back()->with('success', 'Borrow request approved.');
    }

    public function returnBook($borrowingId)
    {
        $borrowing = $this->db->table('book_borrowings')
            ->where('id', $borrowingId)
            ->where('status', 'approved')
            ->get()->getRowArray();

        if (!$borrowing) {
            return redirect()->back()->with('error', 'Request not found or not approved.');
        }

        $this->db->table('book_borrowings')->where('id', $borrowingId)->update([
            'status'      => 'returned',
            'returned_at' => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->db->query(
            'UPDATE books SET available_copies = LEAST(total_copies, available_copies + 1),
             updated_at = NOW() WHERE id = ?',
            [$borrowing['book_id']]
        );

        return redirect()->back()->with('success', 'Book marked as returned.');
    }

    public function rejectBorrowing($borrowingId)
    {
        $borrowing = $this->db->table('book_borrowings')
            ->where('id', $borrowingId)
            ->where('status', 'pending')
            ->get()->getRowArray();

        if (!$borrowing) {
            return redirect()->back()->with('error', 'Request not found or already processed.');
        }

        $this->db->table('book_borrowings')->where('id', $borrowingId)->update([
            'status'     => 'rejected',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Borrow request rejected.');
    }

    // ── Helper ────────────────────────────────────────

    private function isAdmin(): bool
    {
        return (session()->get('role') ?? '') === 'chairman';
    }
}