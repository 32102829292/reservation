<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BookBorrowingModel;

/**
 * BookController
 *
 * Refactored to use BookBorrowingModel for all borrowing queries/writes.
 * Raw $this->db calls remain only for the books table (no BookModel yet).
 */
class BookController extends Controller
{
    protected $db;
    protected BookBorrowingModel $borrowingModel;

    public function __construct()
    {
        $this->db             = \Config\Database::connect();
        $this->borrowingModel = new BookBorrowingModel();
    }

    // ── Helpers ───────────────────────────────────────

    private function isAdmin(): bool
    {
        return (session()->get('role') ?? '') === 'chairman';
    }

    private function skPrefix(): string
    {
        return $this->isAdmin() ? '/admin' : '/sk';
    }

    // ══════════════════════════════════════════════════
    //  RESIDENT — browse catalog + my borrowings
    // ══════════════════════════════════════════════════

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $books = $this->db->table('books')
            ->select('id, title, author, genre, preface, isbn, published_year,
                      call_number, cover_image, total_copies, available_copies, status')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $genres = array_values(array_unique(array_filter(array_column($books, 'genre'))));

        return view('user/books', [
            'books'        => $books,
            'genres'       => $genres,
            'myBorrowings' => $this->borrowingModel->getUserBorrowings($userId),
        ]);
    }

    public function borrow($bookId)
    {
        $userId = (int) session()->get('user_id');

        $book = $this->db->table('books')
            ->where('id', $bookId)
            ->where('status', 'active')
            ->get()->getRowArray();

        if (!$book) {
            return redirect()->to('/books')->with('error', 'Book not found.');
        }

        if ((int) $book['available_copies'] < 1) {
            return redirect()->to('/books')->with('error', 'Sorry, no copies are currently available.');
        }

        if ($this->borrowingModel->hasPendingBorrowing($userId, (int) $bookId)) {
            return redirect()->to('/books')->with('error', 'You already have an active borrow request for this book.');
        }

        $this->borrowingModel->createRequest((int) $bookId, $userId);

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

        return [
            'books'      => $books,
            'borrowings' => $this->borrowingModel->getWithDetails(),
        ];
    }

    public function manage()
    {
        $view = $this->isAdmin() ? 'admin/books' : 'sk/books';
        return view($view, $this->getManageData());
    }

    public function borrowings()
    {
        return $this->manage();
    }

    // ── CRUD ──────────────────────────────────────────

    public function store()
    {
        $totalCopies = (int) ($this->request->getPost('total_copies') ?: 1);

        $this->db->table('books')->insert([
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'genre'            => $this->request->getPost('genre'),
            'preface'          => $this->request->getPost('preface'),
            'isbn'             => $this->request->getPost('isbn'),
            'published_year'   => $this->request->getPost('published_year') ?: null,
            'call_number'      => $this->request->getPost('call_number') ?: null,
            'total_copies'     => $totalCopies,
            'available_copies' => $totalCopies,
            'status'           => 'active',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to($this->skPrefix() . '/books')->with('success', 'Book added successfully.');
    }

    public function update($bookId)
    {
        $book = $this->db->table('books')->where('id', $bookId)->get()->getRowArray();

        if (!$book) {
            return redirect()->to($this->skPrefix() . '/books')->with('error', 'Book not found.');
        }

        $data = [
            'title'          => $this->request->getPost('title')          ?? $book['title'],
            'author'         => $this->request->getPost('author')         ?? $book['author'],
            'genre'          => $this->request->getPost('genre')          ?? $book['genre'],
            'preface'        => $this->request->getPost('preface')        ?? $book['preface'],
            'isbn'           => $this->request->getPost('isbn')           ?? $book['isbn'],
            'published_year' => $this->request->getPost('published_year') ?? $book['published_year'],
            'call_number'    => $this->request->getPost('call_number')    ?? $book['call_number'] ?? null,
            'status'         => $this->request->getPost('status')         ?? $book['status'],
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if ($this->request->getPost('total_copies') !== null) {
            $newTotal                 = (int) $this->request->getPost('total_copies');
            $diff                     = $newTotal - (int) $book['total_copies'];
            $data['total_copies']     = $newTotal;
            $data['available_copies'] = max(0, (int) $book['available_copies'] + $diff);
        }

        $this->db->table('books')->where('id', $bookId)->update($data);

        return redirect()->to($this->skPrefix() . '/books')->with('success', 'Book updated successfully.');
    }

    public function delete($bookId)
    {
        $this->db->table('books')->where('id', $bookId)->delete();

        return redirect()->to($this->skPrefix() . '/books')->with('success', 'Book deleted successfully.');
    }

    // ── Inline copies editor — AJAX ───────────────────

    public function updateCopies($bookId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }

        $data = $this->request->getJSON(true) ?? [];
        $val  = max(0, (int) ($data['available_copies'] ?? 0));

        $book = $this->db->table('books')->where('id', $bookId)->get()->getRowArray();
        if (!$book) {
            return $this->response->setStatusCode(404)
                ->setJSON(['ok' => false, 'error' => 'Book not found']);
        }

        $val = min($val, (int) ($book['total_copies'] ?? $val));

        $this->db->table('books')->where('id', $bookId)->update([
            'available_copies' => $val,
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        return $this->response
            ->setHeader('X-CSRF-TOKEN', csrf_hash())
            ->setJSON(['ok' => true, 'available_copies' => $val]);
    }

    // ── PDF / AI extraction — AJAX ────────────────────

    public function extractPdf(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!session()->has('user_id')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }

        $body    = $this->request->getJSON(true) ?? [];
        $pdfText = trim($body['pdf_text'] ?? '');

        if (strlen($pdfText) < 20) {
            return $this->response->setJSON(['ok' => false, 'error' => 'No readable text provided.']);
        }

        $pdfText = mb_substr($pdfText, 0, 6000);

        $apiKey = env('ANTHROPIC_API_KEY');
        if (!$apiKey) {
            return $this->response->setJSON(['ok' => false, 'error' => 'AI service not configured.']);
        }

        $prompt = <<<PROMPT
Extract bibliographic details from the following book text and return ONLY a JSON object
with these keys (use null for anything you cannot find):
title, author, genre, published_year, isbn, call_number, preface

Rules:
- published_year must be a 4-digit integer or null
- isbn must be digits/hyphens only or null
- preface is a 2-3 sentence summary of what the book is about
- Return ONLY the JSON object, no markdown fences, no extra text

TEXT:
{$pdfText}
PROMPT;

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 512,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]),
            CURLOPT_TIMEOUT => 30,
        ]);

        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Network error: ' . $err]);
        }

        $apiResp = json_decode($raw, true);
        $text    = $apiResp['content'][0]['text'] ?? '';

        $text      = preg_replace('/^```[a-z]*\s*/i', '', trim($text));
        $text      = preg_replace('/\s*```$/', '', $text);
        $extracted = json_decode(trim($text), true);

        if (!is_array($extracted)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'AI returned unexpected format.']);
        }

        return $this->response
            ->setHeader('X-CSRF-TOKEN', csrf_hash())
            ->setJSON(['ok' => true, 'data' => $extracted]);
    }

    // ══════════════════════════════════════════════════
    //  BORROWING ACTIONS
    //  All use findForAction() from the model — no more
    //  duplicated where('id')->where('status') chains.
    // ══════════════════════════════════════════════════

    public function approveBorrowing($borrowingId)
    {
        $borrowing = $this->borrowingModel->findForAction((int) $borrowingId, 'pending');

        if (!$borrowing) {
            return redirect()->to($this->skPrefix() . '/books#borrowings')
                ->with('error', 'This request was already processed or could not be found.');
        }

        $this->borrowingModel->approve((int) $borrowingId);

        $this->db->query(
            'UPDATE books SET available_copies = GREATEST(0, available_copies - 1),
             updated_at = NOW() WHERE id = ?',
            [$borrowing['book_id']]
        );

        return redirect()->to($this->skPrefix() . '/books#borrowings')
            ->with('success', 'Borrow request approved.');
    }

    public function returnBook($borrowingId)
    {
        $borrowing = $this->borrowingModel->findForAction((int) $borrowingId, 'approved');

        if (!$borrowing) {
            return redirect()->to($this->skPrefix() . '/books#borrowings')
                ->with('error', 'This book has already been returned or the request could not be found.');
        }

        $this->borrowingModel->markReturned((int) $borrowingId);

        $this->db->query(
            'UPDATE books SET available_copies = LEAST(total_copies, available_copies + 1),
             updated_at = NOW() WHERE id = ?',
            [$borrowing['book_id']]
        );

        return redirect()->to($this->skPrefix() . '/books#borrowings')
            ->with('success', 'Book marked as returned.');
    }

    public function rejectBorrowing($borrowingId)
    {
        $borrowing = $this->borrowingModel->findForAction((int) $borrowingId, 'pending');

        if (!$borrowing) {
            return redirect()->to($this->skPrefix() . '/books#borrowings')
                ->with('error', 'This request was already processed or could not be found.');
        }

        $this->borrowingModel->reject((int) $borrowingId);

        return redirect()->to($this->skPrefix() . '/books#borrowings')
            ->with('success', 'Borrow request rejected.');
    }
}