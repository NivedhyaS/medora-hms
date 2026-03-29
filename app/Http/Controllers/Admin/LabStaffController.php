<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabStaff;

class LabStaffController extends Controller
{
    // =============================================
    // INDEX - Show All Lab Staff
    // =============================================
    public function index()
    {
        $labstaff = LabStaff::with(['user' => fn($q) => $q->withTrashed()])->get();
        return view('admin.labstaff.index', compact('labstaff'));
    }


    // =============================================
    // CREATE - Show Add Form
    // =============================================
    public function create()
    {
        return view('admin.labstaff.create');
    }


    // =============================================
    // STORE - Save New Lab Staff
    // =============================================
    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|string|unique:lab_staff,lab_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'password' => 'required|min:6',
        ]);

        // Create User
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'labstaff',
        ]);

        LabStaff::create([
            'user_id' => $user->id,
            'lab_id' => $request->lab_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        return redirect()
            ->route('admin.labstaff.index')
            ->with('success', 'Lab Staff and their user account created successfully');
    }


    // =============================================
    // SHOW - View Single Staff
    // =============================================
    public function show($id)
    {
        $staff = LabStaff::findOrFail($id);
        return view('admin.labstaff.show', compact('staff'));
    }


    // =============================================
    // EDIT - Show Edit Form
    // =============================================
    public function edit($id)
    {
        $staff = LabStaff::with(['user' => fn($q) => $q->withTrashed()])->findOrFail($id);
        return view('admin.labstaff.edit', compact('staff'));
    }


    // =============================================
    // UPDATE - Update Staff Data
    // =============================================
    public function update(Request $request, $id)
    {
        $staff = LabStaff::with(['user' => fn($q) => $q->withTrashed()])->findOrFail($id);
        $user = $staff->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : 0) . '|max:255',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
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

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        return redirect()
            ->route('admin.labstaff.index')
            ->with('success', 'Lab Staff Updated Successfully');
    }


    // =============================================
    // DELETE - Remove Staff
    // =============================================
    public function destroy($id)
    {
        $staff = LabStaff::findOrFail($id);
        $staff->delete();

        return redirect()
            ->route('admin.labstaff.index')
            ->with('success', 'Lab Staff Deleted Successfully');
    }
}
