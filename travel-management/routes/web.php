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
use App\Http\Controllers\ProfileController; // ✅ Added this line
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

// Admin Dashboard (Static)
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

// Admin: Get total user count
Route::get('/admin/users/count', function () {
    return response()->json(['count' => User::count()]);
})->name('admin.users.count');

// Admin: Get all users
Route::get('/admin/users/all', function () {
    return response()->json(User::select('id', 'name', 'email', 'is_admin', 'created_at')->orderBy('created_at', 'desc')->get());
})->name('admin.users.all');

Route::delete('/admin/users/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])
    ->name('admin.users.delete')
    ->middleware('auth');

// Admin Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/homeAdmin', [HomeAdminController::class, 'index'])->name('homeAdmin');
    Route::get('/view', [ViewAdminController::class, 'index'])->name('view');
    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');

    // Alerts: Last 5 failed login attempts and users
    Route::get('/admin/alerts', function () {
        $failedAttempts = FailedLogin::latest()->take(5)->get();
        $newUsers = User::latest()->take(5)->get();
        return view('admin.alerts', compact('failedAttempts', 'newUsers'));
    })->name('alerts');

    // Payment routes (for AJAX/modal)
    Route::get('/admin/payments/data', [PaymentController::class, 'getPaymentsData'])->name('admin.payments.data');
    Route::post('/admin/payments/confirm/{id}', [PaymentController::class, 'confirmPaymentById'])->name('admin.payments.confirm');
});

// ✅ Profile/Account Settings (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'index'])->name('settings');
    Route::post('/settings/update', [ProfileController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle-theme', [ProfileController::class, 'toggleTheme'])->name('settings.toggleTheme');
});

// Incident Reporting (User)
Route::get('/incident', [IncidentController::class, 'index']);
Route::get('/incident/create', [IncidentController::class, 'create'])->name('incident.create');
Route::post('/incident', [IncidentController::class, 'store'])->name('incident.store');

// Incident Reporting (Admin)
Route::get('/admin/incident', [IncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incident/{id}', [IncidentController::class, 'show'])->name('admin.incident.show');
Route::get('/admin/incidents', [AdminIncidentController::class, 'index'])->name('admin.incident');
Route::get('/admin/incidents/fetch', [AdminIncidentController::class, 'fetchIncidents'])->name('admin.incidents.fetch');

// API route to fetch incidents (used in dashboard JS) – includes location
Route::get('/incidents/fetch', function () {
    return \App\Models\Incident::select('title', 'description', 'lat', 'lng', 'created_at')->get();
})->name('incidents.fetch');

Route::get('/incidents/fetch', [IncidentController::class, 'fetch'])->name('incidents.fetch');

// Map View
Route::get('/map', [MapController::class, 'show'])->name('map');

// ✅ Premium Page (Static)
Route::get('/premium', function () {
    return view('premium');
})->name('premium');

// Payment Routes (User payment submission form)
Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment');
Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
