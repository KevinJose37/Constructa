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
        Schema::create('work_progress_chapter_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('work_progress_chapters')->onDelete('cascade'); // Relación con capítulos
            $table->decimal('item', 15, 2);
            $table->text('description');
            $table->string('unit');
            $table->decimal('contracted_quantity', 15, 2);
            $table->decimal('unit_value', 15, 2);
            $table->decimal('partial_value', 15, 2);
            $table->decimal('balance_adjustment', 15, 2);
            $table->decimal('balance_quantity', 15, 2);
            $table->decimal('balance_value', 15, 2);
            $table->decimal('adjusted_quantity', 15, 2);
            $table->decimal('adjusted_value', 15, 2);
            $table->decimal('weekly_advance_quantity', 15, 2);
            $table->decimal('weekly_advance_value', 15, 2);
            $table->decimal('total_quantity', 15, 2);
            $table->decimal('remaining_balance', 15, 2);
            $table->decimal('executed_value', 15, 2);
            $table->decimal('executed_percentage', 5, 2);
            $table->timestamps(); // Para crear automáticamente los campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_progress_chapter_details');
    }
};
