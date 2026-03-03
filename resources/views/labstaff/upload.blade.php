@extends('layouts.dashboard')

@section('title', 'Upload Lab Report')
@section('header', 'Upload Lab Report')

@section('content')
    <div class="card full-width">
        <form action="{{ route('labstaff.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Select Patient</label>
                <select name="patient_id" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" required>
                    <option value="">-- Select Patient --</option>
                    @foreach(\App\Models\Patient::with('user')->get() as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->user->name }} ({{ $patient->patient_id }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Test Name</label>
                <input type="text" name="test_name" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                    placeholder="Example: Blood Glucose Test" required>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Summary / Findings</label>
                <textarea name="result_summary" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;" rows="4"></textarea>
            </div>

            <div class="mb-3" style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Upload Report (PDF/Images)</label>
                <input type="file" name="report_file" class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                    accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <button type="submit" class="btn-primary"
                style="padding: 10px 20px; border: none; cursor: pointer; border-radius: 6px;">Submit Report</button>
        </form>
    </div>
@endsection