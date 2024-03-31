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

        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_purchase_order");
            $table->unsignedBigInteger("id_item");
            $table->string("amount");
            $table->string("total_price");

            $table->foreign('id_purchase_order')
                ->references('id')
                ->on('purchase_order')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_item')
                ->references('id')
                ->on('items')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
