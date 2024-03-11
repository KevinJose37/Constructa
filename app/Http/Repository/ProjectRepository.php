<?php
namespace App\Http\Repository;

use App\Models\Project;
use App\Models\ProjectStatus;

class ProjectRepository implements IRepository{

    public function getAll(){
        return Project::with('projectStatus')->get();
    }

    public function FindById($id){
        return Project::with('projectStatus')->find($id);
    }

    public function Create(array $data){
        $project = new Project();
        $project->project_name = $data['project_name'];
        $project->project_description = $data['project_description'];
        $project->project_status_id = $data['project_status_id'];
        $project->project_start_date = $data['project_start_date'];
        $project->project_estimated_end = $data['project_estimated_end'];
        return $project->save();
    }

    public function Update($id, array $data){

    }

    public function Delete($id){

    }

    public function ProjectQuery(){
        return Project::with('projectStatus');
    }

    public function filterLike($value){
        return Project::with('projectStatus')->where(function ($queryBuilder) use ($value) {
            $queryBuilder->where('project_name', 'like', "%$value%")
                         ->orWhere('project_description', 'like', "%$value%")
                         ->orWhere('project_start_date', 'like', "%$value%")
                         ->orWhere('project_estimated_end', 'like', "%$value%")
                         ->orWhereHas('projectStatus', function ($statusQuery) use ($value) {
                            $statusQuery->where('status_name', 'like', "%$value%");
                        });
        });
    }

    public function getAllStatusProject(){
        return ProjectStatus::get();
    }

}