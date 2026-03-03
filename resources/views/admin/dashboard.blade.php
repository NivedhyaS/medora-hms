@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('header', 'Admin Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Total Doctors</h3>
            <p style="font-size: 24px; font-weight: bold; color: #111827;">{{ $totalDoctors }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Total Patients</h3>
            <p style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ $totalPatients }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Total Staff</h3>
            <p style="font-size: 24px; font-weight: bold; color: #059669;">{{ $totalStaff }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Total Appointments</h3>
            <p style="font-size: 24px; font-weight: bold; color: #6366f1;">{{ $totalAppointments }}</p>
        </div>
    </div>

    <!-- Quick Links / Navigation Cards -->
    <div class="cards" style="margin-top: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 10px;">Doctors</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">Manage doctor details and availability</p>
            <a href="{{ route('admin.doctors.index') }}"
                style="color: #2563eb; text-decoration: none; font-weight: 600;">View Doctors →</a>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 10px;">Appointments</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">View and manage appointments</p>
            <a href="{{ route('admin.appointments.index') }}"
                style="color: #2563eb; text-decoration: none; font-weight: 600;">View Appointments →</a>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 10px;">Patients</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">Check patient records and history</p>
            <a href="{{ route('admin.patients.index') }}"
                style="color: #2563eb; text-decoration: none; font-weight: 600;">View Patients →</a>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 10px;">Pharmacists</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">Manage pharmacy staff and medicine access</p>
            <a href="{{ route('admin.pharmacists.index') }}"
                style="color: #2563eb; text-decoration: none; font-weight: 600;">View Pharmacists →</a>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 10px;">Lab Staff</h3>
            <p style="color: #6b7280; margin-bottom: 15px;">Manage lab technicians and test records</p>
            <a href="{{ route('admin.labstaff.index') }}"
                style="color: #2563eb; text-decoration: none; font-weight: 600;">View Lab Staff →</a>
        </div>
    </div>
@endsection