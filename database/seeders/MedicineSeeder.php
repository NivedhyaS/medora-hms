<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'quantity' => 500, 'price' => 5.00],
            ['name' => 'Amoxicillin 250mg', 'quantity' => 200, 'price' => 12.50],
            ['name' => 'Ibuprofen 400mg', 'quantity' => 300, 'price' => 8.00],
            ['name' => 'Cetirizine 10mg', 'quantity' => 150, 'price' => 15.00],
            ['name' => 'Metformin 500mg', 'quantity' => 1000, 'price' => 3.50],
            ['name' => 'Atorvastatin 20mg', 'quantity' => 400, 'price' => 22.00],
            ['name' => 'Omeprazole 20mg', 'quantity' => 600, 'price' => 10.00],
            ['name' => 'Amlodipine 5mg', 'quantity' => 450, 'price' => 6.50],
            ['name' => 'Azithromycin 500mg', 'quantity' => 100, 'price' => 45.00],
            ['name' => 'Ciprofloxacin 500mg', 'quantity' => 250, 'price' => 18.00],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
