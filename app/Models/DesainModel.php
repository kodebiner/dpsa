<?php namespace App\Models;

use CodeIgniter\Model;

class DesainModel extends Model
{
    protected $allowedFields = [
        'proyekid', 'photo', 'status',
    ];
    protected $table            = 'Desain';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}