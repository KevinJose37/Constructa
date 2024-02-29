<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserServices implements IService{

    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }


    public function getAll(Request $request)
    {
        $usersQuery = User::with('rol');
        return $this->paginationService->filter($usersQuery);
    }

    public function getById(int $id)
    {
        $usersQuery = User::with('rol')->find($id);
        return $usersQuery;
    }

    public function Add(Request $request)
    {
        
    }

    public function Update(int $id, Request $request)
    {
        
    }

    public function Delete(int $id, Request $request)
    {
        
    }

}