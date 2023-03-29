<?php

use App\Http\Controllers\Api\AuthServiceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StoryServiceController;
use Illuminate\Support\Facades\Route;

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


Route::middleware('throttle')->group(function () {
    Route::post('/auth/login', [AuthServiceController::class, 'login']);
    Route::post('/auth/register', [AuthServiceController::class, 'register']);
    Route::post('/auth/forgot-password', [AuthServiceController::class, 'forgotPassword']);
    Route::post('/auth/forgot-password/verify', [AuthServiceController::class, 'verifyPasswordCode']);
});


Route::middleware('auth:sanctum')->group(function () {

    /**
     * auth route
     */
    Route::get('/user', [AuthServiceController::class, 'getProfile']);


    /**
     * story route
     */
    Route::get('/categories', CategoryController::class);
    Route::resource('stories', StoryServiceController::class)->except([
        'edit', 'update', 'create',
    ]);

});
