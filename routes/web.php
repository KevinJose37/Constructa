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
use App\Livewire\RedirectMaterials;

// Rutas de autenticación
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

	// Rutas relacionadas con la orden de compra
	Route::middleware(['permission:view.purchase'])->group(function () {
		Route::get('/purchaseorder/{projectId?}', QueryPurchaseOrder::class)->name('purchaseorder.view');
		Route::get('/purchaseorder/view/{id}', ViewPurchaseOrderId::class)->name('purchaseorder.get');
	});
	Route::middleware(['permission:store.purchase'])->group(function () {
		Route::get('/purchaseorder/create/{id}', CreatePurchaseOrder::class)->name('purchaseorder.save');
	});

	Route::middleware(['permission:update.purchase'])->group(function () {
		Route::get('/purchaseorder/edit/{id}', EditPurchaseOrder::class)->name('purchaseorder.edit');
	});

	Route::middleware(['permission:redirect.materials'])->group(function () {
		Route::get('/purchaseorder/redirect/{id}', RedirectMaterials::class)->name('purchaseorder.redirect');
	});

	// Consolidado
	Route::middleware(['permission:view.consolidated'])->group(function () {
		Route::get('/consolidated/{id}', QueryConsolidated::class)->name('consolidated.view');
	});

	Route::middleware(['permission:attachment.purchase'])->group(function () {
		Route::get('/attachments/{invoiceHeaderId}', AttachmentsPage::class)->name('attachments.page');
		Route::get('/download-attachment/{id}', [AttachmentsPage::class, 'download'])->name('download.attachment');
	});
	Route::middleware(['permission:view.materials'])->group(function () {
		Route::get('/materiales', Materials::class)->name('materials.page');
	});

	Route::middleware(['permission:view.dashboard'])->group(function () {
		Route::get('/dashboard', Dashboard::class)->name('dashboard');
	});

	// Rutas relacionadas con proyectos
	Route::middleware(['permission:view.project'])->group(function () {
		Route::get('/proyectos', ShowProjects::class)->name('projects.index');
	});

	// Presupuesto
	Route::middleware(['permission:view.budget'])->group(function () {
		Route::get('/budget/{id_presupuesto}', Budget::class)->name('budget');
	});

	// Proyecto real
	Route::middleware(['permission:view.realproject'])->group(function () {
		Route::get('/proyecto-real/{id}', ProyectoReal::class)->name('proyecto-real');
	});
	// Chat
	Route::middleware(['permission:view.chat'])->group(function () {
		Route::get('/chat', ChatComponent::class)->name('chatprojects');
		Route::get('/chat/project/{id}', ChatComponent::class)->where('id', '[0-9]+')->name('chatbyid.get');
	});

	// Usuarios
	Route::middleware(['permission:view.users'])->group(function () {
		Route::get('/usuarios', ShowUsers::class)->name('usuarios.index');
	});

	// Avance de obra
	Route::middleware(['permission:view.progress'])->group(function () {
		Route::get('/workprogress/{id}', ShowWorkProgress::class)->name('workprogress.index');
	});
	// Generar informes de avance
	Route::middleware(['permission:report.progress'])->group(function () {
		Route::get('/workprogress/report/{projectId}', \App\Livewire\WorkProgressReport::class)
			->name('printable.report');
	});
});
