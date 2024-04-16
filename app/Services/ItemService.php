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
        $projectQuery = $this->itemRepository->getAll();
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
    public function getById(int $id)
    {
        return $this->itemRepository->FindById($id);
    }

    public function Add(array $data)
    {
        return $data;
    }

    public function Update(int $id, array $data)
    {
        return $data;
    }

    public function Delete(int $id)
    {
        return $id;
    }
}
