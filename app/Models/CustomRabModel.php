<?php namespace App\Models;

use CodeIgniter\Model;

class CustomRabModel extends Model
{
    protected $allowedFields = [
        'projectid', 'name', 'length', 'width', 'height', 'volume', 'denomination', 'price', 'qty',
    ];
    protected $table            = 'customrab';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}