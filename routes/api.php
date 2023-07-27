<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\UserProductController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    //Routes for UserProductController

    // Route::get('users/{user}/products', [UserProductController::class, 'index']);

    // Route::post('users/{user}/products', [UserProductController::class, 'store']);

    // Route::delete('users/{user}/products/{product}', [UserProductController::class, 'destroy']);

    Orion::resource('products', ProductContoller::class);

    Orion::belongsToManyResource('users', 'products', UserProductController::class);
});
