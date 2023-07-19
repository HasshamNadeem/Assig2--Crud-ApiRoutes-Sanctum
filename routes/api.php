<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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
    Route::post('logout', [AuthController::class, 'logout']);

    //Below routes now use route model binding

    Route::post('product', [ProductController::class, 'store']);

    Route::get('products', [ProductController::class, 'index']);

    Route::put('product/{id}', [ProductController::class, 'update']);

    // Showing products by name, assuming they have unique names (just to test explicit route model binding)

    Route::get('product/{key:name}', [ProductController::class, 'show']);

    Route::delete('product/{id}', [ProductController::class, 'destroy']);
});
