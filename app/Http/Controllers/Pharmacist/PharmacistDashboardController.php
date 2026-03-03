<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Medicine;

class PharmacistDashboardController extends Controller
{
    public function index()
    {
        $pendingPrescriptions = Prescription::where('status', 'pending')->latest()->take(10)->get();
        $pendingUploaded = \App\Models\UploadedPrescription::where('status', 'pending')->latest()->get();
        $lowStockMedicines = Medicine::where('quantity', '<', 10)->get();

        return view('pharmacist.dashboard', compact('pendingPrescriptions', 'lowStockMedicines', 'pendingUploaded'));
    }

    public function prescriptions()
    {
        $prescriptions = Prescription::latest()->get();
        return view('pharmacist.prescriptions', compact('prescriptions'));
    }

    public function dispense($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->update([
            'status' => 'dispensed',
            'dispensed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Prescription marked as dispensed');
    }

    public function uploadedPrescriptions()
    {
        $uploadedPrescriptions = \App\Models\UploadedPrescription::with('patient.user')->latest()->get();
        return view('pharmacist.uploaded_prescriptions', compact('uploadedPrescriptions'));
    }

    public function updateUploadedPrescription(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dispensed',
        ]);

        $up = \App\Models\UploadedPrescription::findOrFail($id);
        $up->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Uploaded prescription status updated.');
    }

    public function medicines()
    {
        $medicines = Medicine::latest()->get();
        return view('pharmacist.medicines.index', compact('medicines'));
    }

    public function createMedicine()
    {
        return view('pharmacist.medicines.create');
    }

    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        Medicine::create($request->all());

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function editMedicine($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('pharmacist.medicines.edit', compact('medicine'));
    }

    public function updateMedicine(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $medicine->update($request->all());

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroyMedicine($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
