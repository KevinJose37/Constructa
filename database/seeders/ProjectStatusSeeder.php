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
        ProjectStatus::firstOrCreate(['status_name' => 'En progreso']);
        ProjectStatus::firstOrCreate(['status_name' => 'Finalizado']);
        ProjectStatus::firstOrCreate(['status_name' => 'No iniciado']);
        ProjectStatus::firstOrCreate(['status_name' => 'Cancelado']);
        ProjectStatus::firstOrCreate(['status_name' => 'Suspendido']);

        Project::firstOrCreate([
            "project_name" => "Proyecto de ejemplo",
            "project_description" => "Descripcion de ejemplo",
            "project_status_id" => 3,
            "project_start_date" => new DateTime(),
            "project_estimated_end" => new DateTime(),
        ]);

        Project::firstOrCreate([
            "project_name" => "Proyecto de ejemplo",
            "project_description" => "Descripcion de ejemplo",
            "project_status_id" => 1,
            "project_start_date" => new DateTime(),
            "project_estimated_end" => new DateTime(),
        ]);
    }
}
