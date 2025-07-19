<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::firstOrCreate(
            ['name' => 'General Consultation'],
            [
                'description' => 'A basic check-up and consultation with a general practitioner.',
                'price' => 150000.00,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'Dental Check-up'],
            [
                'description' => 'Comprehensive dental examination and cleaning.',
                'price' => 200000.00,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'Pediatric Consultation'],
            [
                'description' => 'Consultation for children with a pediatrician.',
                'price' => 180000.00,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'Dermatology Consultation'],
            [
                'description' => 'Consultation for skin conditions.',
                'price' => 250000.00,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'Nutrition Counseling'],
            [
                'description' => 'Personalized advice on diet and nutrition.',
                'price' => 120000.00,
            ]
        );
    }
}

