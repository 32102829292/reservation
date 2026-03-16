<?php
namespace App\Models;

use CodeIgniter\Model;

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
    protected $useTimestamps = false;

    public function getWithDetails(): array
    {
        return $this->db->query("
            SELECT bb.*, b.title, b.author, b.genre,
                   u.name as user_name, u.email as user_email
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            JOIN users u ON u.id = bb.user_id
            ORDER BY bb.created_at DESC
        ")->getResultArray();
    }

    public function getUserBorrowings(int $userId): array
    {
        return $this->db->query("
            SELECT bb.*, b.title, b.author, b.genre, b.published_year
            FROM book_borrowings bb
            JOIN books b ON b.id = bb.book_id
            WHERE bb.user_id = ?
            ORDER BY bb.created_at DESC
        ", [$userId])->getResultArray();
    }

    public function hasPendingBorrowing(int $userId, int $bookId): bool
    {
        $result = $this->where('user_id', $userId)
                       ->where('book_id', $bookId)
                       ->whereIn('status', ['pending', 'approved'])
                       ->first();
        return !empty($result);
    }
}