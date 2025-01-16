<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Home route
Route::get('/', function () {
    return view('home');
});

// Authentication Routes (Guest middleware to block authenticated users from accessing login and register pages)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
});

// Logout route (POST request)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Authenticated Routes (Only accessible for authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Your dashboard view
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Additional routes
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/options', function () {
    return view('options');
});

Route::get('/prices', function () {
    return view('prices');
});

// For the login route (to use the login functionality in the controller)
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// For the registration route (to use the registration functionality in the controller)
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/register/player', [RegisteredUserController::class, 'createPlayer'])->name('register.player');
Route::post('/register/player', [RegisteredUserController::class, 'storePlayer']);

Route::get('/register/coach', [RegisteredUserController::class, 'createCoach'])->name('register.coach');
Route::post('/register/coach', [RegisteredUserController::class, 'storeCoach']);

require __DIR__.'/auth.php'; // This includes all the default Laravel auth routes
