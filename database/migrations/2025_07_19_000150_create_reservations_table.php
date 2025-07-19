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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('doctor_schedules')->onDelete('cascade');
            $table->date('scheduled_date'); // Redundant but useful for quick filtering
            $table->time('scheduled_time'); // Redundant but useful for quick filtering
            $table->string('status')->default('pending'); // pending, approved, rejected, completed, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('xendit_invoice_id')->nullable()->unique();
            $table->decimal('payment_amount', 8, 2);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Staff user
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null'); // Staff user
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
