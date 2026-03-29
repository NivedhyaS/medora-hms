<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pharmacist;

class PharmacistController extends Controller
{
    public function index()
    {
        $pharmacists = Pharmacist::with(['user' => fn($q) => $q->withTrashed()])->get();
        return view('admin.pharmacists.index', compact('pharmacists'));
    }

    public function create()
    {
        return view('admin.pharmacists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pharm_id' => 'required|unique:pharmacists,pharm_id',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:6',
        ]);

        // Create User
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pharmacist',
        ]);

        Pharmacist::create([
            'user_id' => $user->id,
            'pharm_id' => $request->pharm_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
        ]);

        return redirect()
            ->route('admin.pharmacists.index')
            ->with('success', 'Pharmacist and their user account created successfully');
    }

    public function edit($id)
    {
        $pharmacist = Pharmacist::with(['user' => fn($q) => $q->withTrashed()])->findOrFail($id);
        return view('admin.pharmacists.edit', compact('pharmacist'));
    }

    public function update(Request $request, $id)
    {
        $pharmacist = Pharmacist::findOrFail($id);
        $user = $pharmacist->user;

        $request->validate([
            'pharm_id' => 'required|unique:pharmacists,pharm_id,' . $pharmacist->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : 0),
            'phone' => 'required',
            'password' => 'nullable|min:6',
        ]);

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

        $pharmacist->update([
            'pharm_id' => $request->pharm_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
        ]);

        return redirect()
            ->route('admin.pharmacists.index')
            ->with('success', 'Pharmacist Updated Successfully');
    }
    public function show(Pharmacist $pharmacist)
    {
        return view('admin.pharmacists.show', compact('pharmacist'));
    }

    public function destroy($id)
    {
        Pharmacist::destroy($id);

        return redirect()
            ->route('admin.pharmacists.index')
            ->with('success', 'Pharmacist Deleted Successfully');
    }
}
