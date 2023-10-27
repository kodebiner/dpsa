<?php namespace App\Models;

use CodeIgniter\Model;

class MdlModel extends Model
{
    protected $allowedFields = [
        'name', 'price',
    ];
    protected $table            = 'Mdl';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}