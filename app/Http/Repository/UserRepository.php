<?php
namespace App\Http\Repository;

use App\Models\User;

class UserRepository implements IRepository{

    public function getAll(){
        return User::with('rol')->get();
    }

    public function FindById($id){
        return User::with('rol')->find($id);
    }

    public function Create(array $data){

    }

    public function Update($id, array $data){

    }

    public function Delete($id){

    }

    public function UserQuery(){
        return User::with('rol');
    }

    public function filterLike($value){
        return User::with('rol')->where(function ($queryBuilder) use ($value) {
            $queryBuilder->where('name', 'like', "%$value%")
                         ->orWhere('email', 'like', "%$value%")
                         ->orWhereHas('rol', function ($statusQuery) use ($value) {
                            $statusQuery->where('rol_name', 'like', "%$value%");
                        });
        });
    }

}