<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('real_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('id_capitulo')->nullable()->after('project_id');

            // si ya existe la tabla chapters con pk id_capitulo
            $table->foreign('id_capitulo')
                ->references('id_capitulo')
                ->on('chapters')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('real_projects', function (Blueprint $table) {
            $table->dropForeign(['id_capitulo']);
            $table->dropColumn('id_capitulo');
        });
    }
};
