<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidInformation extends Model
{
    use HasFactory;
    protected $table = "paid_information";
    public $timestamps = false;

    protected $fillable = [
        'payment_date',
        'payment_method',
        'payment_how',
        'payment_person'
    ];

    public function invoiceHeader()
    {
        return $this->hasOne(InvoiceHeader::class, 'payment_info_id', 'id');
    }
}
