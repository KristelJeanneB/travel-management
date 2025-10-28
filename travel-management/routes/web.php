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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosoAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SensorController;
use App\Models\FailedLogin;
use App\Models\User;
use App\Http\Controllers\PosoController;

// Test Firebase SSL
Route::get('/test-firebase', function () {
    try {
        $client = new \GuzzleHttp\Client();
        $client->get('https://firebase.google.com');
        return '✅ SSL connection works!';
    } catch (\Exception $e) {
        return '❌ SSL Error: ' . $e->getMessage();
    }
});

// Home / Registration
Route::get('/', [RegisterController::class, 'showRegistrationForm']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // Home / Dashboard
    Route::get('/home', function () { return view('home'); })->name('home');
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Profile / Settings
    Route::get('/settings', [ProfileController::class, 'index'])->name('settings');
    Route::post('/settings/update', [ProfileController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle-theme', [ProfileController::class, 'toggleTheme'])->name('settings.toggleTheme');

    // Incident Reporting (User)
    Route::get('/incident', [IncidentController::class, 'index']);
    Route::get('/incident/create', [IncidentController::class, 'create'])->name('incident.create');
    Route::post('/incident', [IncidentController::class, 'store'])->name('incident.store');
    Route::post('/incidents/{id}/resolve', [IncidentController::class, 'resolve'])->name('incidents.resolve');
    Route::post('/incidents/{id}/update-status', [IncidentController::class, 'updateStatus']);
    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
    Route::get('/incidents/fetch', [IncidentController::class, 'fetch'])->name('incidents.fetch');

    // Payment Routes (User)
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment');
    Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
});

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/homeAdmin', [HomeAdminController::class, 'index'])->name('homeAdmin');
    Route::get('/view', [ViewAdminController::class, 'index'])->name('view');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');

    // Alerts
    Route::get('/alerts', function () {
        $failedAttempts = FailedLogin::latest()->take(5)->get();
        $newUsers = User::latest()->take(5)->get();
        return view('admin.alerts', compact('failedAttempts', 'newUsers'));
    })->name('alerts');

    // Payments
    Route::get('/payments/data', [PaymentController::class, 'getPaymentsData'])->name('admin.payments.data');
    Route::post('/payments/confirm/{id}', [PaymentController::class, 'confirmPaymentById'])->name('admin.payments.confirm');
    Route::delete('/payments/delete/{id}', [PaymentController::class, 'destroy'])->name('admin.payments.delete');

    // Users
    Route::get('/users/count', fn() => response()->json(['count' => User::count()]))->name('admin.users.count');
    Route::get('/users/all', fn() => response()->json(User::select('id', 'name', 'email', 'is_admin', 'created_at')->orderBy('created_at', 'desc')->get()))->name('admin.users.all');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('admin.users.delete');

    // Incidents
    Route::get('/incidents', [AdminIncidentController::class, 'index'])->name('admin.incidents');
    Route::get('/incidents/fetch', [AdminIncidentController::class, 'fetchIncidents'])->name('admin.incidents.fetch');
    Route::get('/incident/{id}', [IncidentController::class, 'show'])->name('admin.incident.show');

    // Sensor location update
    Route::put('/sensor-location/{id}', [SensorController::class, 'update'])->name('admin.update-sensor-location');
});

// Super Admin Routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
});

// Map & Premium
Route::get('/map', [MapController::class, 'show'])->name('map');
Route::get('/premium', fn() => view('premium'))->name('premium');

// Migration utilities
Route::get('/migrate-users', [UserController::class, 'migrateUsers']);
Route::get('/migrate-incidents', [IncidentController::class, 'migrateIncidents']);

// Poso Routes

// Public registration
Route::get('/poso/register', [PosoAuthController::class, 'showRegistrationForm'])->name('poso.register');
Route::post('/poso/register', [PosoAuthController::class, 'storePoso'])->name('poso.register.store');

// Protected Poso dashboard
Route::middleware(['auth', 'role:poso,admin,superadmin'])->group(function () {
    Route::get('/poso/dashboard', fn() => view('poso.dashboard'))->name('poso.dashboard');
    Route::get('/poso/report', [PosoController::class, 'create'])->name('poso.report');
    Route::post('/poso/report', [PosoController::class, 'store'])->name('poso.report.store');
});

// Admin approval/rejection for incidents
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/admin/incidents/pending', [IncidentController::class, 'pendingIncidents'])->name('admin.incidents.pending');
    Route::post('/admin/incidents/{id}/approve', [IncidentController::class, 'approveIncident'])->name('admin.incidents.approve');
    Route::post('/admin/incidents/{id}/reject', [IncidentController::class, 'rejectIncident'])->name('admin.incidents.reject');
});
// routes/web.php
Route::get('/api/incidents/poso', [IncidentController::class, 'fetchPoso'])->name('incidents.fetchPoso');

Route::get('/incidents/fetch', [IncidentController::class, 'fetch'])->name('incidents.fetch');
Route::get('/incidents/fetch-poso', [IncidentController::class, 'fetchPoso'])->name('incidents.fetchPoso');


