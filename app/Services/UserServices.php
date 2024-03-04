<?php
namespace App\Services;

use App\Http\Repository\UserRepository;
use Illuminate\Http\Request;

class UserServices implements IService{

    protected $paginationService;
    protected $userRepository;

    public function __construct(PaginationServices $paginationService, UserRepository $userRepo)
    {
        $this->paginationService = $paginationService;
        $this->userRepository = $userRepo;
    }


    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function getAllPaginate($filter = '')
    {
        $projectQuery = $this->userRepository->UserQuery();
        if($filter != ""){
            $filter = htmlspecialchars(trim($filter));
            $projectQuery = $this->userRepository->filterLike($filter);

        }

        return $this->paginationService->filter($projectQuery);
    }
    

    public function getById(int $id)
    {
        return $this->userRepository->FindById($id);
    }

    public function Add(array $data)
    {
        
    }

    public function Update(int $id, array $data)
    {
        
    }

    public function Delete(int $id)
    {
        
    }

}