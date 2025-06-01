<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderState extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_header_id',
        'status',
        'status_notes',
        'status_changed_at',
        'changed_by_user_id',
        'previous_status',
        'change_metadata',
    ];

    protected $casts = [
        'status_changed_at' => 'datetime',
        'change_metadata' => 'array',
    ];

    // Estados disponibles
    const STATUS_SIN_PROCESAR = 'sin_procesar';
    const STATUS_PENDIENTE = 'pendiente';
    const STATUS_POR_CONFIRMAR = 'por_confirmar';
    const STATUS_PAGADO = 'pagado';

    // Mapeo para mostrar en UI
    const STATUS_LABELS = [
        self::STATUS_SIN_PROCESAR => 'Sin procesar',
        self::STATUS_PENDIENTE => 'En revisión',
        self::STATUS_POR_CONFIRMAR => 'Pagar orden',
        self::STATUS_PAGADO => 'Pagado',
    ];

    const STATUS_CLASSES = [
        self::STATUS_SIN_PROCESAR => 'danger',
        self::STATUS_PENDIENTE => 'warning',
        self::STATUS_POR_CONFIRMAR => 'warning',
        self::STATUS_PAGADO => 'success',
    ];

    /**
     * Relación con la orden de compra
     */
    public function invoiceHeader(): BelongsTo
    {
        return $this->belongsTo(InvoiceHeader::class);
    }

    /**
     * Relación con el usuario que hizo el cambio
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    /**
     * Obtener el label del estado para mostrar
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Obtener la clase CSS del estado
     */
    public function getStatusClassAttribute(): string
    {
        return self::STATUS_CLASSES[$this->status] ?? 'secondary';
    }

    /**
     * Actualizar el estado con trazabilidad
     */
    public function updateStatus(string $newStatus, ?string $notes = null, ?array $metadata = null): void
    {
        $this->update([
            'previous_status' => $this->status,
            'status' => $newStatus,
            'status_notes' => $notes,
            'status_changed_at' => now(),
            'changed_by_user_id' => auth()->id(),
            'change_metadata' => $metadata,
        ]);
    }
}
