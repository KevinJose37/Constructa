<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Repository\UserRepository;

class UserServices implements IService
{

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
        if ($filter != "") {
            $filter = htmlspecialchars(trim($filter));
            $projectQuery = $this->userRepository->filterLike($filter);
        }

        return $this->paginationService->filter($projectQuery);
    }

    public function getRolesUsers()
    {
        return $this->userRepository->getRolUsers();
    }

    public function getById(int $id)
    {
        return $this->userRepository->FindById($id);
    }

    public function Add(array $data)
    {
        // Valid e-mail
        if ($this->userRepository->validUserByColumn("email", $data["email"]) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este email'];
        }

        // Valid e-mail
        if ($this->userRepository->validUserByColumn("name", $data["name"]) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este nombre'];
        }

        if (!Role::findById($data["rol_id"])) {
            return ['success' => false, 'message' => 'Rol no válido'];
        }
        try {
            return $this->userRepository->Create($data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function Update(int $id, array $data)
    {
        // Valid e-mail
        if ($this->userRepository->validUserByColumn("email", $data["email"], $id) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este email'];
        }

        // Valid e-mail
        if ($this->userRepository->validUserByColumn("name", $data["name"], $id) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este nombre'];
        }

        if (!Role::findById($data["rol_id"])) {
            return ['success' => false, 'message' => 'Rol no válido'];
        }
        try {
            return $this->userRepository->Update($id, $data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function Delete(int $id)
    {
        try {
            // Validamos la existencia del usuario en el proyecto
            $validDelete = $this->userRepository->Delete($id);
            return true;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
