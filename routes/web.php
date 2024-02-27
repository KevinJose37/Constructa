<?php

use Illuminate\Support\Facades\Route;

Route::get('/BlankPage', function () {
    return view('BlankPage');
})->name('blankpage');

Route::get('/DashboardIndex', function () {
    return view('DashboardIndex');
})->name('dashboardindex');

Route::get('/LoginConstructa', function () {
    return view('LoginConstructa');
})->name('loginconstructa');

Route::get('/Proyectos', function () {
    return view('Proyectos');
});
