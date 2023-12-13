<?php namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $allowedFields = [
        'uid', 'record', 'created_at', 'updated_at', 'deleted_at',
    ];
    protected $table            = 'logrecords';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}