<?php

use VOLLdigital\LaravelImmobilienscout24\Http\Controllers\ImmobilienscoutController;

Route::middleware(['web'])->prefix('immobilienscout')->group(function() {
    Route::get('/authorize', [ImmobilienscoutController::class, 'authorize']);
    Route::get('/callback', [ImmobilienscoutController::class, 'callback']);
});