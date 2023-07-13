<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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


Route::Post('register',[ AuthController::class,'register']);
Route::Post('login',[ AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function(){

    Route::Post('logout',[AuthController::class,'logout']);

    Route::Post('product/add',[ProductController::class,'store']);

    Route::Get('products',[ProductController::class,'index']);

    Route::Put('product/{id}/update',[ProductController::class,'update']);

    Route::Get('product/{id}/show',[ProductController::class,'show']);

    Route::Delete('product/{id}/delete',[ProductController::class,'destroy']);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
