<?php namespace App\Models;

use CodeIgniter\Model;

class PurchaseDetailModel extends Model
{
    protected $allowedFields = [
        'id','clientid', 'purchaseid', 'mdlid', 'paketid', 'qty',
    ];
    
    protected $table            = 'purchaseorderdetail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}