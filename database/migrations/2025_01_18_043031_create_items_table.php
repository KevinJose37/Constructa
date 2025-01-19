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
        Schema::create('items_budget', function (Blueprint $table) {
            $table->id('id_item_budget');
            $table->unsignedBigInteger('id_capitulo'); // Relación con el capítulo
            $table->string('descripcion');
            $table->string('und'); // Unidad
            $table->decimal('cantidad', 10, 2);
            $table->decimal('vr_unit', 10, 2);
            $table->decimal('vr_total', 10, 2);
            $table->timestamps();
    
            // Llave foránea
            $table->foreign('id_capitulo')->references('id_capitulo')->on('chapters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_budget');
    }
};
