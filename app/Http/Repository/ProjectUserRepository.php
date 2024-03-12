<?php

namespace App\Http\Repository;

use Exception;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ProjectUserRepository implements IRepository
{

    public function getAll()
    {
        return Project::with('users')->get();
    }

    public function FindById($id)
    {
        return Project::with('users')->find($id);
    }

    public function FindUserByProject(int $idUser, int $idProject)
    {
        $projects = Project::find($idProject);
        if (!$projects) {
            throw new Exception("Fail to find the Project", 1);
        }

        $user = $projects->users()->where('users.id', $idUser)->first();
        if (is_null($user)) {
            return null; // Retorna null si no existe
        }

        return $user->toArray();
    }

    public function notAssignedUsers(int $idProject)
    {
        $usersNotAssigned = User::whereDoesntHave('projects', function ($query) use ($idProject) {
            $query->where('projects.id', $idProject);
        })->get();
        return $usersNotAssigned;
    }

    public function Create(array $data)
    {
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

    public function Update($id, array $data)
    {
        try {
            $projectId = $data["idProject"];
            $userId = $data["idUser"];

            $user = User::find($userId);
            $projects = Project::find($projectId);
            if (!$projects) {
                throw new Exception("Fail to find the Project", 1);
            }

            if (!$user) {
                throw new Exception("Fail to find the user", 1);
            }

            $user->projects()->detach($projects->id);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    public function Delete($id)
    {
        try {
            $projects = Project::find($id);
            if (!$projects) {
                throw new Exception("Fail to find the Project", 1);
            }
            $projects->users()->detach();
            $projects->delete();
            return true;
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function DeleteUserByProject($idProject, $idUser){
        try {
            $projects = Project::find($idProject);
            if (!$projects) {
                throw new Exception("Fail to find the Project", 1);
            }


            $user = User::find($idUser);
            if (!$user) {
                throw new Exception("Fail to find the user", 1);
            }

            $projects->users()->detach($user);

            return true;
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function ProjectUserQuery()
    {
        return Project::with('users');
    }
}
