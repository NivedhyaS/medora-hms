<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = User::where('role', 'patient')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
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
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'patient',
        ]);

        // Create Patient Profile
        \App\Models\Patient::create([
            'user_id' => $user->id,
            'patient_id' => 'P-' . strtoupper(uniqid()),
            'medical_history' => null,
        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Patient added successfully');
    }

    public function show($id)
    {
        $patient = User::with('appointments.doctor')
            ->where('role', 'patient')
            ->findOrFail($id);

        return view('admin.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only([
            'name',
            'email',
            'gender',
            'dob',
            'blood_group',
            'mobile',
            'address',
            'emergency_contact',
        ]);

        if ($request->password) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $patient->update($data);

        return redirect()
            ->route('admin.patients.show', $patient->id)
            ->with('success', 'Patient updated successfully');
    }

    public function destroy($id)
    {
        User::where('role', 'patient')->findOrFail($id)->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully');
    }
}
