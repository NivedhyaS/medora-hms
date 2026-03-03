<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\LabStaff;
use App\Models\Pharmacist;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => 'admin@medora.com',
            'doctor' => 'doctor@example.com',
            'lab_staff' => 'lab@example.com',
            'pharmacist' => 'pharm@example.com',
            'patient' => 'patient@example.com',
        ];

        foreach ($roles as $role => $email) {
            if (!User::where('email', $email)->exists()) {
                $user = User::create([
                    'name' => ucfirst($role) . ' User',
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => $role,
                ]);

                // Create profile if necessary
                if ($role === 'doctor') {
                    Doctor::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'specialization' => 'General Medicine',
                        'phone' => '1234567890',
                        'email' => $user->email,
                        'availability_start' => '09:00',
                        'availability_end' => '17:00'
                    ]);
                } elseif ($role === 'lab_staff') {
                    LabStaff::create([
                        'user_id' => $user->id,
                        'lab_id' => 'LAB-001',
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => '1234567890',
                        'department' => 'Blood Test'
                    ]);
                } elseif ($role === 'pharmacist') {
                    Pharmacist::create([
                        'user_id' => $user->id,
                        'pharm_id' => 'PH-001',
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => '1234567890',
                    ]);
                } elseif ($role === 'patient') {
                    Patient::create([
                        'user_id' => $user->id,
                        'patient_id' => 'PAT-001',
                    ]);
                }
            }
        }
    }
}
