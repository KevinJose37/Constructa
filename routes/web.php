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

    Route::get('/Proyectos', [ProjectController::class, 'index']);

    Route::get('/DashboardIndex', function () {
        return view('DashboardIndex');
    })->name('dashboardindex');
});



Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');

<<<<<<< HEAD
Route::get('/Proyectos', function () {
    return view('Proyectos');
});
=======

>>>>>>> origin/Dev/Kevin
