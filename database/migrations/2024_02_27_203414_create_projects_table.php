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
        
        Schema::create('project_statuses', function (Blueprint $table) {
            $table->id();
            $table->string("status_name");
        });
        
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string("project_name");
            $table->string("project_description");
            $table->unsignedBigInteger("project_status_id");
            $table->date("project_start_date");
            $table->date("project_estimated_end");
            $table->timestamps();

            $table->foreign('project_status_id')
                ->references('id')
                ->on('project_statuses')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_statuses');
    }
};