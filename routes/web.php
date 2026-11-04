<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/contributors', [App\Http\Controllers\AdminOperationsController::class, 'contributors'])->name('contributors');
    Route::patch('/contributors/{profile}', [App\Http\Controllers\AdminOperationsController::class, 'updateContributor'])->name('contributors.update');
    Route::get('/rewards', [App\Http\Controllers\AdminOperationsController::class, 'rewards'])->name('rewards');
    Route::post('/rewards', [App\Http\Controllers\AdminOperationsController::class, 'storeReward'])->name('rewards.store');
    Route::patch('/rewards/{reward}', [App\Http\Controllers\AdminOperationsController::class, 'updateReward'])->name('rewards.update');
    Route::patch('/reward-claims/{claim}', [App\Http\Controllers\AdminOperationsController::class, 'processClaim'])->name('reward-claims.process');
});
