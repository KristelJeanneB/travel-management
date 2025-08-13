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

Route::get('/', [RegisterController::class, 'showRegistrationForm']);

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Protected home route (requires auth)
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

//Settings Route
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

//Admin Home Route

Route::get('/homeAdmin', [HomeAdminController::class, 'index'])
    ->name('homeAdmin')
    ->middleware('web'); 

//Admin View Map
Route::get('/view', [ViewAdminController::class, 'index'])
    ->name('view')
    ->middleware('web');

//Admin Alerts

Route::get('alerts', [AlertsController::class, 'index'])
    ->name('alerts')
    ->middleware('auth');

//Admin Settings
Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');

//Admin Incidents
Route::get('/admin/incident', [IncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incident/{id}', [IncidentController::class, 'show'])->name('admin.incident.show');

Route::get('/incident', [IncidentController::class, 'index']);
Route::get('/report-incident', [IncidentController::class, 'create'])->name('incident.create');
Route::post('/incident', [IncidentController::class, 'store'])->name('incident.store');
Route::get('/incident/create', [IncidentController::class, 'create'])->name('incident.create');

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/map', [MapController::class, 'show'])->name('map');

Route::post('/incidents', [IncidentController::class, 'store'])->name('incident.store');

Route::get('/admin/incidents', [AdminIncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incidents/fetch', [AdminIncidentController::class, 'fetchIncidents'])->name('admin.incidents.fetch');

Route::get('/incidents/fetch', function() {
    return \App\Models\Incident::select('title','description', 'created_at')->get();
})->name('incidents.fetch');

