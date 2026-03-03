<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Pharmacist;
use App\Models\LabStaff;

class AdminDashboardController extends Controller
{
  public function index()
  {
    $totalDoctors = Doctor::count();
    $totalPatients = Patient::count();
    $totalAppointments = Appointment::count();
    $totalStaff = Pharmacist::count() + LabStaff::count();

    return view('admin.dashboard', compact(
      'totalDoctors',
      'totalPatients',
      'totalAppointments',
      'totalStaff'
    ));
  }
}
