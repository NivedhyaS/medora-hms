@extends('layouts.dashboard')

@section('title', 'Write Prescription')
@section('header', 'Write Prescription for ' . $patient->name)

@section('content')
    <div class="card full-width">
        <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->patient->id }}">

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Symptoms</label>
                <textarea name="symptoms" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" rows="3"
                    required></textarea>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Diagnosis</label>
                <textarea name="diagnosis" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" rows="3"
                    required></textarea>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Medicines & Dosage</label>
                <textarea name="medicines" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" rows="4"
                    placeholder="Example: Paracetamol 500mg - Twice daily for 3 days" required></textarea>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Additional Notes</label>
                <textarea name="notes" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" rows="2"></textarea>
            </div>

            <button type="submit" class="btn-primary"
                style="padding: 10px 20px; border: none; cursor: pointer; border-radius: 6px;">Submit Prescription</button>
        </form>
    </div>
@endsection