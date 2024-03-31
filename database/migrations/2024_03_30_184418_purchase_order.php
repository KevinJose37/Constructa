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

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_name');
        });

        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->date('date_order');
            $table->unsignedBigInteger("id_project");
            $table->unsignedBigInteger("payment_id");
            $table->timestamps();

            $table->foreign('id_project')
                ->references('id')
                ->on('projects')
                ->onDelete('restrict')
                ->onUpdate('cascade');


            $table->foreign('payment_id')
                ->references('id')
                ->on('payment_methods')
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
