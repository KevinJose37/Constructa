<?php
namespace App\Http\Repository;

use App\Models\Project;

class ProjectRepository implements IRepository{

    public function getAll(){
        return Project::with('projectStatus')->get();
    }

    public function FindById($id){
        return Project::with('projectStatus')->find($id);
    }

    public function Create(array $data){

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
                        });;
        });
    }

}