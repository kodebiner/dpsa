<?php namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $allowedFields = [
        'rsname', 'ptname', 'address', 'npwp', 'phone', 'parentid', 'status', 'created_at', 'updated_at', 'deleted_at',
    ];
    
    protected $useAutoIncrement = true;
    protected $table            = 'company';
    protected $primaryKey       = 'id';
}