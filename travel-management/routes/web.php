<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('register');
});
use App\Http\Controllers\Auth\RegisterController;

// Show registration form
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Handle registration
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', function () {
    return view('register');
});

//Login
use App\Http\Controllers\Auth\LoginController;

// routes/web.php

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

use App\Http\Controllers\Auth\PasswordResetController;
// Show forgot password form
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');

// Handle email submission
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');;

// Show reset password form
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

// Handle reset password
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Home route
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');
// Handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//route exits
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');
