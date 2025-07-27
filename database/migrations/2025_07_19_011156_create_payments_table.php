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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->string('gateway_transaction_id')->nullable()->unique()->comment('Transaction ID from the payment gateway (e.g., Midtrans ID, QRIS reference)');
            $table->string('order_id')->unique()->comment('Your internal order ID (can be same as reservation_id or a unique string)');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('IDR');
            $table->string('payment_gateway')->comment('e.g., midtrans, cashier, qris_provider_name'); // New field
            $table->string('payment_method')->nullable()->comment('e.g., credit_card, bank_transfer (Midtrans); cash, card (Cashier); gopay, ovo (QRIS)');
            $table->string('transaction_status')->comment('Status of the transaction: pending, capture, settlement, deny, expire, cancel, refund, paid, failed');
            $table->timestamp('transaction_time')->nullable();
            $table->text('raw_response')->nullable()->comment('Full JSON response from payment gateway for debugging/auditing');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
