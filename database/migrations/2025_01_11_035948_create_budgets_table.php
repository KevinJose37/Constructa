<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id('id_presupuesto'); // Identificador del presupuesto
            $table->string('descripcion_obra'); // Descripción de la obra
            $table->string('localizacion'); // Localización de la obra
            $table->date('fecha'); // Fecha del presupuesto
            $table->unsignedBigInteger('id_proyecto'); // Relación con la tabla 'projects'
            $table->timestamps();

            // Llave foránea
            $table->foreign('id_proyecto')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
