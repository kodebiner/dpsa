<?php namespace App\Models;

use CodeIgniter\Model;

class PurchaseDetailModel extends Model
{
    protected $allowedFields = [
        'mdlid', 'paketid', 'qty',
    ];
    
    protected $table            = 'purchaseorderdetail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
}