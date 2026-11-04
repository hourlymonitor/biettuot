<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('chat.index') : view('welcome');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [App\Http\Controllers\ChatController::class, 'send'])->middleware('throttle:10,1')->name('chat.send');
    Route::get('/chat/{chatSession}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');

    Route::get('/content', [App\Http\Controllers\ContentController::class, 'index'])->name('content.index');
    Route::get('/content/create', [App\Http\Controllers\ContentController::class, 'create'])->name('content.create');
    Route::post('/content/generate', [App\Http\Controllers\ContentController::class, 'generate'])->middleware('throttle:5,1')->name('content.generate');
    Route::post('/content', [App\Http\Controllers\ContentController::class, 'store'])->name('content.store');
    Route::get('/content/{contentPost}', [App\Http\Controllers\ContentController::class, 'show'])->name('content.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
});
