<?php

use App\Http\Controllers\MidtransPaymentController;
use App\Http\Controllers\ApiAuthController; // Import the new controller
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no authentication required)
Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');

// Protected API routes (authentication required via Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Route to initiate Midtrans payment via AJAX
    Route::post('/reservation/{reservation}/pay-midtrans', [MidtransPaymentController::class, 'initiatePayment'])
        ->name('api.reservation.pay.midtrans');
    // Route to check payment status from Midtrans
    Route::get('/reservation/{reservation}/check-payment-status', [MidtransPaymentController::class, 'checkPaymentStatus'])
        ->name('api.reservation.check-payment-status');

    // API Logout route
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');

    // You can add other protected API routes here
});


// Midtrans webhook/notification endpoint (does NOT require authentication)
Route::post('/midtrans/callback', [MidtransPaymentController::class, 'handleNotification'])->name('midtrans.notification');
