<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            DoctorSeeder::class, // Doctors depend on Users (if linked)
            PrescriptionItemSeeder::class,
            DoctorScheduleSeeder::class, // Schedules depend on Doctors
            ReservationSeeder::class,    // Reservations depend on Users, Doctors, Services, Schedules
            RecipeSeeder::class,         // Recipes depend on Reservations and PrescriptionItems
            DoctorNoteSeeder::class,     // DoctorNotes depend on Reservations and Doctors
            PaymentGatewayConfigSeeder::class
        ]);
    }
}
