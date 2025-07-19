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
        $today = Carbon::today();

        foreach ($doctors as $doctor) {
            // Create schedules for the next 7 days
            for ($i = 0; $i < 7; $i++) {
                $date = $today->copy()->addDays($i);

                // Example: Morning slot
                DoctorSchedule::firstOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'date' => $date,
                        'start_time' => '09:00:00',
                        'end_time' => '12:00:00',
                    ],
                    [
                        'is_available' => true,
                    ]
                );

                // Example: Afternoon slot
                DoctorSchedule::firstOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'date' => $date,
                        'start_time' => '14:00:00',
                        'end_time' => '17:00:00',
                    ],
                    [
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}

