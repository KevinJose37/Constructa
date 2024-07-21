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
        $role_admin = Role::firstOrCreate(['name' => 'Director']);
        $role_gerente = Role::firstOrCreate(['name' => 'Tesorero']);
        $role_contador = Role::firstOrCreate(['name' => 'Contador']);
        $role_empleado = Role::firstOrCreate(['name' => 'Residente']);

        // Permisos

        // Proyectos
        $store_project = Permission::firstOrCreate(['name' => 'store.project']);
        $update_project = Permission::firstOrCreate(['name' => 'update.project']);
        $delete_project = Permission::firstOrCreate(['name' => 'delete.project']);
        $view_project = Permission::firstOrCreate(['name' => 'view.project']);
        $assign_user_project = Permission::firstOrCreate(['name' => 'assign.user.project']);
        $unassign_user_project = Permission::firstOrCreate(['name' => 'unassign.user.project']);

        // Usuarios
        $store_users = Permission::firstOrCreate(['name' => 'store.users']);
        $update_users = Permission::firstOrCreate(['name' => 'update.users']);
        $delete_users = Permission::firstOrCreate(['name' => 'delete.users']);
        $view_users = Permission::firstOrCreate(['name' => 'view.users']);
        $change_rol_users = Permission::firstOrCreate(['name' => 'change.rol.users']);

        // Órdenes de compra
        $store_purchase_order = Permission::firstOrCreate(['name' => 'store.purchase']);
        $view_purchase_order = Permission::firstOrCreate(['name' => 'view.purchase']);
        $approved_tech = Permission::firstOrCreate(['name' => 'approved_tech.purchase']);
        $approved_account = Permission::firstOrCreate(['name' => 'approved_account.purchase']);
        $paid_account = Permission::firstOrCreate(['name' => 'paid.purchase']);


        // Asignar permisos a roles
        $role_admin->syncPermissions(
            $store_project,
            $update_project,
            $delete_project,
            $view_project,
            $assign_user_project,
            $unassign_user_project,
            $store_users,
            $update_users,
            $delete_users,
            $view_users,
            $change_rol_users,
            $store_purchase_order,
            $view_purchase_order,
            $approved_tech,
            $approved_account,
            $paid_account
        );

        $role_gerente->syncPermissions(
            $store_project,
            $update_project,
            $view_project,
            $assign_user_project,
            $unassign_user_project,
            $store_users,
            $update_users,
            $view_users,
            $store_purchase_order,
            $view_purchase_order,
            $approved_tech,
            $paid_account
        );


        $role_contador->syncPermissions(
            $view_project,
            $view_purchase_order,
            $store_purchase_order,
            $approved_account,
            $paid_account
        );

        $role_empleado->syncPermissions($view_project, $view_users, $update_users);
    }
}
