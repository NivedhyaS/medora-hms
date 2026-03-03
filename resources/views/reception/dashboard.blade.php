@extends('layouts.dashboard')

@section('title', 'Reception Dashboard')
@section('header', 'Reception Overview')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <!-- Stat Cards -->
        <div
            style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-left: 5px solid #2563eb;">
            <p style="color: #64748b; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;">Total Patients</p>
            <h2 style="margin: 0; font-size: 28px;">{{ $stats['total_patients'] }}</h2>
        </div>

        <div
            style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-left: 5px solid #059669;">
            <p style="color: #64748b; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;">Today's Appointments</p>
            <h2 style="margin: 0; font-size: 28px;">{{ $stats['today_appointments'] }}</h2>
        </div>

        <div
            style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-left: 5px solid #dc2626;">
            <p style="color: #64748b; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;">Pending Payments</p>
            <h2 style="margin: 0; font-size: 28px;">{{ $stats['pending_payments'] }}</h2>
        </div>
    </div>

    <div class="card">
        <h3>Quick Actions</h3>
        <div style="display: flex; gap: 15px;">
            <a href="{{ route('reception.patients.create') }}" class="btn btn-primary"
                style="padding: 15px 25px; text-decoration: none; border-radius: 8px;">
                <i class="fas fa-user-plus"></i> Register New Patient
            </a>
            <a href="{{ route('reception.appointments.create') }}" class="btn btn-primary"
                style="background: #059669; border-color: #059669; padding: 15px 25px; text-decoration: none; border-radius: 8px;">
                <i class="fas fa-calendar-plus"></i> Book Appointment
            </a>
            <a href="{{ route('reception.billings') }}" class="btn btn-primary"
                style="background: #7c3aed; border-color: #7c3aed; padding: 15px 25px; text-decoration: none; border-radius: 8px;">
                <i class="fas fa-file-invoice-dollar"></i> Manage Billing
            </a>
        </div>
    </div>
@endsection