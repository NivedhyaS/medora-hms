<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabTest;
use App\Models\LabTestType;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Doctor profile not found.');
        }
        $doctorId = $doctor->id;
        $today = now()->toDateString();
        $search = $request->input('search');

        // Cards
        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();
        $todayCount = Appointment::where('doctor_id', $doctorId)->whereDate('appointment_date', $today)->count();
        $upcomingCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', '>', $today)
            ->where('status', 'booked')
            ->count();
        $completedCount = Appointment::where('doctor_id', $doctorId)->where('status', 'completed')->count();

        // Tabs
        $query = Appointment::where('doctor_id', $doctorId)
            ->when($search, function ($q) use ($search) {
                $q->whereHas('patient.user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                });
            })
            ->with('patient.user');

        $todayAppointments = (clone $query)->whereDate('appointment_date', $today)->get();

        $upcomingAppointments = (clone $query)->whereDate('appointment_date', '>', $today)
            ->where('status', 'booked')
            ->get();

        $pastAppointments = (clone $query)->where(function ($q) use ($today) {
            $q->whereDate('appointment_date', '<', $today)
                ->orWhere('status', 'completed');
        })
            ->get();

        return view('doctor.dashboard', compact(
            'totalAppointments',
            'todayCount',
            'upcomingCount',
            'completedCount',
            'todayAppointments',
            'upcomingAppointments',
            'pastAppointments',
            'search'
        ));
    }

    public function viewPatientProfile(Patient $patient)
    {
        $doctor = Auth::user()->doctor;

        // Section B: Visit History
        $visitHistory = Appointment::where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Section C: Past Prescriptions
        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Section D: Lab Reports
        $labTests = LabTest::where('patient_id', $patient->id)
            ->orderBy('requested_at', 'desc')
            ->get();

        // Section E: Lab Test Types for Request Form
        $testTypes = LabTestType::all()->groupBy('department');

        return view('doctor.patient_profile', compact('patient', 'visitHistory', 'prescriptions', 'labTests', 'testTypes'));
    }

    public function completeAppointment(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();

        if ($appointment->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Cannot complete appointment. Consultation fee is still pending payment.');
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'clinical_notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => 'completed',
            'diagnosis' => $request->diagnosis,
            'clinical_notes' => $request->clinical_notes,
        ]);

        return redirect()->back()->with('success', 'Appointment marked as completed.');
    }

    public function storePrescription(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'medicines' => 'required|string',
            'dosage' => 'required|string',
            'instructions' => 'nullable|string',
        ]);

        Prescription::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $request->patient_id,
            'diagnosis' => $request->diagnosis,
            'medicines' => $request->medicines,
            'dosage' => $request->dosage,
            'instructions' => $request->instructions,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Prescription added successfully.');
    }

    public function storeLabTest(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_type_id' => 'required|exists:lab_test_types,id',
            'instructions' => 'nullable|string',
        ]);

        $testType = LabTestType::find($request->test_type_id);

        LabTest::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $request->patient_id,
            'appointment_id' => $request->appointment_id,
            'test_type_id' => $testType->id,
            'department' => $testType->department,
            'test_name' => $testType->test_name,
            'instructions' => $request->instructions,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Lab test requested successfully.');
    }

    public function printLabTest($id)
    {
        $lt = LabTest::with(['patient.user', 'doctor.user', 'parameterValues.parameter'])->findOrFail($id);
        return view('labstaff.print_report', compact('lt'));
    }

    public function searchMedicines(Request $request)
    {
        $term = $request->get('term');
        $medicines = \App\Models\Medicine::where('name', 'like', '%' . $term . '%')
            ->where('quantity', '>', 0)
            ->take(10)
            ->get(['id', 'name', 'quantity']);

        return response()->json($medicines);
    }
}
