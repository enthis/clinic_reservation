<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateway_configs', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_name')->comment('e.g., midtrans, qris_provider_a, xendit');
            $table->string('mode')->default('sandbox')->comment('e.g., sandbox, production');
            $table->string('config_key')->comment('e.g., client_key, server_key, merchant_id, callback_url');
            $table->text('config_value')->comment('The actual configuration value (can be encrypted)');
            $table->boolean('is_encrypted')->default(false)->comment('Indicates if config_value is encrypted');
            $table->timestamps();
            $table->softDeletes();

            // Ensure uniqueness for a specific config key within a gateway and mode
            $table->unique(['gateway_name', 'mode', 'config_key'], 'unique_gateway_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_configs');
    }
};
