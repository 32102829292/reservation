<?php
namespace App\Models;

use CodeIgniter\Model;

class PrintLogModel extends Model
{
    protected $table         = 'print_logs';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'reservation_id',
        'user_id',
        'printed',
        'pages',
        'printed_at',
        'printed_by',
        'e_ticket_code',
    ];
    protected $useTimestamps = false;

    public function getMapByReservation(): array
    {
        $rows = $this->findAll();
        $map  = [];
        foreach ($rows as $row) {
            $map[(int)$row['reservation_id']] = $row;
        }
        return $map;
    }

    public function upsert(int $reservationId, bool $printed, int $pages): bool
    {
        $existing = $this->where('reservation_id', $reservationId)->first();

        $data = [
            'reservation_id' => $reservationId,
            'printed'        => $printed,
            'pages'          => $pages,
            'printed_at'     => ($printed ? date('Y-m-d H:i:s') : null),
        ];

        if ($existing) {
            return $this->update($existing['id'], $data);
        }

        return (bool)$this->insert($data);
    }
}