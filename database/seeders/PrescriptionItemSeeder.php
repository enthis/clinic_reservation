<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PrescriptionItem;

class PrescriptionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrescriptionItem::firstOrCreate(
            ['name' => 'Paracetamol 500mg'],
            ['description' => 'Pain reliever and fever reducer.', 'price' => 15000.00]
        );
        PrescriptionItem::firstOrCreate(
            ['name' => 'Amoxicillin 500mg'],
            ['description' => 'Antibiotic.', 'price' => 25000.00]
        );
        PrescriptionItem::firstOrCreate(
            ['name' => 'Ibuprofen 400mg'],
            ['description' => 'NSAID for pain and inflammation.', 'price' => 18000.00]
        );
        PrescriptionItem::firstOrCreate(
            ['name' => 'Vitamin C 1000mg'],
            ['description' => 'Immune support.', 'price' => 30000.00]
        );
        PrescriptionItem::firstOrCreate(
            ['name' => 'Antacid'],
            ['description' => 'For heartburn and indigestion.', 'price' => 12000.00]
        );
        PrescriptionItem::firstOrCreate(
            ['name' => 'Cough Syrup'],
            ['description' => 'For cough relief.', 'price' => 22000.00]
        );
    }
}
