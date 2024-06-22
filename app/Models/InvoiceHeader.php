<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'date','order_name','contractor_name', 'contractor_nit', 'responsible_name', 'company_name', 'company_nit',
        'phone', 'material_destination', 'payment_method_id', 'bank_name', 'account_type', 'account_number',
        'support_type_id', 'project_id', 'general_observations', 'subtotal_before_iva', 'total_iva',
        'total_with_iva', 'retention', 'total_payable', 'payment_info_id'
    ];

    // Definir relaciones si es necesario
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function supportType()
    {
        return $this->belongsTo(PaymentSupport::class, 'support_type_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_purchase_order', 'id');
    }

    public function paidInformation()
    {
        return $this->belongsTo(PaidInformation::class, 'payment_info_id');
    }
}
