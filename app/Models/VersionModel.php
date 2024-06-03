<?php namespace App\Models;

use CodeIgniter\Model;

class VersionModel extends Model
{
    protected $allowedFields = [
        'id','projectid', 'file', 'type',
    ];
    
    protected $table            = 'version';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}