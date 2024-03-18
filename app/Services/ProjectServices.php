<?php
namespace App\Services;

use App\Http\Repository\ProjectRepository;
use App\Http\Repository\UserRepository;
use Illuminate\Http\Request;

class ProjectServices implements IService{

    protected $paginationService;
    protected $projectRepository;

    public function __construct(PaginationServices $paginationService, ProjectRepository $projectRepo)
    {
        $this->paginationService = $paginationService;
        $this->projectRepository = $projectRepo;
    }


    public function getAll()
    {
        return $this->projectRepository->getAll();
    }

    public function getAllPaginate($filter = "")
    {

        $projectQuery = $this->projectRepository->ProjectQuery();
        if($filter != ""){
            $filter = htmlspecialchars(trim($filter));
            $projectQuery = $this->projectRepository->filterLike($filter);

        }

        return $this->paginationService->filter($projectQuery);
    }

    public function getById(int $id)
    {
        return $this->projectRepository->FindById($id);
    }

    public function Add(array $data)
    {
        return $this->projectRepository->Create($data);
    }

    public function Update(int $id, array $data)
    {
        $resultUpdate = $this->projectRepository->Update($id, $data);
        if(!$resultUpdate){
            return ['success' => false, 'message' => 'El proyecto no existe']; 
        }
        return true;
    }

    public function Delete(int $id)
    {
        
    }

    public function getStatusProjects(){
        return $this->projectRepository->getAllStatusProject();
    }

}