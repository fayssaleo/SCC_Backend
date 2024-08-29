<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Users\Http\Controllers\UsersController;





// Routes publiques (non protégées par 'auth:sanctum')
Route::group([
    'middleware' => 'api',
    'prefix' => 'api/users'
], function () {
    Route::post('/login', [UsersController::class, 'login']);
    Route::post('/register', [UsersController::class, 'create']);
});

// Routes protégées par 'auth:sanctum'
// Routes protégées par 'auth:sanctum'
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'api/users'
], function () {
    Route::post('/logout', [UsersController::class, 'logout']);
    Route::post('/reset-password', [UsersController::class, 'resetPassword']);
    Route::post('/logout', [UsersController::class, 'logout']);
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/{id}', [UsersController::class, 'get']);
    Route::post('/create', [UsersController::class, 'create']);
    // Route::put('/update/{id}', [UsersController::class, 'update']);
    // Route::delete('/delete/{id}', [UsersController::class, 'delete']);
    Route::post('/update', [UsersController::class, 'update']);
    Route::post('/delete', [UsersController::class, 'delete']);

    Route::post('/changePassword', [UsersController::class, 'changePassword']);
    Route::post('/resetPassword', [UsersController::class, 'resetPassword']);
    Route::post('/createAll', [UsersController::class, 'createAll']);
});
