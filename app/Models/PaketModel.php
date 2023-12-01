<?php namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $allowedFields = [
        'name',
    ];
    
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $table            = 'paket';
    protected $primaryKey       = 'id';
}