<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignUserProject extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('participants_project')->insertOrIgnore([
            ['user_id' => 1,'project_id' => 1],
            ['user_id' => 2,'project_id' => 1],
            ['user_id' => 3,'project_id' => 1],
            ['user_id' => 4,'project_id' => 1],
            ['user_id' => 5,'project_id' => 1],
            ['user_id' => 6,'project_id' => 2],
            ['user_id' => 7,'project_id' => 2],
            ['user_id' => 8,'project_id' => 2],
            ['user_id' => 9,'project_id' => 2],
            ['user_id' => 10,'project_id' => 2]
        ]);
    }
}
