<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabStructureSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------------------
        // HEMATOLOGY
        // ------------------------------------------
        $cbc = DB::table('lab_test_types')->insertGetId([
            'department' => 'Hematology',
            'test_name' => 'CBC (Complete Blood Count)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cbcParams = [
            ['Hemoglobin', 13.0, 17.0, 'g/dL'],
            ['RBC Count', 4.5, 5.5, 'million/µL'],
            ['WBC Count', 4000, 11000, '/µL'],
            ['Platelet Count', 150000, 450000, '/µL'],
            ['Hematocrit', 40, 50, '%'],
            ['MCV', 80, 100, 'fL'],
            ['MCH', 27, 32, 'pg'],
        ];

        foreach ($cbcParams as $p) {
            DB::table('lab_test_parameters_master')->insert([
                'test_type_id' => $cbc,
                'parameter_name' => $p[0],
                'min_value' => $p[1],
                'max_value' => $p[2],
                'unit' => $p[3],
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ------------------------------------------
        // BIOCHEMISTRY
        // ------------------------------------------
        $lft = DB::table('lab_test_types')->insertGetId([
            'department' => 'Biochemistry',
            'test_name' => 'Liver Function Test (LFT)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lftParams = [
            ['SGOT (AST)', 5, 40, 'U/L'],
            ['SGPT (ALT)', 7, 56, 'U/L'],
            ['ALP', 44, 147, 'U/L'],
            ['Total Bilirubin', 0.1, 1.2, 'mg/dL'],
            ['Direct Bilirubin', 0, 0.3, 'mg/dL'],
            ['Albumin', 3.4, 5.4, 'g/dL'],
        ];

        foreach ($lftParams as $p) {
            DB::table('lab_test_parameters_master')->insert([
                'test_type_id' => $lft,
                'parameter_name' => $p[0],
                'min_value' => $p[1],
                'max_value' => $p[2],
                'unit' => $p[3],
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $kft = DB::table('lab_test_types')->insertGetId([
            'department' => 'Biochemistry',
            'test_name' => 'Kidney Function Test (KFT)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kftParams = [
            ['Creatinine', 0.7, 1.3, 'mg/dL'],
            ['Urea', 7, 20, 'mg/dL'],
            ['Sodium', 135, 145, 'mEq/L'],
            ['Potassium', 3.6, 5.2, 'mEq/L'],
            ['Chloride', 96, 106, 'mEq/L'],
        ];

        foreach ($kftParams as $p) {
            DB::table('lab_test_parameters_master')->insert([
                'test_type_id' => $kft,
                'parameter_name' => $p[0],
                'min_value' => $p[1],
                'max_value' => $p[2],
                'unit' => $p[3],
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ------------------------------------------
        // CLINICAL PATHOLOGY
        // ------------------------------------------
        $urine = DB::table('lab_test_types')->insertGetId([
            'department' => 'Clinical Pathology',
            'test_name' => 'Urine Routine',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $urineParams = [
            ['pH', 4.5, 8.0, ''],
            ['Protein', 0, 0, 'mg/dL'], // Qualitative/Quantitative
            ['Sugar', 0, 0, 'mg/dL'],
            ['RBC', 0, 2, '/hpf'],
            ['WBC', 0, 5, '/hpf'],
        ];

        foreach ($urineParams as $p) {
            DB::table('lab_test_parameters_master')->insert([
                'test_type_id' => $urine,
                'parameter_name' => $p[0],
                'min_value' => $p[1],
                'max_value' => $p[2],
                'unit' => $p[3],
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ------------------------------------------
        // IMMUNOLOGY / SEROLOGY
        // ------------------------------------------
        $serology = [
            ['Department' => 'Immunology / Serology', 'Name' => 'CRP'],
            ['Department' => 'Immunology / Serology', 'Name' => 'RA Factor'],
            ['Department' => 'Immunology / Serology', 'Name' => 'HBsAg'],
        ];

        foreach ($serology as $s) {
            $sid = DB::table('lab_test_types')->insertGetId([
                'department' => $s['Department'],
                'test_name' => $s['Name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('lab_test_parameters_master')->insert([
                'test_type_id' => $sid,
                'parameter_name' => 'Result',
                'min_value' => null,
                'max_value' => null,
                'unit' => '',
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
