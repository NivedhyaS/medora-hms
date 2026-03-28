@extends('layouts.dashboard')

@section('title', 'Patients')
@section('header', 'Manage Patients')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Patient Records</h3>
            <a href="{{ route('reception.patients.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Registration
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Dob</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td><code
                                style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $patient->patient_id }}</code>
                        </td>
                        <td><strong>{{ $patient->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ ucfirst($patient->user->gender ?? '-') }}</td>
                        <td>{{ $patient->user && $patient->user->dob ? \Carbon\Carbon::parse($patient->user->dob)->format('d M, Y') : '-' }}</td>
                        <td>{{ $patient->user->mobile ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('reception.patients.show', $patient->user_id) }}" class="btn"
                                    style="padding: 5px 10px; background: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0;"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('reception.patients.edit', $patient->user_id) }}" class="btn"
                                    style="padding: 5px 10px; background: #f1f5f9; color: #2563eb; border: 1px solid #e2e8f0;"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection