<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Reservation;
use App\Models\PrescriptionItem;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservation = Reservation::where('status', 'approved')->first(); // Get an approved reservation
        $paracetamol = PrescriptionItem::where('name', 'Paracetamol 500mg')->first();
        $amoxicillin = PrescriptionItem::where('name', 'Amoxicillin 500mg')->first();

        if (!$reservation || !$paracetamol || !$amoxicillin) {
            echo "Skipping RecipeSeeder: Required reservation or prescription items not found. Please run ReservationSeeder and PrescriptionItemSeeder first.\n";
            return;
        }

        Recipe::firstOrCreate(
            [
                'reservation_id' => $reservation->id,
                'prescription_item_id' => $paracetamol->id,
            ],
            [
                'dose' => '1 tablet, 3 times a day after meals',
                'notes' => 'For fever and headache.',
            ]
        );

        Recipe::firstOrCreate(
            [
                'reservation_id' => $reservation->id,
                'prescription_item_id' => $amoxicillin->id,
            ],
            [
                'dose' => '1 capsule, 2 times a day for 7 days',
                'notes' => 'Finish the full course of antibiotics.',
            ]
        );
    }
}

