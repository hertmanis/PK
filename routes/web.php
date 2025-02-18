<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ManageTeamController;
use App\Http\Controllers\PracticeController;

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

// Guest-only routes (Authentication: Login & Registration)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Role-specific registration routes
    Route::get('/register/player', [RegisteredUserController::class, 'createPlayer'])->name('register.player');
    Route::post('/register/player', [RegisteredUserController::class, 'storePlayer'])->name('register.player.store');

    Route::get('/register/coach', [RegisteredUserController::class, 'createCoach'])->name('register.coach');
    Route::post('/register/coach', [RegisteredUserController::class, 'storeCoach'])->name('register.coach.store');
});

// Authenticated-only routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        
        \Log::info('User Role:', ['role' => $user->role]);

        if ($user->role == 0) { // Coach
            return view('dashboard.coach-dashboard');
        } elseif ($user->role == 1) { // Player
            return view('dashboard.player-dashboard');
        } else {
            return abort(403, 'Unauthorized Role');
        }
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Public pages
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/options', function () {
    return view('options');
})->name('options');

Route::get('/prices', function () {
    return view('prices');
})->name('prices');

Route::get('/manage-team', [ManageTeamController::class, 'index'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/practices', [PracticeController::class, 'index'])->name('practices.index');
    Route::get('/practices/create', [PracticeController::class, 'create'])->name('practices.create');
    Route::post('/practices', [PracticeController::class, 'store'])->name('practices.store');
    Route::delete('/practices/{id}', [PracticeController::class, 'destroy'])->name('practices.destroy');
});

Route::patch('/profile/change-password', [ProfileController::class, 'changePassword'])
    ->middleware('auth')
    ->name('profile.change-password');

// Include additional default Laravel auth routes
require __DIR__.'/auth.php';
