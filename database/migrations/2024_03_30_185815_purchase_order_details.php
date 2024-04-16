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
    Schema::create('invoice_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger("id_purchase_order");
        $table->unsignedBigInteger("id_item");
        $table->decimal("quantity", 8, 2); // Asumiendo que quantity puede tener decimales
        $table->decimal("price", 10, 2); // El precio unitario del item
        $table->decimal("total_price", 10, 2); // El precio total sin IVA
        $table->decimal("iva", 10, 2); // El valor del IVA para el item
        $table->decimal("price_iva", 10, 2); // El precio unitario con IVA
        $table->decimal("total_price_iva", 10, 2); // El precio total con IVA
        $table->unsignedBigInteger('project_id');

        $table->foreign('id_purchase_order')
              ->references('id')
              ->on('invoice_headers') // Asegúrate de que este sea el nombre correcto de tu tabla de cabeceras
              ->onDelete('restrict')
              ->onUpdate('cascade');

        $table->foreign('id_item')
              ->references('id')
              ->on('items')
              ->onDelete('restrict')
              ->onUpdate('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
