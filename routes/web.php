<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MidtransPaymentController;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing-page'); // We'll create this view
})->name('landing');

Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');
// Route::get('/dashboard', function () {
//     return redirect()->route('filament.admin.pages.dashboard');
// })->name('dashboard');

// User Dashboard/Profile and Logout
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Fetch reservations for the authenticated user
        $user = Auth::user();
        $reservations = $user->reservations()->with(['service', 'doctor'])->orderByDesc('scheduled_date')->get();
        return view('dashboard', compact('reservations'));
    })->name('dashboard');

    // Logout Route
    Route::post('/logout', function () {
        Auth::logout(); // Log the user out
        request()->session()->invalidate(); // Invalidate the session
        request()->session()->regenerateToken(); // Regenerate CSRF token
        return redirect('/'); // Redirect to landing page after logout
    })->name('logout');
    // Route to show a single reservation (for user to view their own)
    Route::get('/reservations/{reservation}', function (Reservation $reservation) {
        // Basic authorization: ensure user owns the reservation or is admin/staff/doctor
        if (Auth::id() === $reservation->user_id || Auth::user()->hasAnyRole(['admin', 'staff', 'doctor'])) {
            return view('reservations.show', compact('reservation'));
        }
        abort(403); // Forbidden
    })->name('reservations.show');
});


// ... other routes

// Route to initiate Midtrans payment (e.g., from a user's reservation detail page)
Route::get('/reservation/{reservation}/pay-midtrans', [MidtransPaymentController::class, 'initiatePayment'])
    ->name('reservation.pay.midtrans');

// Midtrans redirect URLs (user is sent here after completing/cancelling payment on Midtrans side)
Route::get('/reservation/{reservation}/payment/finish', [MidtransPaymentController::class, 'handleFinish'])->name('midtrans.finish');
Route::get('/reservation/{reservation}/payment/unfinish', [MidtransPaymentController::class, 'handleUnfinish'])->name('midtrans.unfinish');
Route::get('/reservation/{reservation}/payment/error', [MidtransPaymentController::class, 'handleError'])->name('midtrans.error');
