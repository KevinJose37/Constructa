<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\ChatProjectController;


// Rutas de autenticación
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {


    Route::get('/BlankPage', function () {
        return view('BlankPage');
    })->name('blankpage');
    
    // Rutas relacionadas con proyectos
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');

    Route::get('/chatprojects', [ChatProjectController::class, 'show'])->name('chatprojects');
    Route::post('/chatprojects', [ChatProjectController::class, 'saveMessageInProject'])->name('chatprojects.save');
    Route::get('/getMessagesByProject', [ChatProjectController::class, 'getMessagesByProject'])->name('chatprojects.messages');
    
    Route::get('/dashboard', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');

        // Rutas relacionadas con usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');

});

Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');
