<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_header_id')->constrained('invoice_headers')->onDelete('cascade');

            // Estado principal
            $table->enum('status', [
                'sin_procesar',
                'pendiente',
                'por_confirmar',
                'pagado'
            ])->default('sin_procesar');

            // Trazabilidad y auditoría
            $table->text('status_notes')->nullable();
            $table->timestamp('status_changed_at')->useCurrent();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users');

            // Información del cambio
            $table->string('previous_status')->nullable();
            $table->json('change_metadata')->nullable(); // Para guardar datos adicionales del cambio

            $table->timestamps();

            // Índices
            $table->unique('invoice_header_id'); // Una orden solo tiene un estado actual
            $table->index(['status', 'status_changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_states');
    }
};
