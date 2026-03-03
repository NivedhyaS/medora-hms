<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ReceptionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'reception@medora.com'],
            [
                'name' => 'Hospital Reception',
                'password' => Hash::make('password'),
                'role' => User::ROLE_RECEPTION,
            ]
        );
    }
}
