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
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->boolean('has_support')->default(false); // ¿Tiene soporte?
            $table->date('payment_date')->nullable(); // Fecha de pago
            $table->string('payer')->nullable(); // ¿Quién pagó?
            $table->boolean('is_petty_cash')->default(false); // ¿Es caja menor?
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->dropColumn(['has_support', 'payment_date', 'payer', 'is_petty_cash']);
        });
    }
};
