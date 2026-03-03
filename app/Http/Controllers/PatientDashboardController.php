<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // logged-in patient

        return view('patient.dashboard', compact('user'));
    }
}
