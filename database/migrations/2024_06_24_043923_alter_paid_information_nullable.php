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

        Schema::table('paid_information', function (Blueprint $table) {
            $table->date('payment_date')->nullable()->change();
            $table->string('payment_method')->nullable()->change();
            $table->string('payment_how')->nullable()->change();
            $table->string('payment_person')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
