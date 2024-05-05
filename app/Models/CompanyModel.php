<?php namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $allowedFields = [
        'rsname', 'rscode', 'ptname', 'address', 'npwp', 'phone', 'bank', 'no_rek', 'pic', 'parentid', 'status', 'created_at', 'updated_at', 'deleted_at',
    ];
    
    protected $useAutoIncrement = true;
    protected $table            = 'company';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}