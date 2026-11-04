<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOperationsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('chat.index') : view('welcome'))->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->middleware('throttle:10,1')->name('chat.send');
    Route::get('/chat/{chatSession}', [ChatController::class, 'show'])->name('chat.show');

    Route::get('/content', [ContentController::class, 'index'])->name('content.index');
    Route::get('/content/create', [ContentController::class, 'create'])->name('content.create');
    Route::post('/content/generate', [ContentController::class, 'generate'])->middleware('throttle:5,1')->name('content.generate');
    Route::post('/content', [ContentController::class, 'store'])->name('content.store');
    Route::get('/content/{contentPost}', [ContentController::class, 'show'])->name('content.show');

    Route::get('/contributors', [ContributorController::class, 'dashboard'])->name('contributors.dashboard');
    Route::post('/contributors/apply', [ContributorController::class, 'apply'])->name('contributors.apply');
    Route::get('/points', [PointsController::class, 'index'])->name('points.index');
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->middleware('throttle:5,1')->name('rewards.redeem');
});

// Admin-only operations and dashboard.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/points/award', [PointsController::class, 'award'])->name('points.award');

    Route::get('/contributors', [AdminOperationsController::class, 'contributors'])->name('contributors');
    Route::patch('/contributors/{profile}', [AdminOperationsController::class, 'updateContributor'])->name('contributors.update');

    Route::get('/rewards', [AdminOperationsController::class, 'rewards'])->name('rewards');
    Route::post('/rewards', [AdminOperationsController::class, 'storeReward'])->name('rewards.store');
    Route::patch('/rewards/{reward}', [AdminOperationsController::class, 'updateReward'])->name('rewards.update');
    Route::patch('/reward-claims/{claim}', [AdminOperationsController::class, 'processClaim'])->name('reward-claims.process');
});

// Admins and moderators may review content and see operational reports.
Route::middleware(['auth', 'staff'])->group(function (): void {
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/admin/reports/ai.csv', [AnalyticsController::class, 'aiCsv'])->name('admin.reports.ai');
    Route::get('/admin/reports/moderation.csv', [AnalyticsController::class, 'moderationCsv'])->name('admin.reports.moderation');

    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::get('/moderation/{contentPost}', [ModerationController::class, 'show'])->name('moderation.show');
    Route::post('/moderation/{contentPost}/approve', [ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/{contentPost}/reject', [ModerationController::class, 'reject'])->name('moderation.reject');
});
