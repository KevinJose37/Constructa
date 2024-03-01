<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Rol::firstOrCreate(['rol_name' => 'Administrador']);
        Rol::firstOrCreate(['rol_name' => 'Gerente']);
        Rol::firstOrCreate(['rol_name' => 'Empleado']);
    }
}
