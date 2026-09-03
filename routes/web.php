<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ShortLinkController as AdminShortLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// User (Auth required)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/short-links', [ShortLinkController::class, 'index'])->name('short-links.index');
    Route::get('/short-links/create', [ShortLinkController::class, 'create'])->name('short-links.create');
    Route::post('/short-links', [ShortLinkController::class, 'store'])->name('short-links.store');
    Route::get('/short-links/{shortLink}', [ShortLinkController::class, 'show'])->name('short-links.show');
    Route::get('/short-links/{shortLink}/qr-code', [ShortLinkController::class, 'qrCode'])->name('short-links.qr-code');
    Route::delete('/short-links/{shortLink}', [ShortLinkController::class, 'destroy'])->name('short-links.destroy');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // Categories CRUD
    Route::resource('/categories', CategoryController::class)->except(['show', 'create']);
});

// Admin (Auth + Admin required)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/short-links', [AdminShortLinkController::class, 'index'])->name('short-links.index');
    Route::get('/short-links/{shortLink}', [AdminShortLinkController::class, 'show'])->name('short-links.show');
    Route::patch('/short-links/{shortLink}/toggle', [AdminShortLinkController::class, 'toggleActive'])->name('short-links.toggle');
    Route::delete('/short-links/{shortLink}', [AdminShortLinkController::class, 'destroy'])->name('short-links.destroy');
});

// Short Link Redirect - MUST be LAST to avoid catching other routes
Route::get('/{shortCode}', [ShortLinkController::class, 'redirect'])->name('short-links.redirect');
