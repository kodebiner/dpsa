<?php namespace App\Models;

use CodeIgniter\Model;

class CustomRabModel extends Model
{
    protected $allowedFields = [
        'projectid', 'name', 'price',
    ];
    protected $table            = 'customrab';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}