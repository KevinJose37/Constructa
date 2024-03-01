<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

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
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::get('/Proyectos', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/Proyectos/store', [ProjectController::class, 'store'])->name('projects.store');
        Route::put('/Proyectos/{id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/Proyectos/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    Route::get('/DashboardIndex', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');
    
    Route::get('/Usuarios', [UserController::class, 'index'])->name('usuarios.index');

});

Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');
