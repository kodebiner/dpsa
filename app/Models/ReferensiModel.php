<?php namespace App\Models;

use CodeIgniter\Model;

class ReferensiModel extends Model
{
    protected $allowedFields = [
        'name', 'bank', 'no_rek',
    ];
    
    protected $table            = 'referensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}