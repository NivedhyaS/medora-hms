<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\DoctorSchedule;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('specialization', 'schedules')->get();
        return view('admin.schedules.index', compact('doctors'));
    }

    public function edit($doctorId)
    {
        $doctor = Doctor::with('schedules')->findOrFail($doctorId);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.schedules.edit', compact('doctor', 'days'));
    }

    public function update(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $request->validate([
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'nullable|required_with:schedules.*.end_time|date_format:H:i',
            'schedules.*.end_time' => 'nullable|required_with:schedules.*.start_time|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.slot_duration' => 'nullable|integer|min:5|max:60',
        ]);

        foreach ($request->schedules as $day => $data) {
            if (!empty($data['start_time']) && !empty($data['end_time'])) {
                DoctorSchedule::updateOrCreate(
                    ['doctor_id' => $doctorId, 'day_of_week' => $day],
                    [
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'slot_duration' => $data['slot_duration'] ?? 15,
                    ]
                );
            } else {
                DoctorSchedule::where('doctor_id', $doctorId)->where('day_of_week', $day)->delete();
            }
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }
}
