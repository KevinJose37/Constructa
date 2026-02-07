<?php

use App\Livewire\Users\ShowUsers;
use App\Livewire\Projects\ShowProjects;
use App\Livewire\ChatComponent;
use App\Livewire\ShowWorkProgress;
use App\Livewire\QueryConsolidated;
use App\Livewire\PurchaseOrder\QueryPurchaseOrder;
use App\Livewire\PurchaseOrder\CreatePurchaseOrder;
use App\Livewire\PurchaseOrder\ViewPurchaseOrderId;
use App\Livewire\PurchaseOrder\PurchaseOrderByproject;
use App\Livewire\AttachmentsPage;

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
    Route::get('/purchaseorder/project/{id}', PurchaseOrderByproject::class)->name('purchaseorderproject.get');
    Route::get('/consolidated/{id}', QueryConsolidated::class)->name('consolidated.view');
    Route::get('/purchaseorder/create/{id}', CreatePurchaseOrder::class)->name('purchaseorder.save');
    Route::get('/attachments/{invoiceHeaderId}', AttachmentsPage::class)->name('attachments.page');
    Route::get('/download-attachment/{id}', [AttachmentsPage::class, 'download'])->name('download.attachment');

    Route::get('/dashboard', function () {
        return view('DashboardIndex');
    })->name('dashboard.index');


    // Rutas relacionadas con proyectos
    Route::get('/proyectos', ShowProjects::class)->name('projects.index');

    // Rutas relacionadas con chats

    Route::get('/chat', ChatComponent::class)->name('chatprojects');
    Route::get('/chat/project/{id}', ChatComponent::class)->where('id', '[0-9]+')->name('chatbyid.get');


    // Rutas relacionadas con usuarios
    Route::get('/usuarios', ShowUsers::class)->name('usuarios.index');

    // Rutas relacionadas con el avance de obra
    Route::get('/workprogress/{id}', ShowWorkProgress::class)->name('workprogress.index');


});
