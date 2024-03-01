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

    public function getAllPaginate()
    {
        $usersQuery = $this->userRepository->UserQuery();
        return $this->paginationService->filter($usersQuery);
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