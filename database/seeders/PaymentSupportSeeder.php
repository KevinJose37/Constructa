<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PaymentSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_support')->insertOrIgnore([
            ['support_name' => 'Cuenta de cobro'],
            ['support_name' => 'Factura electrónica'],
            ['support_name' => 'Sin soporte'],
        ]);
    }
}
