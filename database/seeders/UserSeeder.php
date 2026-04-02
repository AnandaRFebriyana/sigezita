<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '082345678901',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dewi Kusuma',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '083456789012',
            'is_active' => true,
        ]);
    }
}
