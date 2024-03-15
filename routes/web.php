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


    Route::get('/purchaseorder', function () {
        return view('PurchaseOrder');
    })->name('purchaseorder');
    
    // Rutas relacionadas con proyectos
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/proyectos/chat', function(){ return view('ChatProjects');})->name('project.chat');
        Route::post('/proyectos/store', [ProjectController::class, 'store'])->name('projects.store');
        Route::put('/proyectos/{id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/proyectos/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    });


    Route::get('/chatprojects', [ChatProjectController::class, 'show'])->name('chatprojects');
    Route::post('/chatprojects', [ChatProjectController::class, 'saveMessageInProject'])->name('chatprojects.save');
    Route::get('/getMessagesByProject', [ChatProjectController::class, 'getMessagesByProject'])->name('chatprojects.messages');





    

    
    Route::get('/dashboard', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');

        // Rutas relacionadas con usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');

});

Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');
