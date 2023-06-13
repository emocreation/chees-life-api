<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exist = Category::whereTranslation('name', '自主體檢組合')->count();
        if (!$exist) {
            Category::create([
                'en' => [
                    'name' => '自主體檢組合',
                    'title' => 'title',
                    'description' => 'description'
                ],
                'tc' => [
                    'name' => '自主體檢組合',
                    'title' => 'title',
                    'description' => 'description'
                ],
                'enable' => 1
            ]);
        }
    }
}
