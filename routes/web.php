<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\Auth\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');
Route::get('/dashboard', function () {
    return redirect()->route('filament.admin.pages.dashboard');
})->name('dashboard');
