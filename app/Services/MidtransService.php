<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\CoreApi; // Import CoreApi
use App\Models\PaymentGatewayConfig;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $isProduction;
    protected $serverKey;
    protected $clientKey;
    protected $merchantId;
    protected $callbackUrl;

    public function __construct()
    {
        // Determine if in production mode based on APP_ENV
        $this->isProduction = (env('APP_ENV') === 'production');
        $mode = $this->isProduction ? 'production' : 'sandbox';

        // Fetch Midtrans configuration from the database
        $configs = PaymentGatewayConfig::where('gateway_name', 'midtrans')
            ->where('mode', $mode)
            ->get()
            ->keyBy('config_key');

        $this->serverKey = $configs->get('server_key') && $configs->get('server_key')->is_encrypted
            ? Crypt::decryptString($configs->get('server_key')->config_value)
            : ($configs->get('server_key')->config_value ?? null);

        $this->clientKey = $configs->get('client_key')->config_value ?? null;
        $this->merchantId = $configs->get('merchant_id')->config_value ?? null;
        $this->callbackUrl = $configs->get('callback_url')->config_value ?? null;

        // Set Midtrans configuration
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Get Snap Token for payment popup.
     *
     * @param array $params Transaction details
     * @return string|null Snap Token or null on failure
     */
    public function getSnapToken(array $params) // This is the method in question
    {
        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), ['params' => $params]);
            return null;
        }
    }

    /**
     * Initiate a QRIS transaction and get its details.
     *
     * @param array $params Transaction details for QRIS
     * @return object|null Transaction response object or null on failure
     */
    public function getQrisTransactionDetails(array $params)
    {
        try {
            $params['payment_type'] = 'qris';
            if (!isset($params['qris'])) {
                $params['qris'] = ['acquirer_id' => 'GOPAY'];
            }

            $chargeResponse = CoreApi::charge($params);

            if (isset($chargeResponse->actions) && is_array($chargeResponse->actions)) {
                foreach ($chargeResponse->actions as $action) {
                    if ($action->name === 'generate-qr-code' && isset($action->url)) {
                        $chargeResponse->qr_code_url = $action->url;
                        break;
                    }
                }
            }

            return $chargeResponse;
        } catch (\Exception $e) {
            Log::error('Midtrans QRIS Error: ' . $e->getMessage(), ['params' => $params]);
            return null;
        }
    }


    /**
     * Get Midtrans notification handler.
     *
     * @return \Midtrans\Notification
     */
    public function getNotification()
    {
        return new Notification();
    }

    /**
     * Get the configured callback URL.
     *
     * @return string|null
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }
}
