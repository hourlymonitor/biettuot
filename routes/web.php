<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('chat.index') : view('welcome'))->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [App\Http\Controllers\ChatController::class, 'send'])->middleware('throttle:10,1')->name('chat.send');
    Route::get('/chat/{chatSession}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');

    Route::get('/content', [App\Http\Controllers\ContentController::class, 'index'])->name('content.index');
    Route::get('/content/create', [App\Http\Controllers\ContentController::class, 'create'])->name('content.create');
    Route::post('/content/generate', [App\Http\Controllers\ContentController::class, 'generate'])->middleware('throttle:5,1')->name('content.generate');
    Route::post('/content', [App\Http\Controllers\ContentController::class, 'store'])->name('content.store');
    Route::get('/content/{contentPost}', [App\Http\Controllers\ContentController::class, 'show'])->name('content.show');

    Route::get('/contributors', [App\Http\Controllers\ContributorController::class, 'dashboard'])->name('contributors.dashboard');
    Route::post('/contributors/apply', [App\Http\Controllers\ContributorController::class, 'apply'])->name('contributors.apply');
    Route::get('/points', [App\Http\Controllers\PointsController::class, 'index'])->name('points.index');
    Route::get('/rewards', [App\Http\Controllers\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [App\Http\Controllers\RewardController::class, 'redeem'])->middleware('throttle:5,1')->name('rewards.redeem');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/points/award', [App\Http\Controllers\PointsController::class, 'award'])->name('points.award');
});

Route::middleware(['auth', 'staff'])->group(function (): void {
    Route::get('/admin/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/admin/reports/ai.csv', [App\Http\Controllers\AnalyticsController::class, 'aiCsv'])->name('admin.reports.ai');
    Route::get('/admin/reports/moderation.csv', [App\Http\Controllers\AnalyticsController::class, 'moderationCsv'])->name('admin.reports.moderation');
    Route::get('/moderation', [App\Http\Controllers\ModerationController::class, 'index'])->name('moderation.index');
    Route::get('/moderation/{contentPost}', [App\Http\Controllers\ModerationController::class, 'show'])->name('moderation.show');
    Route::post('/moderation/{contentPost}/approve', [App\Http\Controllers\ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/{contentPost}/reject', [App\Http\Controllers\ModerationController::class, 'reject'])->name('moderation.reject');
});
