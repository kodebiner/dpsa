<?php namespace App\Models;

use CodeIgniter\Model;

class MdlModel extends Model
{
    protected $allowedFields = [
        'name', 'length', 'width', 'height', 'volume', 'denomination', 'price',
    ];
    protected $table            = 'mdl';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}