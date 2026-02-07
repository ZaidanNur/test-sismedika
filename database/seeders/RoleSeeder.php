<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role Kasir
        Role::create(['name' => 'Kasir']);

        // Membuat role Pelayan
        Role::create(['name' => 'Pelayan']);
    }
}
