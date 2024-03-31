<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insertOrIgnore([
            ['payment_name' => 'Transferencia'],
            ['payment_name' => 'Efectivo'],
            ['payment_name' => 'Crédito'],
            ['payment_name' => 'PSE'],
        ]);
    }
}
