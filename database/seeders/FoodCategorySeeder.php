<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Appertizers'],
            ['name' => 'Main Course'],
            ['name' => 'Dessert'],
            ['name' => 'Beverages'],
        ];

        foreach ($categories as $category) {
            FoodCategory::create($category);
        }
    }
}
