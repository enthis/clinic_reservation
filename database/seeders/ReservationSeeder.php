<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        $doctor1 = Doctor::where('name', 'Dr. Alice Smith')->first();
        $doctor2 = Doctor::where('name', 'Dr. Bob Johnson')->first();
        $service1 = Service::where('name', 'General Consultation')->first();
        $service2 = Service::where('name', 'Pediatric Consultation')->first();
        $staffUser = User::where('role', 'staff')->first();

        if (!$user || !$doctor1 || !$doctor2 || !$service1 || !$service2 || !$staffUser) {
            echo "Skipping ReservationSeeder: Required users, doctors, services, or staff not found. Please run UserSeeder, DoctorSeeder, and ServiceSeeder first.\n";
            return;
        }

        // Get some available schedules
        $schedule1 = DoctorSchedule::where('doctor_id', $doctor1->id)
            ->where('date', Carbon::today()->addDays(1))
            ->where('start_time', '09:00:00')
            ->first();

        $schedule2 = DoctorSchedule::where('doctor_id', $doctor2->id)
            ->where('date', Carbon::today()->addDays(2))
            ->where('start_time', '14:00:00')
            ->first();

        if (!$schedule1 || !$schedule2) {
            echo "Skipping ReservationSeeder: Required doctor schedules not found. Please run DoctorScheduleSeeder first.\n";
            return;
        }

        // Create a pending reservation
        Reservation::firstOrCreate(
            [
                'user_id' => $user->id,
                'doctor_id' => $doctor1->id,
                'service_id' => $service1->id,
                'schedule_id' => $schedule1->id,
                'scheduled_date' => $schedule1->date,
                'scheduled_time' => $schedule1->start_time,
            ],
            [
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_amount' => $service1->price,
            ]
        );

        // Create an approved and paid reservation
        Reservation::firstOrCreate(
            [
                'user_id' => $user->id,
                'doctor_id' => $doctor2->id,
                'service_id' => $service2->id,
                'schedule_id' => $schedule2->id,
                'scheduled_date' => $schedule2->date,
                'scheduled_time' => $schedule2->start_time,
            ],
            [
                'status' => 'approved',
                'payment_status' => 'paid',
                'payment_amount' => $service2->price,
                'approved_by' => $staffUser->id
            ]
        );

        // Mark the schedules as unavailable for these reservations
        $schedule1->update(['is_available' => false]);
        $schedule2->update(['is_available' => false]);
    }
}
