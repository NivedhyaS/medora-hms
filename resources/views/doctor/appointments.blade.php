@extends('layouts.dashboard')

@section('title', 'My Appointments')
@section('header', 'My Appointments')

@section('content')
    <div class="card full-width">
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
                    <th style="padding: 10px;">Patient</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Time</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">{{ $appointment->patient->name }}</td>
                        <td style="padding: 10px;">{{ $appointment->appointment_date }}</td>
                        <td style="padding: 10px;">{{ $appointment->appointment_time }}</td>
                        <td style="padding: 10px;">
                            <span
                                style="background: {{ $appointment->status == 'completed' ? '#d1fae5' : '#dbeafe' }}; color: {{ $appointment->status == 'completed' ? '#065f46' : '#1e40af' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td style="padding: 10px;">
                            <a href="{{ route('doctor.prescriptions.create', $appointment->user_id) }}" class="btn-primary"
                                style="padding: 5px 10px; font-size: 12px; text-decoration: none;">Prescribe</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection