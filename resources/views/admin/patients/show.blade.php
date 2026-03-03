@extends('layouts.dashboard')

@section('title', 'Patient Details')
@section('header', 'Patient Details')


@section('content')

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-back {
            background: #e5e7eb;
            color: #0f172a;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #c7d2fe;
            color: #1d4ed8;
        }

        .info-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
            margin-bottom: 25px;
        }

        .info-row {
            margin: 8px 0;
        }

        .info-row b {
            display: inline-block;
            width: 160px;
        }
    </style>

    <div class="page-header">


        <a href="{{ route('admin.patients.index') }}" class="btn-back">
            ← Back to Patients
        </a>
    </div>

    <div class="info-card">
        <div class="info-row"><b>Name:</b> {{ $patient->name }}</div>
        <div class="info-row"><b>Email:</b> {{ $patient->email }}</div>
        <div class="info-row"><b>Gender:</b> {{ ucfirst($patient->gender) }}</div>
        <div class="info-row">
            <b>Date of Birth:</b>
            {{ \Carbon\Carbon::parse($patient->dob)->format('d M Y') }}
        </div>
        <div class="info-row"><b>Blood Group:</b> {{ $patient->blood_group }}</div>
        <div class="info-row"><b>Mobile:</b> {{ $patient->mobile }}</div>
        <div class="info-row"><b>Address:</b> {{ $patient->address }}</div>
        <div class="info-row"><b>Emergency Contact:</b> {{ $patient->emergency_contact }}</div>
    </div>

    <h3>Appointment History</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patient->appointments as $appt)
                <tr>
                    <td>{{ $appt->appointment_date }}</td>
                    <td>{{ $appt->appointment_time }}</td>
                    <td>{{ $appt->doctor->name ?? 'Deleted Doctor' }}</td>
                    <td>{{ ucfirst($appt->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No appointments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection