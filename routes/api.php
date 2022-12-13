<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::namespace('API')->group(function() {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('check', [AuthController::class, 'check']);

    Route::post('regenerate', [UserController::class, 'regenerate']);
    Route::post('deactivate', [UserController::class, 'deactivate']);
    Route::post('lucky', [UserController::class, 'lucky']);
    Route::post('history', [UserController::class, 'history']);

    Route::prefix('admin')->group(function (){
       Route::post('login',[AdminController::class, 'login']);
    });
});
