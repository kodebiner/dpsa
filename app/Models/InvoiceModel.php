<?php namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $allowedFields = [
        'projectid', 'jatuhtempo', 'referensi', 'pph23', 'email', 'status', 'created_at', 'updated_at', 'deleted_at',
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