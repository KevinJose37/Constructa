<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_progress_chapter_details', function (Blueprint $table) {
            $table->decimal('representation_percentage', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('work_progress_chapter_details', function (Blueprint $table) {
            $table->dropColumn('representation_percentage');
        });
    }
};