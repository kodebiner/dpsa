<?php namespace App\Models;

use CodeIgniter\Model;

class RabModel extends Model
{
    protected $allowedFields = [
        'mdlid', 'projectid', 'qty_complete', 'qty_deliver', 'qty',
    ];
    protected $table            = 'Rab';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}