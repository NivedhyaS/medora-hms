<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', User::ROLE_PATIENT)->get();
        $doctors = Doctor::all();

        $appointments = Appointment::with([
            'doctor' => fn($q) => $q->withTrashed(),
            'user' => fn($q) => $q->withTrashed()
        ])
            ->when($request->user_id, fn($q) =>
                $q->where('user_id', $request->user_id))
            ->when($request->doctor_id, fn($q) =>
                $q->where('doctor_id', $request->doctor_id))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view(
            'admin.appointments.index',
            compact('appointments', 'users', 'doctors')
        );
    }

    public function create()
    {
        return view('admin.appointments.create', [
            'users' => User::where('role', User::ROLE_PATIENT)->get(),
            'doctors' => Doctor::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'user_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $doctor = Doctor::with('schedules')->find($request->doctor_id);

        // Check availability day via schedule or legacy field
        $dayOfWeek = Carbon::parse($request->appointment_date)->format('l');
        $schedule = $doctor->schedules->where('day_of_week', $dayOfWeek)->first();
        if (!$schedule) {
            $availableDays = is_array($doctor->available_days) ? $doctor->available_days : [];
            if (!in_array($dayOfWeek, $availableDays)) {
                return back()->with('error', 'Doctor is not available on ' . $dayOfWeek);
            }
        }

        $patient = \App\Models\Patient::where('user_id', $request->user_id)->first();
        if (!$patient) {
            return redirect()->back()->with('error', 'Selected user does not have a patient profile.');
        }

        $tokenNo = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->count() + 1;

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->id,
            'user_id' => $request->user_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'token_no' => $tokenNo,
            'consultation_fee' => $doctor->consultation_fee,
            'payment_status' => 'pending',
            'status' => 'booked',
        ]);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment booked with Consultation Fee: ₹' . $doctor->consultation_fee);
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return back()->with('success', 'Appointment deleted');
    }

    public function getSlots($doctorId, $date)
    {
        $doctor = Doctor::with('schedules')->findOrFail($doctorId);
        $dayOfWeek = Carbon::parse($date)->format('l');

        // Find schedule for specific day
        $schedule = $doctor->schedules->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            $availableDays = is_array($doctor->available_days) ? $doctor->available_days : [];
            if (!in_array($dayOfWeek, $availableDays)) {
                return response()->json(['error' => 'Doctor unavailable'], 422);
            }
            $start = Carbon::parse($doctor->availability_start);
            $end = Carbon::parse($doctor->availability_end);
            $slotDuration = $doctor->slot_duration ?? 15;
        } else {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $slotDuration = $schedule->slot_duration ?: 15;
        }

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

            $start->addMinutes($slotDuration);
        }

        return response()->json($slots);
    }
}
