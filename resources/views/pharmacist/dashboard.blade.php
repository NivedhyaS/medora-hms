@extends('layouts.dashboard')

@section('title', 'Pharmacist Dashboard')
@section('header', 'Pharmacist Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Pending Prescriptions</h3>
            <p style="font-size: 24px; font-weight: bold; color: #d97706;">{{ count($pendingPrescriptions) }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Low Stock Alert</h3>
            <p style="font-size: 24px; font-weight: bold; color: #dc2626; margin-bottom: 5px;">{{ count($lowStockMedicines) }}</p>
            <a href="{{ route('pharmacist.medicines.index') }}" style="font-size: 12px; color: #2563eb; text-decoration: none;">Manage Inventory →</a>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">External Uploads</h3>
            <p style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ count($pendingUploaded) }}</p>
        </div>
    </div>

    <div class="card full-width" style="margin-top: 30px;">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-pills"></i> Pending Prescriptions to Dispense</h3>
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb;">
                    <th style="padding: 12px;">Patient Name</th>
                    <th style="padding: 12px;">Doctor Name</th>
                    <th style="padding: 12px;">Medicines</th>
                    <th style="padding: 12px;">Instructions</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPrescriptions as $prescription)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px; font-weight: 500;">{{ $prescription->patient?->user?->name ?? 'N/A' }}</td>
                        <td style="padding: 12px;">Dr. {{ $prescription->doctor?->name ?? 'N/A' }}</td>
                        <td style="padding: 12px;">
                            <div style="font-weight: 600; color: #111827;">{{ $prescription->medicines }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $prescription->dosage }}</div>
                        </td>
                        <td style="padding: 12px; font-size: 13px; color: #4b5563;">{{ $prescription->instructions ?? 'None' }}
                        </td>
                        <td style="padding: 12px;">
                            <span
                                style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600;">
                                {{ ucfirst($prescription->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <form action="{{ route('pharmacist.prescriptions.dispense', $prescription->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary"
                                    style="height: auto; padding: 8px 15px; font-size: 12px; background: #059669;">
                                    <i class="fas fa-check"></i> Mark as Dispensed
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #9ca3af;">
                            <i class="fas fa-clipboard-list" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                            No pending prescriptions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($pendingUploaded) > 0)
        <div class="card full-width" style="margin-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;"><i class="fas fa-upload"></i> Pending External Uploads</h3>
                <a href="{{ route('pharmacist.uploaded_prescriptions') }}"
                    style="color: #2563eb; text-decoration: none; font-weight: 500; font-size: 14px;">View All →</a>
            </div>
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: #f9fafb;">
                        <th style="padding: 12px;">Patient</th>
                        <th style="padding: 12px;">Prescription</th>
                        <th style="padding: 12px;">Note</th>
                        <th style="padding: 12px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUploaded as $up)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px;">
                                <div style="font-weight: 600;">{{ $up->patient->user->name }}</div>
                                <div style="font-size: 11px; color: #6b7280;">ID: {{ $up->patient->patient_id }}</div>
                            </td>
                            <td style="padding: 12px;">
                                <a href="{{ Storage::url($up->file_path) }}" target="_blank"
                                    style="color: #2563eb; text-decoration: none; font-size: 13px;">
                                    <i class="fas fa-file-medical"></i> View File
                                </a>
                            </td>
                            <td style="padding: 12px; font-size: 13px; color: #4b5563;">
                                {{ Str::limit($up->patient_note, 50) ?? '-' }}</td>
                            <td style="padding: 12px;">
                                <a href="{{ route('pharmacist.uploaded_prescriptions') }}" class="btn"
                                    style="height: auto; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 4px; text-decoration: none; font-size: 12px;">Review</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection