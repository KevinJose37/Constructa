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
            $table->unsignedBigInteger('payment_method_id'); // Asegúrate de que el tipo coincida con el de la columna referenciada.
            $table->string('bank_name');
            $table->string('account_type');
            $table->string('account_number');
            $table->unsignedBigInteger('support_type_id'); // Asegúrate de que el tipo coincida con el de la columna referenciada.
            $table->timestamps();
        
            // Define las claves foráneas
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
