<?php namespace App\Models;

use CodeIgniter\Model;

class RabModel extends Model
{
    protected $allowedFields = [
        'mdlid', 'proyekid', 'qty_completed', 'qty_delivered', 'qty',
    ];
    protected $table            = 'Rab';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}