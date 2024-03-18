<?php namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $allowedFields = [
        'parentid', 'name', 'created_at', 'updated_at', 'deleted_at', 'ordering',
    ];
    
    protected $table            = 'paket';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}