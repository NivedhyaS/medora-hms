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

        // Create Default Receptionist
        User::create([
            'name' => 'Medora Reception',
            'email' => 'reception@medora.com',
            'password' => \Illuminate\Support\Facades\Hash::make('reception123'),
            'role' => User::ROLE_RECEPTION,
        ]);

        // Create Default Specializations
        $specializations = [
            'Cardiology', 
            'Dermatology', 
            'Neurology', 
            'Pediatrics', 
            'Orthopedics', 
            'General Medicine', 
            'Gynecology', 
            'Oncology', 
            'ENT (Ear, Nose, Throat)', 
            'Psychiatry'
        ];

        foreach ($specializations as $sp) {
            \App\Models\Specialization::firstOrCreate(['name' => $sp]);
        }

        // Create Medicines and Lab Tests
        $this->call([
            MedicineSeeder::class,
            LabTestTypeSeeder::class,
        ]);
    }
}
