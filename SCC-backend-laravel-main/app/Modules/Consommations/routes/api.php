<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Consommations\Http\Controllers\ConsommationsController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'api'
], function () {
    Route::get('/consommations', [ConsommationsController::class, 'index'])->name('consommations.index');
    Route::get('/consommations/{id}', [ConsommationsController::class, 'show'])->name('consommations.show');
    Route::put('/consommations/{id}', [ConsommationsController::class, 'update'])->name('consommations.update');
    Route::delete('/consommations/{id}', [ConsommationsController::class, 'delete'])->name('consommations.delete');
   
    Route::get('/statistics', [ConsommationsController::class, 'getStatistics'])->name('statistics.get');
    Route::post('/consommations/store', [ConsommationsController::class, 'store']);
    Route::get('/consommations/anomalies/{year}/{month}', [ConsommationsController::class, 'getMonthlyAnomaliesForMonth'])->name('consommations.anomalies.month');

    Route::get('/consommations/month/{month}', [ConsommationsController::class, 'getMonthlyConsumption']);
    Route::get('/consommations/months-with-consumptions', [ConsommationsController::class, 'getMonthsWithConsumptions']);
    Route::get('/getMonthlyConsumptions', [ConsommationsController::class, 'getMonthlyConsumptions']);

// Exemple de définition de route dans api.php
Route::get('/consommations/check', [ConsommationsController::class, 'checkConsumptionExistence']);

    
});
