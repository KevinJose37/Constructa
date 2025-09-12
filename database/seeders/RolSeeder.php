<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Roles
		// Crear roles
		$role_contador   = Role::firstOrCreate(['name' => 'Contador']);
		$role_director   = Role::firstOrCreate(['name' => 'Director']);
		$role_gerente    = Role::firstOrCreate(['name' => 'Gerente']);
		$role_residente  = Role::firstOrCreate(['name' => 'Residente']);
		$role_subgerente = Role::firstOrCreate(['name' => 'Subgerente']);
		$role_tesoreria  = Role::firstOrCreate(['name' => 'Tesoreria']);
		$role_visitante  = Role::firstOrCreate(['name' => 'Visitante']);

		// Definir permisos según el CSV
		$permissions = [
			// PROYECTOS
			'store.project'      => ['GERENTE', 'SUBGERENTE'],
			'update.project'     => ['GERENTE', 'SUBGERENTE'],
			'assign.user.project' => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],
			'unassign.user.project' => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],
			'view.project'       => ['CONTADOR', 'DIRECTOR', 'GERENTE', 'SUBGERENTE', 'VISITANTE'],

			// PRESUPUESTO
			'store.budget'       => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'view.budget'        => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'VISITANTE'],
			'update.budget'      => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'delete.budget'      => ['GERENTE', 'RESIDENTE', 'SUBGERENTE'],

			// CHAT
			'view.chat'          => ['CONTADOR', 'DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'TESORERIA', 'VISITANTE'],
			'use.chat'           => ['CONTADOR', 'DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'TESORERIA'],

			// USUARIOS
			'store.users'        => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],
			'update.users'       => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],
			'view.users'         => ['DIRECTOR', 'GERENTE', 'SUBGERENTE', 'VISITANTE'],
			'delete.users'       => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],

			// MATERIALES
			'store.materials'    => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'update.materials'   => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'view.materials'     => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'VISITANTE'],
			'delete.materials'   => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],

			// ÓRDENES DE COMPRA
			'store.purchase'     => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'update.purchase'    => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'view.purchase'      => ['CONTADOR', 'DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'TESORERIA', 'VISITANTE'],
			'delete.purchase'    => ['GERENTE'],
			'disable.purchase'   => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'attachment.purchase' => ['CONTADOR', 'DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'TESORERIA'],
			'info.purchase'      => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'TESORERIA'],

			'approved_tech.purchase' => ['DIRECTOR', 'GERENTE', 'SUBGERENTE'],
			'approved_account.purchase' => ['CONTADOR'],
			'paid.purchase' => ['GERENTE', 'TESORERIA'],

			// REDIRECCIONAR
			'redirect.materials' => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],

			// CONSOLIDADO
			'view.consolidated'  => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'VISITANTE'],

			// DASHBOARD
			'view.dashboard'     => ['GERENTE', 'VISITANTE'],

			// PROYECTO REAL
			'store.realproject'  => ['GERENTE'],
			'update.realproject' => ['GERENTE'],
			'view.realproject'   => ['GERENTE', 'VISITANTE'],
			'delete.realproject' => ['GERENTE'],

			// AVANCE DE OBRA
			'balance.progress'   => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'weekly.progress'    => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'store.progress'     => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
			'view.progress'      => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE', 'VISITANTE'],
			'report.progress'    => ['DIRECTOR', 'GERENTE', 'RESIDENTE', 'SUBGERENTE'],
		];

		// Crear permisos
		foreach ($permissions as $perm => $roles) {
			$permission = Permission::firstOrCreate(['name' => $perm]);

			foreach ($roles as $roleName) {
				$roleVar = 'role_' . strtolower($roleName);
				if (isset($$roleVar)) {
					$$roleVar->givePermissionTo($permission);
				}
			}
		}
	}
}
