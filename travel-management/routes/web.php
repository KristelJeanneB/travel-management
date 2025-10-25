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
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use App\Models\FailedLogin;
use App\Models\User;

// Test Firebase SSL
Route::get('/test-firebase', function () {
    try {
        $client = new \GuzzleHttp\Client();
        $res = $client->get('https://firebase.google.com');
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
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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

// Admin Routes using .env credentials
Route::middleware(['auth', 'is.admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

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
    Route::get('/users/count', function () {
        return response()->json(['count' => User::count()]);
    })->name('admin.users.count');

    Route::get('/users/all', function () {
        return response()->json(User::select('id', 'name', 'email', 'is_admin', 'created_at')
            ->orderBy('created_at', 'desc')->get());
    })->name('admin.users.all');

    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('admin.users.delete');

    // Incidents (Admin)
    Route::get('/incidents', [AdminIncidentController::class, 'index'])->name('admin.incidents');
    Route::get('/incidents/fetch', [AdminIncidentController::class, 'fetchIncidents'])->name('admin.incidents.fetch');
    Route::get('/incident/{id}', [IncidentController::class, 'show'])->name('admin.incident.show');
});

// Super Admin Routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
});

// Map
Route::get('/map', [MapController::class, 'show'])->name('map');

// Premium Page
Route::get('/premium', function () {
    return view('premium');
})->name('premium');

// Migration (utility routes)
Route::get('/migrate-users', [UserController::class, 'migrateUsers']);
Route::get('/migrate-incidents', [IncidentController::class, 'migrateIncidents']);
