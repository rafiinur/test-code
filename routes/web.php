<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:api')->group(function () {
    // Merchant routes
    Route::post('/merchants/register', [MerchantController::class, 'register']);
    Route::post('/merchants/login', [MerchantController::class, 'login']);
    Route::get('/merchants/profile', [MerchantController::class, 'profile']);
    Route::put('/merchants/profile', [MerchantController::class, 'updateProfile']);
    
    // Menu routes
    Route::post('/menus', [MenuController::class, 'store']);
    Route::put('/menus/{menu}', [MenuController::class, 'update']);
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy']);
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});

Route::prefix('api')->middleware('auth:api')->group(function () {
    // API routes here
    Route::get('/merchants', [MerchantController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/menus', [MenuController::class, 'index']);
    Route::delete('/menus/{id}', [MenuController::class, 'delete']);
    Route::post('/menus', [MenuController::class, 'store']);
});
require __DIR__.'/auth.php';
