<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items_budget', function (Blueprint $table) {
            $table->decimal('cantidad', 15, 2)->change();
            $table->decimal('vr_unit', 15, 2)->change();
            $table->decimal('vr_total', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('items_budget', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 2)->change();
            $table->decimal('vr_unit', 10, 2)->change();
            $table->decimal('vr_total', 10, 2)->change();
        });
    }
};
