<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ReceptionDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_payments' => Appointment::where('payment_status', 'pending')->count(),
        ];
        return view('reception.dashboard', compact('stats'));
    }

    public function patients()
    {
        $patients = Patient::with('user')->latest()->get();
        return view('reception.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        return view('reception.patients.create');
    }

    public function storePatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:15',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'blood_group' => $request->blood_group,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'password' => Hash::make($request->password),
            'role' => 'patient',
        ]);

        // Create Patient Profile
        Patient::create([
            'user_id' => $user->id,
            'patient_id' => 'P-' . strtoupper(uniqid()),
            'medical_history' => null,
        ]);

        return redirect()->route('reception.patients')->with('success', 'Patient registered successfully.');
    }

    public function appointments()
    {
        $appointments = Appointment::with([
            'doctor' => fn($q) => $q->withTrashed(),
            'user' => fn($q) => $q->withTrashed()
        ])->latest()->get();
        return view('reception.appointments.index', compact('appointments'));
    }

    public function createAppointment()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::all();
        return view('reception.appointments.create', compact('patients', 'doctors'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $doctor = Doctor::find($request->doctor_id);
        $patient = Patient::find($request->patient_id);

        // Check if day is available
        $dayOfWeek = Carbon::parse($request->appointment_date)->format('l');
        if (!in_array($dayOfWeek, $doctor->available_days)) {
            return back()->with('error', 'Doctor is not available on this day.');
        }

        $tokenNo = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->count() + 1;

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'user_id' => $patient->user_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'token_no' => $tokenNo,
            'consultation_fee' => $doctor->consultation_fee,
            'payment_status' => 'pending',
            'status' => 'booked',
        ]);

        return redirect()->route('reception.appointments')->with('success', 'Appointment booked successfully.');
    }

    public function billings()
    {
        $appointments = Appointment::with([
            'doctor' => fn($q) => $q->withTrashed(),
            'user' => fn($q) => $q->withTrashed()
        ])
            ->where('payment_status', 'pending')
            ->get();
        return view('reception.billings.index', compact('appointments'));
    }

    public function createBilling($appointment_id)
    {
        $appointment = Appointment::with([
            'doctor' => fn($q) => $q->withTrashed(),
            'patient.user' => fn($q) => $q->withTrashed()
        ])->findOrFail($appointment_id);
        return view('reception.billings.create', compact('appointment'));
    }

    public function storeBilling(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'payment_method' => 'required',
            'additional_charges' => 'nullable|numeric|min:0',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $total = $appointment->consultation_fee + ($request->additional_charges ?? 0);

        $billing = Billing::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_fee' => $appointment->consultation_fee,
            'additional_charges' => $request->additional_charges ?? 0,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
        ]);

        $appointment->update(['payment_status' => 'paid']);

        return redirect()->route('reception.billings.receipt', $billing->id)->with('success', 'Billing completed successfully.');
    }

    public function showReceipt(Billing $billing)
    {
        $billing->load([
            'appointment.doctor' => fn($q) => $q->withTrashed(),
            'patient.user' => fn($q) => $q->withTrashed()
        ]);
        return view('reception.billings.receipt', compact('billing'));
    }

    /* =========================
       PATIENT MANAGEMENT (EXTENDED)
    ========================== */

    public function showPatient($id)
    {
        $patient = User::with('appointments.doctor')
            ->where('role', 'patient')
            ->findOrFail($id);

        return view('reception.patients.show', compact('patient'));
    }

    public function editPatient($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);
        return view('reception.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'password' => 'nullable|min:6',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'mobile' => 'required|string|max:15',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:15',
        ]);

        $data = $request->except(['password']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $patient->update($data);

        return redirect()->route('reception.patients')->with('success', 'Patient updated successfully.');
    }

    /* =========================
       APPOINTMENT SLOT API
    ========================== */

    public function getSlots($doctorId, $date)
    {
        $doctor = Doctor::findOrFail($doctorId);

        // Use the common logic from AdminAppointmentController (or duplicated for now to avoid cross-controller calls if not needed)
        $start = Carbon::parse($doctor->availability_start);
        $end = Carbon::parse($doctor->availability_end);

        $bookedTimes = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->pluck('appointment_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        $slots = [];
        while ($start < $end) {
            $time = $start->format('H:i');
            $slots[] = [
                'time' => $time,
                'booked' => in_array($time, $bookedTimes),
            ];
            $start->addMinutes(15);
        }

        return response()->json($slots);
    }
}
