<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $allowedFields = [
        'clientid', 'brief', 'name', 'created_at', 'updated_at', 'deleted_at',
    ];
    
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = true;

    protected $table            = 'project';
    protected $primaryKey       = 'id';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $returnType       = 'array';
}