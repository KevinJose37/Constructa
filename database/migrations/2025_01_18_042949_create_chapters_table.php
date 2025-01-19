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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id('id_capitulo');
            $table->unsignedBigInteger('id_presupuesto'); // Relación con el presupuesto
            $table->string('numero_capitulo');
            $table->string('nombre_capitulo');
            $table->timestamps();
    
            // Llave foránea
            $table->foreign('id_presupuesto')->references('id_presupuesto')->on('budgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
