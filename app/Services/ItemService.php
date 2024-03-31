<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Repository\ItemRepository;

class ItemService implements IService
{

    protected $paginationService;
    protected $itemRepository;

    public function __construct(PaginationServices $paginationService, ItemRepository $itemRepo)
    {
        $this->paginationService = $paginationService;
        $this->itemRepository = $itemRepo;
    }


    public function getAll()
    {
        return $this->itemRepository->getAll();
    }

    public function getAllPaginate($filter = '')
    {
        $projectQuery = $this->itemRepository->UserQuery();
        if ($filter != "") {
            $filter = htmlspecialchars(trim($filter));
            $projectQuery = $this->itemRepository->filterLike($filter);
        }
        return $this->paginationService->filter($projectQuery);
    }

    public function getAllFilter($filter, $limit = null){
        $filter = htmlspecialchars(trim($filter));
        return $this->itemRepository->filterLike($filter, $limit);
    }

    public function getRolesUsers()
    {
        return $this->itemRepository->getRolUsers();
    }

    public function getById(int $id)
    {
        return $this->itemRepository->FindById($id);
    }

    public function Add(array $data)
    {
        // Valid e-mail
        if ($this->itemRepository->validUserByColumn("email", $data["email"]) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este email'];
        }

        // Valid e-mail
        if ($this->itemRepository->validUserByColumn("name", $data["name"]) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este nombre'];
        }

        if (!Role::findById($data["rol_id"])) {
            return ['success' => false, 'message' => 'Rol no válido'];
        }
        try {
            return $this->itemRepository->Create($data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function Update(int $id, array $data)
    {
        // Valid e-mail
        if ($this->itemRepository->validUserByColumn("email", $data["email"], $id) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este email'];
        }

        // Valid e-mail
        if ($this->itemRepository->validUserByColumn("name", $data["name"], $id) !== null) {
            return ['success' => false, 'message' => 'Ya existe un usuario con este nombre'];
        }

        if (!Role::findById($data["rol_id"])) {
            return ['success' => false, 'message' => 'Rol no válido'];
        }
        try {
            return $this->itemRepository->Update($id, $data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function Delete(int $id)
    {
        try {
            // Validamos la existencia del usuario en el proyecto
            $validDelete = $this->itemRepository->Delete($id);
            return true;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
