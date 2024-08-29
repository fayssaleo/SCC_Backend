<?php

use App\Modules\Consommateurs\Http\Controllers\ConsommateursController;
use Illuminate\Support\Facades\Route;

// Routes pour les consommateurs avec middleware CORS
Route::group([
    'middleware' => 'api',
    'prefix' => 'api/consommateurs'
], function () {
    Route::get('/', [ConsommateursController::class, 'index'])->name('consommateurs.index');
    Route::get('/{id}', [ConsommateursController::class, 'get'])->name('consommateurs.get');
    Route::put('/{id}', [ConsommateursController::class, 'update'])->name('consommateurs.update');
    Route::delete('/{id}', [ConsommateursController::class, 'delete'])->name('consommateurs.delete');
    Route::post('/login', [ConsommateursController::class, 'login'])->name('consommateurs.login');
    Route::post('/store', [ConsommateursController::class, 'store']);
    Route::post('consommateurs/ids-by-usernames', [ConsommateursController::class, 'getIdsByUsernames']);

});
