<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('discover'))->name('discover');
Route::get('/random', fn () => view('coming-soon', ['page' => 'Random']))->name('random');
Route::get('/u/{psn_id}', fn (string $psn_id) => view('coming-soon', ['page' => 'Profile']))->name('profile.show');
Route::get('/onboarding', fn () => view('coming-soon', ['page' => 'Onboarding']))->name('profile.edit');
Route::get('/login', fn () => view('coming-soon', ['page' => 'Login']))->name('login');
