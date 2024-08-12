<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'createCategory']);


    Route::get('/items', [CategoryController::class, 'getItems']);
    Route::post('/items', [CategoryController::class, 'createItem']);

    Route::get('/menus', [MenuController::class, 'index']);
    Route::post('/menus', [MenuController::class, 'createMenu']);
    Route::get('/menus/{id}', [MenuController::class, 'getMenu']);
    Route::post('/menus/menu-total-price', [MenuController::class, 'calculateTotalPrice']);

});



