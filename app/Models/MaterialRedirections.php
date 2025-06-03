<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRedirections extends Model
{
    use HasFactory;
    protected $table = 'material_redirections';
    protected $fillable = [
        'purchase_order_id',
        'invoice_detail_id',
        'chapter_id',
        'item_id',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(InvoiceHeader::class, 'purchase_order_id');
    }
    public function invoiceDetail()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_detail_id');
    }
    public function chapter()
    {
        return $this->belongsTo(RealProject::class, 'chapter_id');
    }
    public function item()
    {
        return $this->belongsTo(RealProjectInfo::class, 'item_id');
    }
}
