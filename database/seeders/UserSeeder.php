<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'kasir',
            'email' => 'kasir@example.com',
            'password' => 'password',
        ]); 
        $user->assignRole('Kasir');   
        
        $user = User::create([
            'name' => 'Pelayan',
            'email' => 'pelayan@example.com',
            'password' => 'password',
        ]); 
        $user->assignRole('Pelayan');
    }
}
