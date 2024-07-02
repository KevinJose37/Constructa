<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = "payment_methods";

    protected $fillable = [
        'payment_name'
    ];

    public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class, 'payment_method_id');
}

}
