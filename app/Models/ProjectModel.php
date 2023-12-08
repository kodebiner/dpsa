<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $allowedFields = [
        'clientid', 'brief', 'name', 'status', 'production', 'created_at', 'updated_at', 'deleted_at',
    ];
    
    protected $useAutoIncrement = true;
    protected $table            = 'project';
    protected $primaryKey       = 'id';
}