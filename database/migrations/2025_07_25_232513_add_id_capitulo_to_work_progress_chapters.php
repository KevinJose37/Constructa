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
        Schema::table('work_progress_chapters', function (Blueprint $table) {
            $table->unsignedBigInteger('id_capitulo')->nullable()->after('project_id');

            $table->foreign('id_capitulo')
                ->references('id_capitulo')
                ->on('chapters')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('work_progress_chapters', function (Blueprint $table) {
            $table->dropForeign(['id_capitulo']);
            $table->dropColumn('id_capitulo');
        });
    }
};
