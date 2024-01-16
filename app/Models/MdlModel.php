<?php namespace App\Models;

use CodeIgniter\Model;

class MdlModel extends Model
{
    protected $allowedFields = [
        'name', 'length', 'width', 'height', 'volume', 'denomination', 'price', 'paketid','keterangan', 'created_at', 'updated_at', 'deleted_at',
    ];
    protected $table            = 'mdl';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}