<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_headers', function (Blueprint $table) {
            $table->decimal('subtotal_before_iva', 20, 2)->default(0)->change(); // Subtotal antes de IVA
            $table->decimal('total_iva', 20, 2)->default(0)->change(); // Total de IVA
            $table->decimal('total_with_iva', 20, 2)->default(0)->change(); // Total con IVA
            $table->decimal('retention', 20, 2)->default(0)->change(); // Retención calculada
            $table->decimal('total_payable', 20, 2)->default(0)->change(); // Total a pagar después de retenciones
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->decimal('total_price', 20, 2)->change(); // Aumenta la precisión a 20
            $table->decimal('total_price_iva', 20, 2)->change(); // Aumenta la precisión a 20
            $table->decimal('price', 20, 2)->change(); // Aumenta la precisión a 20
            $table->decimal('iva', 20, 2)->change(); // Aumenta la precisión a 20
        });
    }

    public function down()
    {
        Schema::table('invoice_headers', function (Blueprint $table) {
            // Revertir los cambios si es necesario
            $table->decimal('subtotal_before_iva', 10, 2)->default(0)->change();
            $table->decimal('total_iva', 10, 2)->default(0)->change();
            $table->decimal('total_with_iva', 10, 2)->default(0)->change();
            $table->decimal('retention', 10, 2)->default(0)->change();
            $table->decimal('total_payable', 10, 2)->default(0)->change();
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change(); // Revertir a la precisión original si es necesario
            $table->decimal('total_price_iva', 10, 2)->change();
            $table->decimal('price', 10, 2)->change();
            $table->decimal('iva', 10, 2)->change();
        });
    }
};
