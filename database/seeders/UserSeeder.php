<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@assets.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Petugas User
        User::firstOrCreate(
            ['email' => 'petugas@assets.com'],
            [
                'name' => 'Petugas Toko',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
    }
}
