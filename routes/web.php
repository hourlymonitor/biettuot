<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/contributors', [App\Http\Controllers\ContributorController::class, 'dashboard'])->name('contributors.dashboard');
    Route::post('/contributors/apply', [App\Http\Controllers\ContributorController::class, 'apply'])->name('contributors.apply');

    Route::get('/points', [App\Http\Controllers\PointsController::class, 'index'])->name('points.index');
    Route::post('/points/award', [App\Http\Controllers\PointsController::class, 'award'])->name('points.award');

    Route::get('/rewards', [App\Http\Controllers\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [App\Http\Controllers\RewardController::class, 'redeem'])->name('rewards.redeem');
});
