<?php
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/BlankPage', function () {
        return view('BlankPage');
    })->name('blankpage');

    // Ruta para mostrar la lista de proyectos
    Route::get('/Proyectos', [ProjectController::class, 'index'])->name('projects.index');

    // Ruta para guardar un nuevo proyecto
    Route::post('/Proyectos/store', [ProjectController::class, 'store'])->name('projects.store');
    // Ruta para actualizar un nuevo proyecto
    Route::put('/proyectos/{id}', [ProjectController::class, 'update'])->name('projects.update');

    Route::delete('/proyectos/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');


    Route::get('/DashboardIndex', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');
});



Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');
