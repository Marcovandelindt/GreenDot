<?php

use App\Http\Controllers\Auth\EmailVerificationNoticeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendVerificationEmailController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Discover\DiscoverController;
use Illuminate\Support\Facades\Route;

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated routes (email not required to be verified here)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', EmailVerificationNoticeController::class)->name('verification.notice');
    Route::post('/email/verify', VerifyEmailController::class)->name('verification.verify');
    Route::post('/email/verification-notification', ResendVerificationEmailController::class)->name('verification.send')->middleware('throttle:6,1');
});

// Authenticated + verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DiscoverController::class)->name('discover');
    Route::get('/random', fn () => view('coming-soon', ['page' => 'Random']))->name('random');
    Route::get('/u/{psn_id}', fn (string $psn_id) => view('coming-soon', ['page' => 'Profile']))->name('profile.show');
    Route::get('/onboarding', fn () => view('coming-soon', ['page' => 'Onboarding']))->name('profile.edit');
});

// Logout (auth not required as a guard — Laravel handles gracefully)
Route::post('/logout', LogoutController::class)->name('logout');
