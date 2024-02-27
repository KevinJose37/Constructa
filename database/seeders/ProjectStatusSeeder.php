<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectStatus;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectStatus::create(['status_name' => 'En progreso']);
        ProjectStatus::create(['status_name' => 'Finalizado']);
        ProjectStatus::create(['status_name' => 'No iniciado']);
        ProjectStatus::create(['status_name' => 'Cancelado']);
        ProjectStatus::create(['status_name' => 'En pausa']);

        Project::create([
            "project_name" => "Base Project",
            "project_description" => "Simple project",
            "project_status_id" => 3,
            "project_start_date" => new DateTime(),
            "project_estimated_end" => new DateTime(),
        ]);
    }
}
