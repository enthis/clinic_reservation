<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Auth; // Added Auth facade for initiatePayment

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function initiatePayment(Reservation $reservation)
    {
        Log::debug('Initiate Payment: Starting for Reservation ID: ' . $reservation->id);

        // Check if user is authenticated before accessing Auth::user()
        if (!Auth::check()) {
            Log::warning('Initiate Payment: Unauthenticated attempt for Reservation ID: ' . $reservation->id);
            return response()->json(['error' => 'Authentication required to initiate payment.'], 401);
        }

        // Ensure user is authorized to pay for this reservation
        if (Auth::id() !== $reservation->user_id && !Auth::user()->hasAnyRole(['admin', 'staff'])) {
            Log::warning('Initiate Payment: Unauthorized attempt for Reservation ID: ' . $reservation->id . ' by User ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized to pay for this reservation.'], 403);
        }
        Log::debug('Initiate Payment: User authorized for Reservation ID: ' . $reservation->id);


        // Check if reservation is already paid or has a pending payment
        if ($reservation->payment_status === 'paid') {
            Log::info('Initiate Payment: Reservation ID ' . $reservation->id . ' is already paid.');
            return response()->json(['info' => 'This reservation has already been paid.'], 200);
        }
        Log::debug('Initiate Payment: Reservation ID ' . $reservation->id . ' payment status is ' . $reservation->payment_status);


        // Generate a unique order ID for Midtrans
        $orderId = 'RES-' . $reservation->id . '-' . time();
        Log::debug('Initiate Payment: Generated Order ID: ' . $orderId);

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
        Log::debug('Initiate Payment: Midtrans parameters prepared.', $params);

        // Call getSnapToken() from MidtransService (assuming it returns the token for popup)
        $snapToken = $this->midtransService->getSnapToken($params);
        Log::debug('Initiate Payment: Snap Token request completed. Token: ' . ($snapToken ? 'Generated' : 'Failed'));


        if ($snapToken) {
            // Create a pending payment record in your database
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'order_id' => $orderId,
                'amount' => $reservation->payment_amount,
                'currency' => 'IDR',
                'payment_gateway' => 'midtrans',
                'transaction_status' => 'pending', // Initial status
            ]);
            Log::info('Initiate Payment: Payment record created for Order ID: ' . $orderId . ' with ID: ' . $payment->id);

            // Return the Snap Token as JSON
            return response()->json(['snap_token' => $snapToken]);
        } else {
            Log::error('Initiate Payment: Failed to get Snap Token for Reservation ID: ' . $reservation->id);
            return response()->json(['error' => 'Failed to initiate payment. Please try again.'], 500);
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
        Log::debug('Notification: Webhook received. Request data: ', $request->all());

        $notification = $this->midtransService->getNotification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;
        $midtransTransactionId = $notification->transaction_id;

        Log::info("Notification: Processing for Order ID: {$orderId}, Status: {$transactionStatus}, Fraud: {$fraudStatus}");

        // Find the payment record in your database
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning("Notification: Payment record not found for Order ID: {$orderId}. Aborting.");
            return response()->json(['message' => 'Payment record not found'], 404);
        }
        Log::debug('Notification: Found payment record ID: ' . $payment->id . ' for Order ID: ' . $orderId);


        // Use a database transaction to ensure atomicity
        DB::beginTransaction();
        try {
            $reservation = $payment->reservation;

            if (!$reservation) {
                Log::warning("Notification: Reservation not found for Payment ID: {$payment->id}. Rolling back.");
                throw new \Exception("Reservation not found for payment.");
            }
            Log::debug('Notification: Found reservation ID: ' . $reservation->id . ' for payment.');


            // Update payment record based on Midtrans status
            $payment->midtrans_transaction_id = $midtransTransactionId;
            $payment->payment_method = $notification->payment_type;
            $payment->transaction_time = $notification->transaction_time;
            $payment->raw_response = json_encode($notification->json());
            Log::debug('Notification: Payment record details updated. New status will be based on Midtrans response.');


            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $payment->transaction_status = 'challenge';
                    $reservation->payment_status = 'pending'; // Still pending until challenge resolved
                    Log::info('Notification: Transaction captured, fraud challenge. Setting status to challenge.');
                } else if ($fraudStatus == 'accept') {
                    $payment->transaction_status = 'settlement';
                    $reservation->payment_status = 'paid';
                    Log::info('Notification: Transaction captured, fraud accepted. Setting status to settlement/paid.');
                }
            } else if ($transactionStatus == 'settlement') {
                $payment->transaction_status = 'settlement';
                $reservation->payment_status = 'paid';
                Log::info('Notification: Transaction settled. Setting status to settlement/paid.');
            } else if ($transactionStatus == 'pending') {
                $payment->transaction_status = 'pending';
                $reservation->payment_status = 'pending';
                Log::info('Notification: Transaction pending. Setting status to pending.');
            } else if ($transactionStatus == 'deny') {
                $payment->transaction_status = 'deny';
                $reservation->payment_status = 'failed';
                Log::warning('Notification: Transaction denied. Setting status to deny/failed.');
            } else if ($transactionStatus == 'expire') {
                $payment->transaction_status = 'expire';
                $reservation->payment_status = 'failed';
                Log::warning('Notification: Transaction expired. Setting status to expire/failed.');
            } else if ($transactionStatus == 'cancel') {
                $payment->transaction_status = 'cancel';
                $reservation->payment_status = 'failed';
                Log::warning('Notification: Transaction cancelled. Setting status to cancel/failed.');
            } else if ($transactionStatus == 'refund') {
                $payment->transaction_status = 'refund';
                $reservation->payment_status = 'refunded';
                Log::info('Notification: Transaction refunded. Setting status to refunded.');
            }

            $payment->save();
            $reservation->save();
            Log::debug('Notification: Payment and Reservation records saved.');

            DB::commit();
            Log::info('Notification: Transaction committed successfully for Order ID: ' . $orderId);
            return response()->json(['message' => 'Notification handled successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Notification: Error handling Midtrans notification: ' . $e->getMessage(), ['order_id' => $orderId, 'exception' => $e]);
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
        Log::debug('Redirect: Finish URL accessed for Reservation ID: ' . $reservation->id . '. Query params: ', $request->query());
        $status = $request->query('transaction_status');
        $orderId = $request->query('order_id');

        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment && ($payment->transaction_status === 'settlement' || $payment->transaction_status === 'paid')) {
            Log::info('Redirect: Finish - Payment successful for Order ID: ' . $orderId);
            return redirect()->route('dashboard')->with('success', 'Payment successful! Your reservation is confirmed.');
        } else {
            Log::warning('Redirect: Finish - Payment status not successful for Order ID: ' . $orderId . '. Status: ' . ($payment ? $payment->transaction_status : 'unknown'));
            return redirect()->route('dashboard')->with('warning', 'Payment status: ' . ($payment ? $payment->transaction_status : 'Pending') . '. Please check your reservation details.');
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
        Log::debug('Redirect: Unfinish URL accessed for Reservation ID: ' . $reservation->id . '. Query params: ', $request->query());
        return redirect()->route('dashboard')->with('info', 'Payment was unfinished. Please try again or contact support.');
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
        Log::debug('Redirect: Error URL accessed for Reservation ID: ' . $reservation->id . '. Query params: ', $request->query());
        return redirect()->route('dashboard')->with('error', 'Payment encountered an error. Please try again or contact support.');
    }
}
