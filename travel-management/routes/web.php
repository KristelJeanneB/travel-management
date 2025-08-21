<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HomeAdminController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\ViewAdminController;
use App\Http\Controllers\AlertsController;
use App\Http\Controllers\AdminIncidentController;
use App\Models\FailedLogin;
use App\Models\User;

// Home / Registration
Route::get('/', [RegisterController::class, 'showRegistrationForm']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

// Password Reset
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Home (user)
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/homeAdmin', [HomeAdminController::class, 'index'])->name('homeAdmin');
    Route::get('/view', [ViewAdminController::class, 'index'])->name('view');
    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');

    // Alerts Route - shows failed login attempts and new users
    Route::get('/admin/alerts', function () {
        $failedAttempts = FailedLogin::latest()->take(5)->get(); // last 5 failed attempts
        $newUsers = User::latest()->take(5)->get(); // last 5 users
        return view('admin.alerts', compact('failedAttempts', 'newUsers'));
    })->name('alerts');
});

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

// Incident Reporting (user)
Route::get('/incident', [IncidentController::class, 'index']);
Route::get('/incident/create', [IncidentController::class, 'create'])->name('incident.create');
Route::post('/incident', [IncidentController::class, 'store'])->name('incident.store');
Route::post('/incidents', [IncidentController::class, 'store'])->name('incident.store');

// Incident Reporting (admin)
Route::get('/admin/incident', [IncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incident/{id}', [IncidentController::class, 'show'])->name('admin.incident.show');
Route::get('/admin/incidents', [AdminIncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incidents/fetch', [AdminIncidentController::class, 'fetchIncidents'])->name('admin.incidents.fetch');

// API route to fetch incidents (used in dashboard JS)
Route::get('/incidents/fetch', function () {
    return \App\Models\Incident::select('title', 'description', 'created_at')->get();
})->name('incidents.fetch');

// Map View
Route::get('/map', [MapController::class, 'show'])->name('map');
