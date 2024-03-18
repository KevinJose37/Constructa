<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $role_admin = Role::firstOrCreate(['name' => 'Administrador']);
        $role_gerente = Role::firstOrCreate(['name' => 'Gerente']);
        $role_empleado = Role::firstOrCreate(['name' => 'Empleado']);

        // Permisos

        // Proyectos
        $store_project = Permission::firstOrCreate(['name' => 'store.project']);
        $update_project = Permission::firstOrCreate(['name' => 'update.project']);
        $delete_project = Permission::firstOrCreate(['name' => 'delete.project']);
        $view_project = Permission::firstOrCreate(['name' => 'view.project']);

        // Asignar permisos a roles
        $role_admin->syncPermissions($store_project, $update_project, $delete_project, $view_project);
        $role_gerente->syncPermissions($store_project, $update_project, $view_project);
        $role_empleado->syncPermissions($view_project);
    }
}
