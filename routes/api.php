<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductUserController;
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

    Route::post('users/{user}/product', [ProductUserController::class, 'store']);

    Route::get('users/{user}/products', [ProductUserController::class, 'index']);

    Route::put('users/{user}/products/{product}', [ProductUserController::class, 'update']);

    // Showing products by name, assuming they have unique names (just to test explicit route model binding)

    Route::get('users/{user}/products/{product:name}', [ProductUserController::class, 'show']);

    Route::delete('users/{user}/products/{product}', [ProductUserController::class, 'destroy']);
});
