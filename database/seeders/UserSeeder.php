<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bagus',
            'email' => 'bagus@gmail.com',
            'password' => Hash::make('bagus123'),
            'role' => 'user',
        ]);
        
        User::create([
            'name' => 'Testing',
            'email' => 'testing@gmail.com',
            'password' => Hash::make('testing123'),
            'role' => 'user',
        ]);
    }
}
