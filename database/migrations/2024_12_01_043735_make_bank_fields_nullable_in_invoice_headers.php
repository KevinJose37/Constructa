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
            $table->string('bank_name')->nullable()->change();
            $table->string('account_type')->nullable()->change();
            $table->string('account_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->string('bank_name')->nullable(false)->change();
            $table->string('account_type')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
        });
    }
};
