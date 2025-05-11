<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('real_project_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_project_id')->constrained('real_projects')->onDelete('cascade');
            $table->string('item_number');      // Ej: "1.1", "1.2"
            $table->text('description');        // Descripción del ítem
            $table->decimal('total', 15, 2);    // Valor total
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_project_infos');
    }
};
