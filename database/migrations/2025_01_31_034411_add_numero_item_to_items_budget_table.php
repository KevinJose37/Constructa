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
        Schema::table('items_budget', function (Blueprint $table) {
            $table->string('numero_item')->after('id_capitulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_budget', function (Blueprint $table) {
            $table->dropColumn('numero_item');
        });
    }
};
