<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user IDs for doctors created in UserSeeder
        $doctorUser1 = User::where('email', 'doctor1@example.com')->first();
        $doctorUser2 = User::where('email', 'doctor2@example.com')->first();

        if ($doctorUser1) {
            Doctor::firstOrCreate(
                ['name' => 'Dr. Alice Smith'],
                [
                    'user_id' => $doctorUser1->id,
                    'specialty' => 'General Practitioner',
                    'phone_number' => '081234567890',
                ]
            );
        }

        if ($doctorUser2) {
            Doctor::firstOrCreate(
                ['name' => 'Dr. Bob Johnson'],
                [
                    'user_id' => $doctorUser2->id,
                    'specialty' => 'Pediatrician',
                    'phone_number' => '081298765432',
                ]
            );
        }

        Doctor::firstOrCreate(
            ['name' => 'Dr. Carol White'],
            [
                'user_id' => null, // This doctor does not have a linked user account for login
                'specialty' => 'Dentist',
                'phone_number' => '081311223344',
            ]
        );

        Doctor::firstOrCreate(
            ['name' => 'Dr. David Brown'],
            [
                'user_id' => null,
                'specialty' => 'Dermatologist',
                'phone_number' => '081555667788',
            ]
        );
    }
}
