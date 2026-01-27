<?php
namespace App\Models;
use CodeIgniter\Model;

class ResourceModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','description','quantity','status'];
}
