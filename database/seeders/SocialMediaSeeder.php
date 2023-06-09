<?php

namespace Database\Seeders;

use App\Enums\SocialMedia;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = SocialMedia::getValues();
        foreach ($types as $type) {
            \App\Models\SocialMedia::firstOrCreate(['type' => $type], [
                'link' => SocialMedia::getKey($type),
                'enable' => true
            ]);
        }
    }
}
