<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tableAmount = 30;

        for ($i=1; $i <= $tableAmount; $i++) {
            Table::create();
        }
    }
}
