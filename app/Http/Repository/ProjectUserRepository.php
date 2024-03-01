<?php
namespace App\Http\Repository;

use Exception;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProjectUserRepository implements IRepository{

    public function getAll(){
        return Project::with('users')->get();
    }

    public function FindById($id){
        return Project::with('users')->find($id);
    }

    public function FindUserByProject(int $idUser, int $idProject){
        $projects = Project::find($idProject);
        if(!$projects){
            throw new Exception("Fail to find the Project", 1);
            
        }

        $user = $projects->users()->where('users.id', $idUser)->first();
        if(is_null($user)){
            return null; // Retorna null si no existe
        }

        return $user->toArray();
    }

    public function Create(array $data) {
        try {
            $projectId = $data["idProject"];
            $userId = $data["idUser"];
    
            $success = DB::table('participants_project')->insert([
                'project_id' => $projectId,
                'user_id' => $userId
            ]);

            return $success;
    
        } catch (QueryException $e) {
            return false;
        }
    }

    public function Update($id, array $data){

    }

    public function Delete($id){

    }

    public function ProjectUserQuery(){
        return Project::with('users');
    }



    

}