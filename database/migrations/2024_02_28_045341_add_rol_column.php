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

        // Schema::create('roles', function (Blueprint $table) {
        //     $table->id();
        //     $table->string("rol_name");
        // });
        // Schema::table('users', function (Blueprint $table) {
        //     $table->unsignedBigInteger("rol_id");
        //     $table->foreign('rol_id')
        //         ->references('id')
        //         ->on('roles')
        //         ->onDelete('restrict')
        //         ->onUpdate('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
