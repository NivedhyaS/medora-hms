<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientAuthController extends Controller
{
    /* =========================
       REGISTER
    ========================== */

    // Show patient registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle patient registration
    public function register(Request $request)
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
            'password' => 'required|min:6|confirmed',
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
        \App\Models\Patient::create([
            'user_id' => $user->id,
            'patient_id' => 'P-' . strtoupper(uniqid()),
            'medical_history' => null,
        ]);


        return redirect()
            ->route('auth.login')
            ->with('success', 'Registration successful. Please login.');
    }

    /* =========================
       LOGIN
    ========================== */

    // Show patient login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle patient login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 👇 THIS IS THE KEY PART
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard'); // patient
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ]);
    }

    /* =========================
       LOGOUT
    ========================== */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

}
