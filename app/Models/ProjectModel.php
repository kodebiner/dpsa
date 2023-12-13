<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $allowedFields = [
        'clientid', 'brief', 'name', 'status', 'production', 'created_at', 'updated_at', 'deleted_at',
    ];
    
    protected $table            = 'project';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}