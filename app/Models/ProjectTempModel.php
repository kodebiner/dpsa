<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectTempModel extends Model
{
    protected $allowedFields = [
        'clientid', 'brief', 'name', 'status', 'production',
    ];
    
    protected $useAutoIncrement = true;
    protected $table            = 'temp_project';
    protected $primaryKey       = 'id';
}