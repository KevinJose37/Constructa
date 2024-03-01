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


}