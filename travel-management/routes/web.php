<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\IncidentController;

// Root URL shows registration form (GET, no route name)
Route::get('/', [RegisterController::class, 'showRegistrationForm']);

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');  // POST route named 'register'

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//Admin Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth','is.admin']);

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Protected home route (requires auth)
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

//Settings Roue
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');


// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/map', [MapController::class, 'show'])->name('map');

Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');

