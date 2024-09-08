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
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->decimal('retention_value', 10, 2)->default(0);  // Valor de la retención
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_headers', function (Blueprint $table) {
            //
            $table->dropColumn(['retention_value']);
        });
    }
};
