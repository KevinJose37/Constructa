<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\User;

class ShareUserData
{
    public function handle($request, Closure $next)
    {
        $users = User::all();
        View::share('users', $users);
        
        return $next($request);
    }
}
