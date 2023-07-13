<?php

namespace Database\Seeders;

use App\Models\CustomerHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Update uuid
        $customer_histories = CustomerHistory::whereNull('uuid');
        if ($customer_histories->count()) {
            $data = $customer_histories->get();
            foreach ($data as $row) {
                $row->update([
                    'uuid' => Str::uuid(),
                ]);
            }
        }
    }
}
