<?php
namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table         = 'books';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'title', 'author', 'genre', 'preface',
        'published_year', 'quantity', 'available',
        'status', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = false;

    public function search(string $keyword): array
    {
        if (!$keyword) return $this->orderBy('title', 'ASC')->findAll();

        return $this->db->query("
            SELECT *, MATCH(title, author, preface) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance
            FROM books
            WHERE MATCH(title, author, preface) AGAINST(? IN NATURAL LANGUAGE MODE)
            ORDER BY relevance DESC
        ", [$keyword, $keyword])->getResultArray();
    }

    public function filterByGenre(string $genre): array
    {
        return $this->where('genre', $genre)->orderBy('title', 'ASC')->findAll();
    }

    public function getGenres(): array
    {
        return $this->db->query("SELECT DISTINCT genre FROM books ORDER BY genre ASC")->getResultArray();
    }
}