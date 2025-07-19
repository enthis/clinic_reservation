<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentGatewayConfig; // Make sure to create this model
use Illuminate\Support\Facades\Crypt; // For encryption

class PaymentGatewayConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Midtrans Sandbox Configuration ---
        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'sandbox',
                'config_key' => 'server_key',
            ],
            [
                'config_value' => Crypt::encryptString('SB-Mid-server-YOUR_SANDBOX_SERVER_KEY'), // Encrypt sensitive data
                'is_encrypted' => true,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'sandbox',
                'config_key' => 'client_key',
            ],
            [
                'config_value' => 'SB-Mid-client-YOUR_SANDBOX_CLIENT_KEY', // Client key usually not encrypted
                'is_encrypted' => false,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'sandbox',
                'config_key' => 'merchant_id',
            ],
            [
                'config_value' => 'YOUR_SANDBOX_MERCHANT_ID',
                'is_encrypted' => false,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'sandbox',
                'config_key' => 'callback_url',
            ],
            [
                'config_value' => env('APP_URL') . '/api/midtrans/callback', // Use APP_URL from .env
                'is_encrypted' => false,
            ]
        );

        // --- Midtrans Production Configuration ---
        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'production',
                'config_key' => 'server_key',
            ],
            [
                'config_value' => Crypt::encryptString('Mid-server-YOUR_PRODUCTION_SERVER_KEY'), // Encrypt sensitive data
                'is_encrypted' => true,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'production',
                'config_key' => 'client_key',
            ],
            [
                'config_value' => 'Mid-client-YOUR_PRODUCTION_CLIENT_KEY',
                'is_encrypted' => false,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'production',
                'config_key' => 'merchant_id',
            ],
            [
                'config_value' => 'YOUR_PRODUCTION_MERCHANT_ID',
                'is_encrypted' => false,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'midtrans',
                'mode' => 'production',
                'config_key' => 'callback_url',
            ],
            [
                'config_value' => env('APP_URL') . '/api/midtrans/callback',
                'is_encrypted' => false,
            ]
        );

        // --- Example for a generic QRIS provider ---
        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'qris_provider_a',
                'mode' => 'production',
                'config_key' => 'api_key',
            ],
            [
                'config_value' => Crypt::encryptString('QRIS-API-KEY-12345'),
                'is_encrypted' => true,
            ]
        );

        PaymentGatewayConfig::firstOrCreate(
            [
                'gateway_name' => 'qris_provider_a',
                'mode' => 'production',
                'config_key' => 'merchant_code',
            ],
            [
                'config_value' => 'MERCHANT-QRS-A',
                'is_encrypted' => false,
            ]
        );
    }
}
