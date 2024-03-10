<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use App\Validators\UserValidator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    protected $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $filter = $request->input('filter');
        $users = $this->userService->getAllPaginate($filter);

        return view('Usuarios', compact('users', 'filter')); // Pasar el usuario a la vista
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|email|unique:users,email',
            'rol_id' => 'required|exists:roles,id' // Asegúrate de que existe el rol en la tabla 'roles'
        ]);

        try {
            // Crear un nuevo usuario con los datos validados
            $user = new User();
            $user->name = $validatedData['name'];
            $user->password = bcrypt($validatedData['password']);
            $user->email = $validatedData['email'];
            $user->rol_id = $validatedData['rol_id']; // Asignar el rol_id del formulario al modelo User
            $user->save();

            // Redirigir a alguna parte o devolver una respuesta
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra durante la creación
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al crear el usuario: ' . $e->getMessage()]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validator = UserValidator::validateId(['id' => $id]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Bad request",
                'errors' => $validator->errors()
            ], 400);
        }

        $userInfo = $this->userService->getById($id);
        if ($userInfo === null) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado',
                'errors' => ''
            ], 404);
        }

        return response()->json($this->userService->getById($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario de edición
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user_password' => 'nullable|string|min:8',
            'email' => 'required|email|unique:users,email,' . $id,
            'rol_id' => 'required|exists:roles,id'
        ]);

         // Obtener el proyecto a actualizar
         $user = User::findOrFail($request->input('user_id'));

         // Actualizar los datos del proyecto
         $user->name = $request->input('name');
         $user->password = $request->input('user_password');
         $user->email = $request->input('email');
         $user->rol_id = $request->input('rol_id');
 
         // Guardar los cambios
         $user->save();
 
         // Redireccionar a la página de proyectos u otra acción deseada
         return redirect()->route('usuarios.index')->with('success', '¡Proyecto actualizado exitosamente!');

        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
}
