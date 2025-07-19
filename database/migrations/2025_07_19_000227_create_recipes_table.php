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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('prescription_item_id')->constrained('prescription_items')->onDelete('cascade');
            $table->string('dose');
            $table->text('notes')->nullable(); // Specific notes for this item in the recipe
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
