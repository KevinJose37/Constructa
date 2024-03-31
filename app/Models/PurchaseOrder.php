<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = "purchase_order";

    protected $fillable = [
        'date_order',
        'id_project',
        'payment_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function paymentMethods()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'id_purchase_order');
    }
}
