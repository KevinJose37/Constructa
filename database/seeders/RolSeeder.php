<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role_admin = Role::create(['name' => 'Administrador']);
        $role_gerente = Role::create(['name' => 'Gerente']);
        $role_empleado = Role::create(['name' => 'Empleado']);

        
    }
}
