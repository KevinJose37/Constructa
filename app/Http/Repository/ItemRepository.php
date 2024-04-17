<?php

namespace App\Http\Repository;

use App\Models\Item;
use Exception;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;

class ItemRepository implements IRepository
{

    public function getAll()
    {
        // return Item::limit(3)->get();]
        return Item::get();
    }

    public function FindById($id)
    {
        return Item::find($id);
    }

    public function Create(array $data)
    {
        return $data;
    }

    public function Update($id, array $data)
    {
        return $id;
    }

    public function Delete($id)
    {
        return $id;
    }


    public function filterLike($value, $limit = null)
    {
        $query = Item::where(function ($queryBuilder) use ($value) {
            $queryBuilder->where('name', 'like', "%$value%")
                ->orWhere('description', 'like', "%$value%")
                ->orWhere('price', 'like', "%$value%");
        });
        if ($limit !== null) {
            $query->limit($limit);
        }
    
        return $query->get();
    }
}
