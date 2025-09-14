<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('invoice_headers', function (Blueprint $table) {
        $table->unsignedInteger('invoice_number')->after('id'); // o en la posición que prefieras
    });
}

public function down()
{
    Schema::table('invoice_headers', function (Blueprint $table) {
        $table->dropColumn('invoice_number');
    });
}
};
