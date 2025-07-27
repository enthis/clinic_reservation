<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Auth; // Added Auth facade for initiatePayment
use Midtrans\Transaction; // Import Midtrans Transaction class

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
            $payment->gateway_transaction_id = $midtransTransactionId; // Correctly uses gateway_transaction_id
            $payment->payment_method = $notification->payment_type;
            $payment->transaction_time = $notification->transaction_time;
            $payment->raw_response = json_encode($notification);
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
     * Fetch the latest transaction status from Midtrans for a given reservation.
     *
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus(Reservation $reservation)
    {
        Log::debug('Check Payment Status: Starting for Reservation ID: ' . $reservation->id);

        // Basic authorization check: user must own the reservation or be admin/staff
        if (!Auth::check() || (Auth::id() !== $reservation->user_id && !Auth::user()->hasAnyRole(['admin', 'staff']))) {
            Log::warning('Check Payment Status: Unauthorized attempt for Reservation ID: ' . $reservation->id . ' by User ID: ' . (Auth::check() ? Auth::id() : 'Guest'));
            return response()->json(['error' => 'Unauthorized to check status for this reservation.'], 403);
        }

        // Find the latest payment record for this reservation that has an order_id for Midtrans
        // The order_id is the primary identifier used to query Midtrans's Transaction.status()
        $payment = $reservation->payments()->whereNotNull('order_id')->latest()->first();

        if (!$payment) {
            Log::warning('Check Payment Status: No payment record with order_id found for Reservation ID: ' . $reservation->id);
            return response()->json(['message' => 'No payment initiated for this reservation or no Midtrans order ID found.'], 404);
        }

        try {
            // Get the transaction status from Midtrans using the order_id
            $midtransStatus = \Midtrans\Transaction::status($payment->order_id); // Correctly uses order_id for query
            Log::debug('Check Payment Status: Midtrans raw status response for Order ID ' . $payment->order_id . ': ', (array)$midtransStatus);

            // Use a database transaction to ensure atomicity for local updates
            DB::beginTransaction();
            try {
                // Update local payment record details from Midtrans response
                $payment->gateway_transaction_id = $midtransStatus->transaction_id ?? $payment->gateway_transaction_id; // Assign Midtrans's transaction_id to gateway_transaction_id
                $payment->payment_method = $midtransStatus->payment_type ?? $payment->payment_method;
                $payment->transaction_time = $midtransStatus->transaction_time ?? $payment->transaction_time;
                $payment->raw_response = json_encode($midtransStatus); // Store the full response

                // Determine new local status based on Midtrans response
                $newPaymentStatus = $payment->transaction_status; // Default to current if no change
                $newReservationPaymentStatus = $reservation->payment_status; // Default to current if no change

                // Mapping Midtrans transaction_status to your local statuses
                switch ($midtransStatus->transaction_status) {
                    case 'capture':
                        if ($midtransStatus->fraud_status == 'challenge') {
                            $newPaymentStatus = 'challenge';
                            $newReservationPaymentStatus = 'pending';
                        } else if ($midtransStatus->fraud_status == 'accept') {
                            $newPaymentStatus = 'settlement';
                            $newReservationPaymentStatus = 'paid';
                        }
                        break;
                    case 'settlement':
                        $newPaymentStatus = 'settlement';
                        $newReservationPaymentStatus = 'paid';
                        break;
                    case 'pending':
                        $newPaymentStatus = 'pending';
                        $newReservationPaymentStatus = 'pending';
                        break;
                    case 'deny':
                        $newPaymentStatus = 'deny';
                        $newReservationPaymentStatus = 'failed';
                        break;
                    case 'expire':
                        $newPaymentStatus = 'expire';
                        $newReservationPaymentStatus = 'failed';
                        break;
                    case 'cancel':
                        $newPaymentStatus = 'cancel';
                        $newReservationPaymentStatus = 'failed';
                        break;
                    case 'refund':
                        $newPaymentStatus = 'refund';
                        $newReservationPaymentStatus = 'refunded';
                        break;
                    case 'authorize': // For credit card authorization only
                        $newPaymentStatus = 'authorize';
                        $newReservationPaymentStatus = 'pending';
                        break;
                    case 'partial_refund':
                        $newPaymentStatus = 'partial_refund';
                        $newReservationPaymentStatus = 'refunded'; // Or a new status like 'partially_refunded'
                        break;
                    default:
                        // Handle any other unknown statuses, keep current or set to 'unknown'
                        $newPaymentStatus = 'unknown';
                        $newReservationPaymentStatus = 'unknown';
                        Log::warning('Check Payment Status: Unknown Midtrans status received for Order ID ' . $payment->order_id . ': ' . $midtransStatus->transaction_status);
                        break;
                }


                // Only update if status has actually changed to avoid unnecessary DB writes
                if ($payment->transaction_status !== $newPaymentStatus || $reservation->payment_status !== $newReservationPaymentStatus) {
                    $payment->transaction_status = $newPaymentStatus;
                    $reservation->payment_status = $newReservationPaymentStatus;
                    $payment->save();
                    $reservation->save();
                    Log::info('Check Payment Status: Updated status for Order ID ' . $payment->order_id . ' to ' . $newPaymentStatus);
                } else {
                    Log::debug('Check Payment Status: Status for Order ID ' . $payment->order_id . ' is already up-to-date (' . $newPaymentStatus . ').');
                }

                DB::commit();

                return response()->json([
                    'status' => ucfirst($newPaymentStatus),
                    'last_updated' => now()->format('M d, Y H:i:s'),
                    'message' => 'Payment status retrieved and updated successfully.'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Check Payment Status: Error updating DB after Midtrans query for Order ID ' . $payment->order_id . ': ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['message' => 'Error updating payment status locally.'], 500);
            }

        } catch (\Exception $e) {
            // Catch exceptions from Midtrans API call itself
            Log::error('Check Payment Status: Error querying Midtrans API for Order ID ' . $payment->order_id . ': ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to query Midtrans status. Please ensure payment was initiated.'], 500);
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
        $status = $request->query('transaction_status'); // Status from Midtrans redirect
        $orderId = $request->query('order_id');

        // It's generally not reliable to fetch the final status here as webhook is asynchronous.
        // The webhook is the source of truth for the final payment status in the DB.
        $payment = Payment::where('order_id', $orderId)->first();

        if ($payment) {
            Log::info('Redirect: Finish - Payment process completed on Midtrans side for Order ID: ' . $orderId . '. Status from Midtrans: ' . $status);
            return redirect()->route('dashboard')->with('success', 'Payment process completed. Your reservation status will be updated shortly.');
        } else {
            Log::warning('Redirect: Finish - Payment record not found for Order ID: ' . $orderId . '. Status from Midtrans: ' . $status);
            return redirect()->route('dashboard')->with('warning', 'Payment process completed, but we could not find your payment record. Please check your dashboard later or contact support.');
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
        return redirect()->route('dashboard')->with('info', 'Payment was not completed. Please try again or contact support.');
    }

    /**
     * Handle payment error redirect.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleError(Request $request, Reservation $reservation)
    {
        Log::debug('Redirect: Error URL accessed for Reservation ID: ' . $reservation->id . '. Query params: ', $request->query());
        return redirect()->route('dashboard')->with('error', 'Payment encountered an error. Please try again or contact support.');
    }
}

