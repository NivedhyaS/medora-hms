<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
   public function index(Request $request)
{
    $doctors = Doctor::all();

    $appointments = Appointment::with(['doctor', 'patient'])
        ->when($request->doctor_id, function ($query) use ($request) {
            $query->where('doctor_id', $request->doctor_id);
        })
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    return view('admin.appointments.index', compact('appointments', 'doctors'));
}

}
