<?php

use Illuminate\Http\Request;
use App\Http\Controllers\MidtransPaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/callback', [MidtransPaymentController::class, 'handleNotification'])->name('midtrans.notification');
