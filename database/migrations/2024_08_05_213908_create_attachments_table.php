<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_header_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
