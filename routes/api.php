<?php

use App\Actions\CreateNewPost;
use App\Actions\DeletePost;
use App\Actions\ReadAllPosts;
use App\Actions\UpdatePostBody;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\UserProductController;
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
    //Routes for UserProductController

    Route::get('users/{user}/products', [UserProductController::class, 'index']);

    Route::post('users/{user}/products', [UserProductController::class, 'store']);

    Route::delete('users/{user}/products/{product}', [UserProductController::class, 'destroy']);

    //Routes for ProductController

    Route::get('products', [ProductContoller::class, 'index']);

    Route::get('products/{product:name}', [ProductContoller::class, 'show']);

    Route::post('products', [ProductContoller::class, 'store']);

    Route::put('products/{product}', [ProductContoller::class, 'update']);

    Route::delete('products/{product}', [ProductContoller::class, 'destroy']);

    Route::post('logout', [AuthController::class, 'logout']);

    //Routes for Post Actions

    Route::get('posts', ReadAllPosts::class);
    Route::post('posts', CreateNewPost::class);
    Route::put('posts/{post}', UpdatePostBody::class);
    Route::delete('posts/{post}', DeletePost::class);
});
