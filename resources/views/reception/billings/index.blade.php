@extends('layouts.dashboard')

@section('title', 'Billing')
@section('header', 'Pending Payments')

@section('content')
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Appt Date</th>
                    <th>Consultation Fee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                    <tr>
                        <td><strong>{{ $appt->user->name ?? 'Deleted Patient' }}</strong></td>
                        <td>Dr. {{ $appt->doctor->name ?? 'Deleted Doctor' }}</td>
                        <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M, Y') }} at
                            {{ $appt->appointment_time }}
                        </td>
                        <td style="font-weight: 700; color: #059669;">₹{{ $appt->consultation_fee }}</td>
                        <td>
                            <a href="{{ route('reception.billings.create', $appt->id) }}" class="btn btn-primary"
                                style="padding: 6px 15px; font-size: 13px;">
                                <i class="fas fa-file-invoice"></i> Generate Bill
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection