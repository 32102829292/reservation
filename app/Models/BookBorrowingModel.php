<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BookBorrowingModel
 *
 * Centralises all query logic for the book_borrowings table so that
 * BookController stays thin and both the SK and resident views share
 * the exact same SQL.
 *
 * Improvements over v1:
 *  1. getWithDetails() now uses COALESCE(full_name, name) to match the
 *     controller's inline query — consistent resident_name everywhere.
 *  2. Added findForAction() — fetches a single borrowing locked to an
 *     expected status, replacing the duplicated inline queries in
 *     approveBorrowing / returnBook / rejectBorrowing.
 *  3. Added getPendingCount() — handy for badge counters without loading
 *     the full dataset.
 *  4. Added getOverdue() — borrowings that are approved but past due_date,
 *     useful for a future overdue-notice feature.
 *  5. now() helper centralises date formatting so one change covers all rows.
 *  6. useTimestamps kept false (columns managed manually) but created_at /
 *     updated_at are always written through the model methods below, never
 *     left to the caller to remember.
 */
class BookBorrowingModel extends Model
{
    protected $table         = 'book_borrowings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'book_id', 'user_id', 'status',
        'borrowed_at', 'due_date', 'returned_at',
        'notes', 'created_at', 'updated_at',
    ];

    // Timestamps are set manually so we keep full control over the values.
    protected $useTimestamps = false;

    // ── Private helpers ───────────────────────────────

    private function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    // ══════════════════════════════════════════════════
    //  READ — multi-row queries
    // ══════════════════════════════════════════════════

    /**
     * All borrowings with joined book + user details.
     * Used by the SK / Admin manage page.
     *
     * Returns resident_name via COALESCE so it works whether the users
     * table has a full_name column, a name column, or both.
     */
    public function getWithDetails(): array
    {
        return $this->db->query("
            SELECT
                bb.id,
                bb.status,
                bb.borrowed_at,
                bb.due_date,
                bb.returned_at,
                bb.notes,
                bb.created_at,
                b.title      AS book_title,
                b.author     AS book_author,
                COALESCE(NULLIF(u.full_name, ''), u.name) AS resident_name,
                u.email
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            JOIN users u ON u.id = bb.user_id
            ORDER BY bb.created_at DESC
        ")->getResultArray();
    }

    /**
     * Borrowings for a single resident, with enough book info to render
     * the "My Borrowings" card list.
     */
    public function getUserBorrowings(int $userId): array
    {
        return $this->db->query("
            SELECT
                bb.id,
                bb.status,
                bb.borrowed_at,
                bb.due_date,
                bb.returned_at,
                bb.notes,
                b.title,
                b.author,
                b.genre,
                b.published_year
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            WHERE bb.user_id = ?
            ORDER BY bb.created_at DESC
        ", [$userId])->getResultArray();
    }

    /**
     * Borrowings filtered by status — used by the SK filter pills.
     */
    public function getByStatus(string $status): array
    {
        return $this->db->query("
            SELECT
                bb.id,
                bb.status,
                bb.borrowed_at,
                bb.due_date,
                bb.returned_at,
                bb.notes,
                bb.created_at,
                b.title      AS book_title,
                b.author     AS book_author,
                COALESCE(NULLIF(u.full_name, ''), u.name) AS resident_name,
                u.email
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            JOIN users u ON u.id = bb.user_id
            WHERE bb.status = ?
            ORDER BY bb.created_at DESC
        ", [$status])->getResultArray();
    }

    /**
     * Approved borrowings whose due_date has already passed.
     * Useful for an overdue-notice or dashboard warning.
     */
    public function getOverdue(): array
    {
        return $this->db->query("
            SELECT
                bb.id,
                bb.status,
                bb.borrowed_at,
                bb.due_date,
                bb.returned_at,
                b.title      AS book_title,
                b.author     AS book_author,
                COALESCE(NULLIF(u.full_name, ''), u.name) AS resident_name,
                u.email
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            JOIN users u ON u.id = bb.user_id
            WHERE bb.status = 'approved'
              AND bb.due_date < CURDATE()
            ORDER BY bb.due_date ASC
        ")->getResultArray();
    }

    // ══════════════════════════════════════════════════
    //  READ — single-row / scalar queries
    // ══════════════════════════════════════════════════

    /**
     * Fetch a single borrowing row only if its status matches the expected
     * value. Returns null when the row doesn't exist or the status is wrong.
     *
     * Replaces the three separate ->where('id')->where('status') blocks
     * that were duplicated across approveBorrowing / returnBook / rejectBorrowing.
     *
     * Usage:
     *   $borrowing = $this->borrowingModel->findForAction($id, 'pending');
     *   if (!$borrowing) { ...already processed... }
     */
    public function findForAction(int $borrowingId, string $expectedStatus): ?array
    {
        $row = $this->where('id', $borrowingId)
                    ->where('status', $expectedStatus)
                    ->first();

        return $row ?: null;
    }

    /**
     * Count of borrowings in a given status — used for badge counters
     * without pulling every column into memory.
     */
    public function getPendingCount(): int
    {
        return (int) $this->where('status', 'pending')->countAllResults();
    }

    /**
     * Returns true if the user already has an active (pending or approved)
     * borrowing for this book.
     */
    public function hasPendingBorrowing(int $userId, int $bookId): bool
    {
        return !empty(
            $this->where('user_id', $userId)
                 ->where('book_id', $bookId)
                 ->whereIn('status', ['pending', 'approved'])
                 ->first()
        );
    }

    // ══════════════════════════════════════════════════
    //  WRITE — borrowing lifecycle
    // ══════════════════════════════════════════════════

    /**
     * Create a new borrowing request.
     * Caller only needs to pass book_id and user_id; everything else
     * is defaulted here so the controller stays clean.
     */
    public function createRequest(int $bookId, int $userId, int $dueDays = 14): int|false
    {
        $now = $this->now();

        return $this->insert([
            'book_id'     => $bookId,
            'user_id'     => $userId,
            'status'      => 'pending',
            'borrowed_at' => $now,
            'due_date'    => date('Y-m-d', strtotime("+{$dueDays} days")),
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }

    /**
     * Mark a pending borrowing as approved.
     */
    public function approve(int $borrowingId): bool
    {
        return $this->update($borrowingId, [
            'status'     => 'approved',
            'updated_at' => $this->now(),
        ]);
    }

    /**
     * Mark an approved borrowing as returned.
     */
    public function markReturned(int $borrowingId): bool
    {
        return $this->update($borrowingId, [
            'status'      => 'returned',
            'returned_at' => $this->now(),
            'updated_at'  => $this->now(),
        ]);
    }

    /**
     * Mark a pending borrowing as rejected.
     */
    public function reject(int $borrowingId): bool
    {
        return $this->update($borrowingId, [
            'status'     => 'rejected',
            'updated_at' => $this->now(),
        ]);
    }
}