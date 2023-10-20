<?php namespace App\Models;

use CodeIgniter\Model;

class BarModel extends Model
{
    protected $allowedFields = [
        'qty',
    ];
    protected $table            = 'bar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}