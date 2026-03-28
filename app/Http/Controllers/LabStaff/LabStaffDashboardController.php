<?php

namespace App\Http\Controllers\LabStaff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabReportValue;
use App\Models\LabParameterMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabStaffDashboardController extends Controller
{
    public function index()
    {
        // Show all pending test requests
        $pendingTests = LabTest::where('status', 'pending')
            ->with(['patient.user' => function($q) {
                $q->withTrashed();
            }, 'doctor.user', 'testType.parameters'])
            ->latest()
            ->get();

        return view('labstaff.dashboard', compact('pendingTests'));
    }

    public function uploadReport(Request $request, $id)
    {
        $labStaff = Auth::user()->labStaff;
        if (!$labStaff) {
            return redirect()->back()->with('error', 'Lab staff profile not found.');
        }

        $labTest = LabTest::with('testType.parameters')->findOrFail($id);

        // Validation based on parameters
        $rules = [
            'remarks' => 'nullable|string',
            'report_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ];

        if ($labTest->testType) {
            foreach ($labTest->testType->parameters as $param) {
                $rules['param_' . $param->id] = ($param->is_required ? 'required' : 'nullable') . ($param->min_value !== null ? '|numeric' : '|string');
            }
        } else {
            $rules['result'] = 'required|string';
        }

        $request->validate($rules);

        $filePath = $labTest->file_path;
        if ($request->hasFile('report_file')) {
            $filePath = $request->file('report_file')->store('lab_tests', 'public');
        }

        DB::transaction(function () use ($labTest, $labStaff, $request, $filePath) {
            if ($labTest->testType) {
                foreach ($labTest->testType->parameters as $param) {
                    $val = $request->input('param_' . $param->id);
                    $status = 'normal';

                    if ($param->min_value !== null && $param->max_value !== null) {
                        if ($val < $param->min_value)
                            $status = 'low';
                        elseif ($val > $param->max_value)
                            $status = 'high';
                    }

                    LabReportValue::create([
                        'report_id' => $labTest->id,
                        'parameter_id' => $param->id,
                        'value' => $val,
                        'status' => $status
                    ]);
                }
            }

            $labTest->update([
                'lab_staff_id' => $labStaff->id,
                'result' => $request->input('result'),
                'remarks' => $request->remarks,
                'file_path' => $filePath,
                'status' => 'completed',
            ]);
        });

        return redirect()->back()->with('success', 'Lab report uploaded successfully.');
    }
}
