<?php

namespace Database\Seeders;

use App\Models\ReportExplanation;
use Illuminate\Database\Seeder;

class ReportExplanationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportExplanation::firstOrCreate([
            'type' => 'na',
            'price' => 0
        ]);

        ReportExplanation::firstOrCreate([
            'type' => 'by_phone',
            'price' => 150
        ]);

        ReportExplanation::firstOrCreate([
            'type' => 'by_appointment',
            'price' => 250
        ]);
    }
}
