<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MidtransPaymentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing-page'); // We'll create this view
})->name('landing');

Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');
Route::get('/dashboard', function () {
    return redirect()->route('filament.admin.pages.dashboard');
})->name('dashboard');

// User Dashboard/Profile and Logout
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout Route
    Route::post('/logout', function () {
        Auth::logout(); // Log the user out
        request()->session()->invalidate(); // Invalidate the session
        request()->session()->regenerateToken(); // Regenerate CSRF token
        return redirect('/'); // Redirect to landing page after logout
    })->name('logout');
});


// ... other routes

// Route to initiate Midtrans payment (e.g., from a user's reservation detail page)
Route::get('/reservation/{reservation}/pay-midtrans', [MidtransPaymentController::class, 'initiatePayment'])
    ->name('reservation.pay.midtrans');

// Midtrans redirect URLs (user is sent here after completing/cancelling payment on Midtrans side)
Route::get('/reservation/{reservation}/payment/finish', [MidtransPaymentController::class, 'handleFinish'])->name('midtrans.finish');
Route::get('/reservation/{reservation}/payment/unfinish', [MidtransPaymentController::class, 'handleUnfinish'])->name('midtrans.unfinish');
Route::get('/reservation/{reservation}/payment/error', [MidtransPaymentController::class, 'handleError'])->name('midtrans.error');
