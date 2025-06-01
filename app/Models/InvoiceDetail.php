<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_purchase_order',
        'id_item',
        'quantity',
        'price',
        'total_price',
        'iva',
        'price_iva',
        'total_price_iva',
        'project_id',
        
    ];

    public function invoiceHeader()
    {
        return $this->belongsTo(InvoiceHeader::class, 'id_purchase_order', 'id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id');
    }
}
