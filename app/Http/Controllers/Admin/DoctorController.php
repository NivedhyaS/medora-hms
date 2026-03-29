<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // READ – list doctors
    public function index()
    {
        $doctors = Doctor::with(['specialization', 'user' => fn($q) => $q->withTrashed()])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    // CREATE – show form
    public function create()
    {
        $specializations = \App\Models\Specialization::all();
        return view('admin.doctors.create', compact('specializations'));
    }

    // STORE – save doctor with per-day schedules
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:doctors,email|unique:users,email',
            'password' => 'required|min:6',
            'available_days' => 'required|array|min:1',
            'consultation_fee' => 'required|numeric|min:0',
            'schedules' => 'required|array',
        ]);

        // Get specialization name for backward compatibility
        $specName = \App\Models\Specialization::find($request->specialization_id)->name;

        // Create User first
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'doctor',
        ]);

        // Get general start/end from the first selected day for summary
        $firstDay = $request->available_days[0];
        $availabilityStart = $request->schedules[$firstDay]['start_time'] ?? '09:00';
        $availabilityEnd = $request->schedules[$firstDay]['end_time'] ?? '17:00';

        // Create Doctor linked to user
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'specialization' => $specName,
            'specialization_id' => $request->specialization_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'available_days' => $request->available_days,
            'consultation_fee' => $request->consultation_fee,
            'availability_start' => $availabilityStart,
            'availability_end' => $availabilityEnd,
        ]);

        // Create specific schedules
        foreach ($request->available_days as $day) {
            $data = $request->schedules[$day] ?? [];
            if (!empty($data['start_time']) && !empty($data['end_time'])) {
                \App\Models\DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'slot_duration' => $data['slot_duration'] ?? 15,
                ]);
            }
        }

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor and their user account created successfully');
    }

    // EDIT – show edit form
    public function edit(Doctor $doctor)
    {
        $doctor->load(['schedules', 'user' => fn($q) => $q->withTrashed()]);
        $specializations = \App\Models\Specialization::all();
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    // UPDATE – update doctor with per-day schedules
    public function update(Request $request, Doctor $doctor)
    {
        $user = $doctor->user;

        $request->validate([
            'name' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : 0),
            'password' => 'nullable|min:6',
            'available_days' => 'required|array|min:1',
            'consultation_fee' => 'required|numeric|min:0',
            'schedules' => 'required|array',
        ]);

        // Get specialization name for backward compatibility
        $specName = \App\Models\Specialization::find($request->specialization_id)->name;

        // Update User
        if ($user) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->password) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }
            $user->update($userData);
        }

        // Get general start/end from the first selected day for summary
        $firstDay = $request->available_days[0];
        $availabilityStart = $request->schedules[$firstDay]['start_time'] ?? '09:00';
        $availabilityEnd = $request->schedules[$firstDay]['end_time'] ?? '17:00';

        $doctor->update([
            'name' => $request->name,
            'specialization' => $specName,
            'specialization_id' => $request->specialization_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'available_days' => $request->available_days,
            'consultation_fee' => $request->consultation_fee,
            'availability_start' => $availabilityStart,
            'availability_end' => $availabilityEnd,
        ]);

        // Sync schedules
        // Remove old schedules not in the new available_days
        \App\Models\DoctorSchedule::where('doctor_id', $doctor->id)
            ->whereNotIn('day_of_week', $request->available_days)
            ->delete();

        // Update or create new ones
        foreach ($request->available_days as $day) {
            $data = $request->schedules[$day] ?? [];
            if (!empty($data['start_time']) && !empty($data['end_time'])) {
                \App\Models\DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                    [
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'slot_duration' => $data['slot_duration'] ?? 15,
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully');
    }

    // DELETE
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully');
    }
}
