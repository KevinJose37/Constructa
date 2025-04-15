<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; // Importa la clase Log

class ForceLightTheme
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Forzar el tema light en la sesión
        session(['themeConfig' => [
            'theme' => 'light',
            'nav' => 'vertical',
            'layout' => ['mode' => 'fluid', 'position' => 'fixed'],
            'topbar' => ['color' => 'light'],
            'menu' => ['color' => 'light'], // Asegúrate de que el menú esté en modo light
            'sidenav' => ['size' => 'default', 'user' => false],
        ]]);

        return $next($request);
    }
}
