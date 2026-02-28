<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationPcModel extends Model
{
    protected $table = 'reservation_pcs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['reservation_id', 'pc_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;
}