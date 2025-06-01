<?php

use App\Livewire\Budget;
use App\Livewire\Dashboard;
use App\Livewire\Materials;
use App\Livewire\ShowUsers;
use App\Livewire\RealChapter;
use App\Livewire\ProyectoReal;
use App\Livewire\ShowProjects;
use App\Livewire\ChatComponent;
use App\Livewire\AttachmentsPage;
use App\Livewire\ShowWorkProgress;
use App\Livewire\EditPurchaseOrder;
use App\Livewire\QueryConsolidated;
use App\Livewire\QueryPurchaseOrder;
use App\Livewire\CreatePurchaseOrder;
use App\Livewire\ViewPurchaseOrderId;

use Illuminate\Support\Facades\Route;
use App\Livewire\PurchaseOrderByproject;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas relacionadas con la orden de compra
    Route::get('/purchaseorder/{projectId?}', QueryPurchaseOrder::class)->name('purchaseorder.view');
    Route::get('/purchaseorder/{id}', ViewPurchaseOrderId::class)->name('purchaseorder.get');
    Route::get('/consolidated/{id}', QueryConsolidated::class)->name('consolidated.view');
    Route::get('/purchaseorder/create/{id}', CreatePurchaseOrder::class)->name('purchaseorder.save');
    Route::get('/purchaseorder/edit/{id}', EditPurchaseOrder::class)
        // ->middleware()
        ->name('purchaseorder.edit');
    Route::get('/attachments/{invoiceHeaderId}', AttachmentsPage::class)->name('attachments.page');
    Route::get('/download-attachment/{id}', [AttachmentsPage::class, 'download'])->name('download.attachment');
    Route::get('/materiales', Materials::class)->name('materials.page');


    Route::get('/dashboard', Dashboard::class)->name('dashboard');



    // Rutas relacionadas con proyectos
    Route::get('/proyectos', ShowProjects::class)->name('projects.index');
    Route::get('/budget/{id_presupuesto}', Budget::class)->name('budget');
    Route::get('/proyecto-real/{id}', ProyectoReal::class)->name('proyecto-real');

    Route::get('/chat', ChatComponent::class)->name('chatprojects');
    Route::get('/chat/project/{id}', ChatComponent::class)->where('id', '[0-9]+')->name('chatbyid.get');


    // Rutas relacionadas con usuarios
    Route::get('/usuarios', ShowUsers::class)->name('usuarios.index');

    // Rutas relacionadas con el avance de obra
    Route::get('/workprogress/{id}', ShowWorkProgress::class)->name('workprogress.index');
});
