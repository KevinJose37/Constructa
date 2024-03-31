<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChatProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Livewire\CreatePurchaseOrder;

// Rutas de autenticación
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/purchaseorder', function () { return view('PurchaseOrder'); })->name('purchaseorder');
    Route::get('/purchaseorder/{id}', CreatePurchaseOrder::class)->name('purchaseorder.save');

    
    Route::get('/dashboard', function () { return view('DashboardIndex'); })->name('dashboard.index');

    // Rutas relacionadas con proyectos
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');

    // Rutas relacionadas con chats

    Route::get('/chatprojects', [ChatProjectController::class, 'show'])->name('chatprojects');
    Route::post('/chatprojects', [ChatProjectController::class, 'saveMessageInProject'])->name('chatprojects.save');
    Route::get('/getMessagesByProject', [ChatProjectController::class, 'getMessagesByProject'])->name('chatprojects.messages');
    
    Route::get('/dashboard', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');

    // Rutas relacionadas con usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
});
