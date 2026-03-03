<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabTest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return view('patient.dashboard', [
                'user' => $user,
                'appointments' => collect(),
                'prescriptions' => collect(),
                'labTests' => collect()
            ]);
        }

        $appointments = Appointment::where('patient_id', $patient->id)->latest()->take(5)->get();
        $prescriptions = $patient->prescriptions()->latest()->take(5)->get();
        $labTests = $patient->labTests()->with(['doctor.user', 'parameterValues.parameter'])->latest()->take(5)->get();

        return view('patient.dashboard', compact('user', 'appointments', 'prescriptions', 'labTests'));
    }

    public function selectService()
    {
        return view('patient.select_service');
    }

    public function appointments()
    {
        $patient = Auth::user()->patient;
        $appointments = $patient ? Appointment::where('patient_id', $patient->id)->latest()->get() : collect();
        return view('patient.appointments', compact('appointments'));
    }

    public function createAppointment(Request $request)
    {
        $specializations = \App\Models\Specialization::with('doctors')->get();
        $doctors = Doctor::with('specialization', 'schedules')->get();
        $selected_doctor_id = $request->get('doctor_id');
        return view('patient.book_appointment', compact('doctors', 'specializations', 'selected_doctor_id'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient profile not found. Please complete your profile first.');
        }

        $doctor = Doctor::with('schedules')->findOrFail($request->doctor_id);

        // Check availability day
        $dayOfWeek = Carbon::parse($request->appointment_date)->format('l');

        $schedule = $doctor->schedules->where('day_of_week', $dayOfWeek)->first();
        if ($schedule) {
            // Already available via specific schedule
        } else {
            $availableDays = is_array($doctor->available_days) ? $doctor->available_days : [];
            if (!in_array($dayOfWeek, $availableDays)) {
                return back()->with('error', 'Doctor is not available on ' . $dayOfWeek);
            }
        }

        $tokenNo = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->count() + 1;

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'token_no' => $tokenNo,
            'consultation_fee' => $doctor->consultation_fee,
            'payment_status' => 'pending',
            'status' => 'booked',
        ]);

        return redirect()
            ->route('patient.appointments')
            ->with('success_booking', [
                'doctor' => $doctor->name,
                'date' => Carbon::parse($request->appointment_date)->format('d M, Y'),
                'time' => $request->appointment_time,
                'fee' => $doctor->consultation_fee
            ]);
    }

    public function getSlots($doctorId, $date)
    {
        $doctor = Doctor::with('schedules')->findOrFail($doctorId);
        $dayOfWeek = Carbon::parse($date)->format('l');

        // Find schedule for specific day
        $schedule = $doctor->schedules->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            // Fallback to old fields if no specific schedule set
            $availableDays = is_array($doctor->available_days) ? $doctor->available_days : [];
            if (!in_array($dayOfWeek, $availableDays)) {
                return response()->json(['error' => 'Doctor is not available on this day.'], 422);
            }
            $start = Carbon::parse($doctor->availability_start);
            $end = Carbon::parse($doctor->availability_end);
            $slotDuration = $doctor->slot_duration ?? 15;
        } else {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $slotDuration = $schedule->slot_duration;
        }

        $bookedTimes = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->pluck('appointment_time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        $slots = [];

        while ($start < $end) {
            $time = $start->format('H:i');

            $slots[] = [
                'time' => $time,
                'booked' => in_array($time, $bookedTimes),
            ];

            $start->addMinutes($slotDuration);
        }

        return response()->json($slots);
    }

    public function prescriptions()
    {
        $patient = Auth::user()->patient;
        $prescriptions = $patient ? $patient->prescriptions()->latest()->get() : collect();
        $uploadedPrescriptions = $patient ? $patient->uploadedPrescriptions()->latest()->get() : collect();
        return view('patient.prescriptions', compact('prescriptions', 'uploadedPrescriptions'));
    }

    public function uploadPrescription(Request $request)
    {
        $request->validate([
            'prescription_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'patient_note' => 'nullable|string|max:500',
        ]);

        $patient = Auth::user()->patient;
        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $path = $request->file('prescription_file')->store('uploaded_prescriptions', 'public');

        \App\Models\UploadedPrescription::create([
            'patient_id' => $patient->id,
            'file_path' => $path,
            'patient_note' => $request->patient_note,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Prescription uploaded successfully. The pharmacist will review it soon.');
    }

    public function labReports()
    {
        $patient = Auth::user()->patient;
        $labTests = $patient ? $patient->labTests()->with(['doctor.user', 'parameterValues.parameter'])->latest()->get() : collect();
        return view('patient.lab_reports', compact('labTests'));
    }

    public function findDoctor(Request $request)
    {
        $query = Doctor::with(['specialization', 'schedules']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('specialization', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $doctors = $query->get();
        $specializations = \App\Models\Specialization::all();

        return view('patient.find_doctor', compact('doctors', 'specializations'));
    }

    public function printLabTest($id)
    {
        $lt = LabTest::with(['patient.user', 'doctor.user', 'parameterValues.parameter'])->findOrFail($id);
        return view('labstaff.print_report', compact('lt'));
    }

    public function bookLabTest()
    {
        $testTypes = \App\Models\LabTestType::all()->groupBy('department');
        return view('patient.book_lab_test', compact('testTypes'));
    }

    public function storeLabTestRequest(Request $request)
    {
        $request->validate([
            'test_type_id' => 'required|exists:lab_test_types,id',
            'instructions' => 'nullable|string',
        ]);

        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $testType = \App\Models\LabTestType::findOrFail($request->test_type_id);

        LabTest::create([
            'patient_id' => $patient->id,
            'test_type_id' => $testType->id,
            'department' => $testType->department,
            'test_name' => $testType->test_name,
            'instructions' => $request->instructions,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->route('patient.lab_reports')->with('success', 'Lab test requested successfully.');
    }
}
