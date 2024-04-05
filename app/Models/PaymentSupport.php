<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupport extends Model
{
    use HasFactory;
    protected $table = "payment_support";

    protected $fillable = [
        'support_name'
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'support_id');
    }
}
