<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_header_id', 'filename', 'path'];

    public function invoiceHeader()
    {
        return $this->belongsTo(InvoiceHeader::class);
    }
}