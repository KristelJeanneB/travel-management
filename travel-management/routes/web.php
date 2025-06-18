<?php

use Illuminate\Support\Facades\Route;

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
