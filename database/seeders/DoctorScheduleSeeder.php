<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon; // Import Carbon for date/time manipulation

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Create schedules for each day of the week (0=Sunday, 1=Monday, ..., 6=Saturday)
            for ($dayOfWeek = 0; $dayOfWeek <= 6; $dayOfWeek++) {
                // Example: Morning slot
                DoctorSchedule::firstOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => '09:00:00',
                        'end_time' => '12:00:00',
                    ],
                    [
                        'is_available' => true,
                        'notes' => "Morning shift on " . (new Carbon())->dayOfWeek($dayOfWeek)->format('l'),
                    ]
                );

                // Example: Afternoon slot
                DoctorSchedule::firstOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => '14:00:00',
                        'end_time' => '17:00:00',
                    ],
                    [
                        'is_available' => true,
                        'notes' => "Afternoon shift on " . (new Carbon())->dayOfWeek($dayOfWeek)->format('l'),
                    ]
                );
            }
        }
    }
}

