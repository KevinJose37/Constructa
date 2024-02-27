<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de importar el modelo de usuario

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Recupera las credenciales del formulario
        $credentials = $request->only('username', 'password');

        // Intenta autenticar al usuario
        if (auth()->attempt($credentials)) {
            // Autenticación exitosa, redirige al usuario a la página de inicio
            return redirect()->route('dashboard');
        } else {
            // Autenticación fallida, redirige al usuario de vuelta al formulario de inicio de sesión
            return redirect()->route('login')->with('error_message', 'Credenciales inválidas.');
        }
    }
}
