<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    protected $table = "purchase_order_details";

    protected $fillable = [
        'id_purchase_order',
        'id_item',
        'amount',
        'total_price'
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'id_purchase_order');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
