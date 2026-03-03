@extends('layouts.dashboard')

@section('title', 'Appointments')
@section('header', 'All Appointments')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Appointment History</h3>
            <a href="{{ route('reception.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book Appointment
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Token</th>
                    <th>Date & Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Fee</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                    <tr>
                        <td>
                            <div style="font-weight: 800; color: #2563eb; background: #eff6ff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #dbeafe;">
                                #{{ $appt->token_no }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">
                                {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M, Y') }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $appt->appointment_time }}</div>
                        </td>
                        <td><strong>{{ $appt->user->name ?? 'Deleted Patient' }}</strong></td>
                        <td>Dr. {{ $appt->doctor->name ?? 'Deleted Doctor' }}</td>
                        <td style="font-weight: 600;">₹{{ $appt->consultation_fee }}</td>
                        <td>
                            <span
                                style="background: {{ $appt->payment_status == 'paid' ? '#d1fae5' : '#fee2e2' }}; color: {{ $appt->payment_status == 'paid' ? '#065f46' : '#b91c1c' }}; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700;">
                                {{ strtoupper($appt->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span
                                style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700;">
                                {{ strtoupper($appt->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection