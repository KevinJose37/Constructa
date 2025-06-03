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
        Schema::create('material_redirections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('invoice_headers')->onDelete('cascade');
            $table->foreignId('invoice_detail_id')->constrained('invoice_details')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('real_projects')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('real_project_infos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_redirections');
    }
};
