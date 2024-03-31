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
        return Item::get();
    }

    public function FindById($id)
    {
        return Item::find($id);
    }

    public function Create(array $data)
    {
        try {
            // Crear un nuevo usuario con los datos validados
            $user = new User();
            $user->name = $data['name'];
            $user->fullname = $data['fullname'];
            $user->password = bcrypt($data['password']);
            $user->email = $data['email'];
            $user->rol_id = $data['rol_id']; // Asignar el rol_id del formulario al modelo User
            $user->save();
            $role = Role::findById($user->rol_id);
            $user->assignRole($role);
            return true;
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function validUserByColumn($columnName, $value, $ignoreId = null)
    {
        $query = User::where($columnName, $value);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->first();
    }

    public function Update($id, array $data)
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        $user->name = $data["name"];
        $user->fullname = $data["fullname"];
        if (trim($data["password"]) != "" && !is_null($data["password"]) && !empty($data["password"])) {
            $user->password = bcrypt($data["password"]);
        }
        $user->email = $data["email"];
        // Obtener el rol actual del usuario
        $currentRole = $user->roles->first();

        // Si el rol actual es diferente al nuevo rol seleccionado, actualizamos el rol
        if ($currentRole && $currentRole->id != $data["rol_id"]) {
            // Quitar el rol actual
            $user->removeRole($currentRole);
            // Asignar el nuevo rol
            $role = Role::findById($data["rol_id"]);
            $user->assignRole($role);
            $user->rol_id = $data["rol_id"];
        }
        // Guardar los cambios
        return $user->save();
    }

    public function Delete($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                throw new Exception("Fail to find the user", 1);
            }
            $user->projects()->detach();
            $user->delete();
            return true;
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }

    public function UserQuery()
    {
        return User::with('rol');
    }

    public function getRolUsers()
    {
        return Role::all(['id', 'name']);
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
