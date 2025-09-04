<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$roles = ['Contador', 'Director', 'Gerente', 'Residente', 'Subgerente', 'Tesoreria', 'Visitante'];

		foreach ($roles as $rolName) {
			$role = Role::where('name', $rolName)->first();

			if ($role) {
				$user = User::firstOrCreate(
					['email' => strtolower($rolName) . '@example.com'],
					['name' => "pub_$rolName", 'password' => bcrypt('pass'), 'rol_id' => $role->id, 'fullname' => "User $rolName",]
				);
				// Asignar rol por nombre sin preocuparnos por id
				$user->assignRole($rolName);
			}
		}
	}
}
