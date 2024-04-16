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

        Schema::create('paid_information', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('payment_how');
            $table->string('payment_person');
        });
        
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_info_id')->nullable();
            $table->foreign('payment_info_id')->references('id')->on('paid_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_headers_table_and_paid', function (Blueprint $table) {
            //
        });
    }
};
