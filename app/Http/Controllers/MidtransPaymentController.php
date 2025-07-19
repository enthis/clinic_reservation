<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // For database transactions

class MidtransPaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Initiate a Midtrans payment for a reservation.
     *
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiatePayment(Reservation $reservation)
    {
        // Check if reservation is already paid or has a pending payment
        if ($reservation->payment_status === 'paid') {
            return redirect()->back()->with('info', 'This reservation has already been paid.');
        }

        // Generate a unique order ID for Midtrans
        // You can use reservation ID directly or combine it with a timestamp
        $orderId = 'RES-' . $reservation->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $reservation->payment_amount,
            ],
            'customer_details' => [
                'first_name' => $reservation->user->name,
                'email' => $reservation->user->email,
                // 'phone' => $reservation->user->phone_number, // Add if available in User model
            ],
            'item_details' => [
                [
                    'id' => $reservation->service->id,
                    'price' => $reservation->service->price,
                    'quantity' => 1,
                    'name' => $reservation->service->name,
                ]
            ],
            'callbacks' => [
                'finish' => url('/reservation/' . $reservation->id . '/payment/finish'),
                'unfinish' => url('/reservation/' . $reservation->id . '/payment/unfinish'),
                'error' => url('/reservation/' . $reservation->id . '/payment/error'),
            ],
        ];

        $snapUrl = $this->midtransService->getSnapRedirectUrl($params);

        if ($snapUrl) {
            // Create a pending payment record in your database
            Payment::create([
                'reservation_id' => $reservation->id,
                'order_id' => $orderId,
                'amount' => $reservation->payment_amount,
                'currency' => 'IDR',
                'payment_gateway' => 'midtrans',
                'transaction_status' => 'pending', // Initial status
            ]);

            return redirect()->away($snapUrl);
        } else {
            return redirect()->back()->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Handle Midtrans payment notification (webhook).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleNotification(Request $request)
    {
        $notification = $this->midtransService->getNotification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;
        $midtransTransactionId = $notification->transaction_id; // Midtrans's unique transaction ID

        Log::info("Midtrans Notification received for Order ID: {$orderId}, Status: {$transactionStatus}, Fraud: {$fraudStatus}");

        // Find the payment record in your database
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning("Midtrans Notification: Payment record not found for Order ID: {$orderId}");
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        // Use a database transaction to ensure atomicity
        DB::beginTransaction();
        try {
            $reservation = $payment->reservation;

            if (!$reservation) {
                Log::warning("Midtrans Notification: Reservation not found for Payment ID: {$payment->id}");
                throw new \Exception("Reservation not found for payment.");
            }

            // Update payment record based on Midtrans status
            $payment->midtrans_transaction_id = $midtransTransactionId;
            $payment->payment_method = $notification->payment_type;
            $payment->transaction_time = $notification->transaction_time;
            $payment->raw_response = json_encode($notification->json());

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $payment->transaction_status = 'challenge';
                    $reservation->payment_status = 'pending'; // Still pending until challenge resolved
                } else if ($fraudStatus == 'accept') {
                    $payment->transaction_status = 'settlement';
                    $reservation->payment_status = 'paid';
                }
            } else if ($transactionStatus == 'settlement') {
                $payment->transaction_status = 'settlement';
                $reservation->payment_status = 'paid';
            } else if ($transactionStatus == 'pending') {
                $payment->transaction_status = 'pending';
                $reservation->payment_status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $payment->transaction_status = 'deny';
                $reservation->payment_status = 'failed';
            } else if ($transactionStatus == 'expire') {
                $payment->transaction_status = 'expire';
                $reservation->payment_status = 'failed';
            } else if ($transactionStatus == 'cancel') {
                $payment->transaction_status = 'cancel';
                $reservation->payment_status = 'failed';
            } else if ($transactionStatus == 'refund') {
                $payment->transaction_status = 'refund';
                $reservation->payment_status = 'refunded';
            }

            $payment->save();
            $reservation->save();

            DB::commit();
            return response()->json(['message' => 'Notification handled successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error handling Midtrans notification: ' . $e->getMessage(), ['order_id' => $orderId, 'exception' => $e]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Handle payment finish redirect.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleFinish(Request $request, Reservation $reservation)
    {
        // This is where the user is redirected after payment.
        // The actual status update should happen via webhook, but you can show a message.
        $status = $request->query('transaction_status');
        $orderId = $request->query('order_id');

        // You might want to fetch the latest payment status from your DB here
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && $payment->transaction_status === 'settlement' || $payment->transaction_status === 'paid') {
            return redirect()->route('reservations.show', $reservation->id)->with('success', 'Payment successful!');
        } else {
            return redirect()->route('reservations.show', $reservation->id)->with('warning', 'Payment status: ' . ($payment ? $payment->transaction_status : 'unknown') . '. Please check your reservation details.');
        }
    }

    /**
     * Handle payment unfinish redirect.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleUnfinish(Request $request, Reservation $reservation)
    {
        return redirect()->route('reservations.show', $reservation->id)->with('info', 'Payment was unfinished. Please try again.');
    }

    /**
     * Handle payment error redirect.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleError(Request $request, Reservation $reservation)
    {
        return redirect()->route('reservations.show', $reservation->id)->with('error', 'Payment encountered an error. Please try again.');
    }
}
