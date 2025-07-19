<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DoctorNote;
use App\Models\Reservation;
use App\Models\Doctor;

class DoctorNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservation = Reservation::where('status', 'approved')->first(); // Get an approved reservation
        $doctor = $reservation ? $reservation->doctor : null;

        if (!$reservation || !$doctor) {
            echo "Skipping DoctorNoteSeeder: Required reservation or doctor not found. Please run ReservationSeeder first.\n";
            return;
        }

        DoctorNote::firstOrCreate(
            [
                'reservation_id' => $reservation->id,
                'doctor_id' => $doctor->id,
            ],
            [
                'note_content' => 'Patient presented with mild fever and sore throat. Prescribed antibiotics and advised rest. Follow-up in 3 days if symptoms persist.',
            ]
        );
    }
}
