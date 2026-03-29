<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Your Custom Admin
        User::create([
            'name' => 'Medora Admin',
            'email' => 'admin@medora.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
