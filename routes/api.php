<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){
    
    // Users 
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    // Route::get('users/{id}', [UserController::class, 'show']);
    // Route::put('users/{id}', [UserController::class, 'update']);
    // Route::delete('users/{id}', [UserController::class, 'destroy']);

    // // Projects
    // Route::get('projects/users', [ProjectUserController::class, 'index']);
    // Route::get('projects/{idProject}/users', [ProjectUserController::class, 'show']);
    // Route::post('projects/assign', [ProjectUserController::class, 'store']);

    // Route::get('projects', [ProjectController::class, 'index']);
    // Route::post('projects', [ProjectController::class, 'store']);
    // Route::get('projects/{id}', [ProjectController::class, 'show']);
    // Route::put('projects/{id}', [ProjectController::class, 'update']);
    // Route::delete('projects/{id}', [ProjectController::class, 'destroy']);

});




