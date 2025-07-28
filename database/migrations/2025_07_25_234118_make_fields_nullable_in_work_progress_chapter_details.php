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
    Schema::table('work_progress_chapter_details', function (Blueprint $table) {
        $table->decimal('balance_adjustment', 15, 2)->nullable()->change();
        $table->decimal('balance_quantity', 15, 2)->nullable()->change();
        $table->decimal('balance_value', 15, 2)->nullable()->change();
        $table->decimal('adjusted_quantity', 15, 2)->nullable()->change();
        $table->decimal('adjusted_value', 15, 2)->nullable()->change();
        $table->decimal('weekly_advance_quantity', 15, 2)->nullable()->change();
        $table->decimal('weekly_advance_value', 15, 2)->nullable()->change();
        $table->decimal('total_quantity', 15, 2)->nullable()->change();
        $table->decimal('remaining_balance', 15, 2)->nullable()->change();
        $table->decimal('executed_value', 15, 2)->nullable()->change();
        $table->decimal('executed_percentage', 5, 2)->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_progress_chapter_details', function (Blueprint $table) {
            //
        });
    }
};
