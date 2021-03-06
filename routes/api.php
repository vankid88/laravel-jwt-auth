<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [AuthController::class, 'userProfile']);

    Route::get('/products', [ProductController::class, 'index']);
});


Route::group([
    'middleware' => 'auth:api',
], function ($router) {
    Route::get('/users', [AuthController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);
    
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::get('/products/{id}/show', [ProductController::class, 'show']);
    Route::post('/products/{id}/update', [ProductController::class, 'update']);
    Route::post('/products/{id}/delete', [ProductController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/getList', [CategoryController::class, 'getList']);
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::get('/categories/{id}/show', [CategoryController::class, 'show']);
    Route::post('/categories/{id}/update', [CategoryController::class, 'update']);
    Route::post('/categories/{id}/delete', [CategoryController::class, 'destroy']);
});