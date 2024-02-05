<?php namespace App\Models;

use CodeIgniter\Model;

class BastModel extends Model
{
    protected $allowedFields = [
        'projectid', 'file', 'status', 
    ];
    protected $table            = 'bast';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}