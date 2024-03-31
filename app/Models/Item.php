<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = "items";
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'id_item');
    }

}
