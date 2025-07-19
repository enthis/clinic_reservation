<?php

use Illuminate\Container\Attributes\Log;
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
        Schema::table('doctor_schedules', function (Blueprint $table) {
            // Add new columns
            $table->tinyInteger('day_of_week')->after('doctor_id')->comment('0 for Sunday, 1 for Monday, ..., 6 for Saturday');
            $table->text('notes')->nullable()->after('end_time');

            // Drop the old date column if it exists
            if (Schema::hasColumn('doctor_schedules', 'date')) {
                // $table->dropColumn('date');
            }

            // Update unique constraint if it includes 'date'
            // This might require dropping and re-adding the constraint.
            // For simplicity, we'll assume the unique constraint was on doctor_id, date, start_time, end_time.
            // If you had it, you'd need to manually handle dropping and re-adding it here
            // to include day_of_week instead of date, or remove it if no longer desired.
            // Example if you had: $table->unique(['doctor_id', 'date', 'start_time', 'end_time']);
            // You'd do:
            // $table->dropUnique(['doctor_id', 'date', 'start_time', 'end_time']);
            // $table->unique(['doctor_id', 'day_of_week', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            // Re-add the old date column
            $table->date('date')->nullable()->after('doctor_id');

            // Drop the new columns
            $table->dropColumn('day_of_week');
            $table->dropColumn('notes');

            // Re-add the unique constraint if it was dropped in up()
            // $table->unique(['doctor_id', 'date', 'start_time', 'end_time']);
        });
    }
};
