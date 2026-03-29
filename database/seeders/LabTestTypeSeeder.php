<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTestType;
use App\Models\LabParameterMaster;

class LabTestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'department' => 'Hematology',
                'test_name' => 'Complete Blood Count (CBC)',
                'parameters' => [
                    ['parameter_name' => 'Hemoglobin', 'min_value' => 13.0, 'max_value' => 17.0, 'unit' => 'g/dL', 'is_required' => true],
                    ['parameter_name' => 'WBC Count', 'min_value' => 4000, 'max_value' => 11000, 'unit' => 'cells/mm3', 'is_required' => true],
                    ['parameter_name' => 'Platelet Count', 'min_value' => 150000, 'max_value' => 450000, 'unit' => 'cells/mm3', 'is_required' => true],
                ]
            ],
            [
                'department' => 'Biochemistry',
                'test_name' => 'Liver Function Test (LFT)',
                'parameters' => [
                    ['parameter_name' => 'SGPT', 'min_value' => 0, 'max_value' => 40, 'unit' => 'U/L', 'is_required' => true],
                    ['parameter_name' => 'SGOT', 'min_value' => 0, 'max_value' => 40, 'unit' => 'U/L', 'is_required' => true],
                    ['parameter_name' => 'Total Bilirubin', 'min_value' => 0.1, 'max_value' => 1.2, 'unit' => 'mg/dL', 'is_required' => true],
                ]
            ],
            [
                'department' => 'Biochemistry',
                'test_name' => 'Sugar Profile',
                'parameters' => [
                    ['parameter_name' => 'Fasting Blood Sugar', 'min_value' => 70, 'max_value' => 110, 'unit' => 'mg/dL', 'is_required' => true],
                    ['parameter_name' => 'Post Prandial (PPBS)', 'min_value' => 70, 'max_value' => 140, 'unit' => 'mg/dL', 'is_required' => true],
                ]
            ],
            [
                'department' => 'Immunology',
                'test_name' => 'Thyroid Profile (T3, T4, TSH)',
                'parameters' => [
                    ['parameter_name' => 'TSH', 'min_value' => 0.45, 'max_value' => 4.5, 'unit' => 'uIU/mL', 'is_required' => true],
                    ['parameter_name' => 'Free T3', 'min_value' => 2.0, 'max_value' => 4.4, 'unit' => 'pg/mL', 'is_required' => true],
                    ['parameter_name' => 'Free T4', 'min_value' => 0.82, 'max_value' => 1.77, 'unit' => 'ng/dL', 'is_required' => true],
                ]
            ],
        ];

        foreach ($data as $test) {
            $testType = LabTestType::firstOrCreate([
                'department' => $test['department'],
                'test_name' => $test['test_name']
            ]);

            foreach ($test['parameters'] as $param) {
                LabParameterMaster::firstOrCreate(array_merge(
                    ['test_type_id' => $testType->id],
                    $param
                ));
            }
        }
    }
}
