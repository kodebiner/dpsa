<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $allowedFields = [
        'clientid', 'name', 'status', 'marketing', 'sph', 'spk', 'no_spk', 'status_spk', 'batas_produksi', 'tanggal_spk', 'type_design', 'ded','no_sph', 'tahun', 'inv1', 'inv2', 'inv3', 'inv4', 'created_at', 'updated_at', 'deleted_at',
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