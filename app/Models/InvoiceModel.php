<?php namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $allowedFields = [
        'projectid','file', 'jatuhtempo', 'referensi', 'pph23', 'email', 'status', 'pic', 'no_inv', 'tahun', 'created_at', 'updated_at', 'deleted_at',
    ];
    protected $table            = 'invoice';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}