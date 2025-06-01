<?php

namespace App\Models;

use App\Models\Project;
use App\Models\InvoiceDetail;
use App\Models\PaymentMethod;
use App\Models\PaymentSupport;
use App\Models\PaidInformation;
use App\Models\PurchaseAttachment;
use App\Models\PurchaseOrderState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InvoiceHeader extends Model
{
    use HasFactory;
    protected $table = 'invoice_headers';

    protected $fillable = [
        'date',
        'order_name',
        'contractor_name',
        'contractor_nit',
        'responsible_name',
        'company_name',
        'company_nit',
        'phone',
        'material_destination',
        'payment_method_id',
        'bank_name',
        'account_type',
        'account_number',
        'support_type_id',
        'project_id',
        'general_observations',
        'subtotal_before_iva',
        'total_iva',
        'total_with_iva',
        'retention',
        'total_payable',
        'payment_info_id',
        'retention_value',
        'is_active'
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

    public function attachments()
    {
        return $this->hasMany(PurchaseAttachment::class);
    }
    /**
     * Relación con el estado de la orden de compra
     */
    public function purchaseOrderState(): HasOne
    {
        return $this->hasOne(PurchaseOrderState::class);
    }

    /**
     * Obtener o crear el estado de la orden
     */
    public function getOrCreateState(): PurchaseOrderState
    {
        return $this->purchaseOrderState ?? $this->purchaseOrderState()->create([
            'status' => PurchaseOrderState::STATUS_SIN_PROCESAR,
            'changed_by_user_id' => auth()->id(),
        ]);
    }
}
