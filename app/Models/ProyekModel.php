<?php namespace App\Models;

use CodeIgniter\Model;

class ProyekModel extends Model
{
    protected $allowedFields = [
        'clientid', 'brief', 'name', 'created', 'updated_at', 'deleted_at',
    ];
    protected $table            = 'proyek';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $returnType       = 'array';
}