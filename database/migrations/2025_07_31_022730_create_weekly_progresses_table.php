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
		Schema::create('weekly_progresses', function (Blueprint $table) {
			$table->id();

			$table->foreignId('chapter_detail_id')->constrained('work_progress_chapter_details')->onDelete('cascade');
			$table->foreignId('week_project_id')->constrained('week_projects')->onDelete('cascade');

			// Campos de avance
			$table->decimal('executed_quantity', 8, 2)->default(0);
			$table->decimal('executed_value', 8, 2)->default(0);
			$table->decimal('executed_percentage', 8, 2)->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('weekly_progresses');
	}
};
