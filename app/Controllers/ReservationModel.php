<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'full_name',
        'resource',
        'reservation_date',
        'reservation_time',
        'duration',
        'pc_number',
        'status',
        'created_at'
    ];

    protected $useTimestamps = false;
}
