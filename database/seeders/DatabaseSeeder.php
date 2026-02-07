<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(['name' => 'Admin', 'email' => 'pipegamo55@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 1]);
        User::firstOrCreate(['name' => 'Default user 1', 'email' => 'default1@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 2', 'email' => 'default2@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 3', 'email' => 'default3@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 4', 'email' => 'default4@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 5', 'email' => 'default5@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 6', 'email' => 'default6@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 7', 'email' => 'default7@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 8', 'email' => 'default8@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 9', 'email' => 'default9@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
        User::firstOrCreate(['name' => 'Default user 10', 'email' => 'default10@gmail.com', 'password' => bcrypt('pass'), 'rol_id' => 3]);
    }
}
