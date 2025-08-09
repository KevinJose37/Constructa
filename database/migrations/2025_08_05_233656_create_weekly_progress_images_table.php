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
        Schema::create('weekly_progress_images', function (Blueprint $table) {
            $table->id();
			$table->foreignId('chapter_detail_id')->constrained('work_progress_chapter_details')->onDelete('cascade');
			$table->foreignId('week_project_id')->constrained('week_projects')->onDelete('cascade');
			$table->string('image_path');
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_progress_images');
    }
};
