<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Repository\UserRepository;
use App\Http\Repository\ProjectRepository;
use App\Http\Repository\ProjectUserRepository;
use Exception;

class ProjectUserServices implements IService{

    protected $paginationService;
    protected $userRepository;
    protected $projectRepository;
    protected $projectUserRepository;

    public function __construct(PaginationServices $paginationService, UserRepository $userRepo, ProjectRepository $projectRepo, ProjectUserRepository $projectUserRepo)
    {
        $this->paginationService = $paginationService;
        $this->userRepository = $userRepo;
        $this->projectRepository = $projectRepo;
        $this->projectUserRepository = $projectUserRepo;
    }


    public function getAll()
    {
        return $this->projectUserRepository->getAll();
    }

    public function getAllPaginate($filter = '')
    {
        $projectUsersQuery = $this->projectUserRepository->ProjectUserQuery();
        return $this->paginationService->filter($projectUsersQuery);

    }

    public function getNotAssignedUsers(int $id){
        return $this->projectUserRepository->notAssignedUsers($id);   
    }

    public function getById(int $id)
    {
        return $this->projectUserRepository->FindById($id);
    }

    


    public function Add(array $data)
    {

        // Validamos la existencia del usuario
        $userId = $this->userRepository->FindById($data["idUser"]);
        if($userId === null){
            return ['success' => false, 'message' => 'El usuario no existe'];
        }

        // Validamos la existencia del proyecto
        $projectId = $this->projectRepository->FindById($data["idProject"]);
        if($projectId === null){
            return ['success' => false, 'message' => 'El proyecto no existe'];
        }

        try {
            // Validamos la existencia del usuario en el proyecto
            $validAssign = $this->projectUserRepository->FindUserByProject($data["idUser"],$data["idProject"]);
            if(!is_null($validAssign)){
                return ['success' => false, 'message' => 'El usuario ya está asignado al proyecto'];
            }
            
            return $this->projectUserRepository->Create($data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e];
        }
    }

    public function Update(int $id, array $data)
    {
        
    }

    public function Delete(int $id, )
    {
        
    }

}