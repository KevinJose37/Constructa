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
        Schema::create('invoice_headers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('contractor_name');
            $table->string('contractor_nit');
            $table->string('responsible_name');
            $table->string('company_name');
            $table->string('company_nit');
            $table->string('phone');
            $table->string('material_destination');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('bank_name');
            $table->string('account_type');
            $table->string('account_number');
            $table->unsignedBigInteger('support_type_id');
            $table->unsignedBigInteger('project_id');
            $table->text('general_observations')->nullable(); // Observaciones generales
            $table->decimal('subtotal_before_iva', 10, 2)->default(0); // Subtotal antes de IVA
            $table->decimal('total_iva', 10, 2)->default(0); // Total de IVA
            $table->decimal('total_with_iva', 10, 2)->default(0); // Total con IVA
            $table->decimal('retention', 10, 2)->default(0); // Retención calculada
            $table->decimal('total_payable', 10, 2)->default(0); // Total a pagar después de retenciones
            $table->timestamps();
            
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('support_type_id')->references('id')->on('payment_support')->onDelete('cascade');
            
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_headers');
    }
};
