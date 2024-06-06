<?php

use App\Livewire\ShowUsers;
use App\Livewire\ShowProjects;
use App\Livewire\ChatComponent;
use App\Livewire\ShowWorkProgress;
use App\Livewire\QueryPurchaseOrder;
use App\Livewire\CreatePurchaseOrder;
use App\Livewire\ViewPurchaseOrderId;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas relacionadas con la orden de compra
    Route::get('/purchaseorder', QueryPurchaseOrder::class)->name('purchaseorder.view');
    Route::get('/purchaseorder/{id}', ViewPurchaseOrderId::class)->name('purchaseorder.get');
    Route::get('/purchaseorder/create/{id}', CreatePurchaseOrder::class)->name('purchaseorder.save');

    
    Route::get('/dashboard', function () {
        return view('DashboardIndex');
    })->name('dashboard.index');


    // Rutas relacionadas con proyectos
    Route::get('/proyectos', ShowProjects::class)->name('projects.index');

    // Rutas relacionadas con chats

    Route::get('/chatprojects', ChatComponent::class)->name('chatprojects');

    // Rutas relacionadas con usuarios
    Route::get('/usuarios', ShowUsers::class)->name('usuarios.index');

    // Rutas relacionadas con el avance de obra
    Route::get('/workprogress', ShowWorkProgress::class)->name('workprogress.index');


});
