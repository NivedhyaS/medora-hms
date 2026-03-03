@extends('layouts.dashboard')

@section('title', 'My Appointments')
@section('header', '')

@section('content')
    <div class="welcome-section mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h1 class="fw-bold">My Appointments</h1>
            <p class="text-secondary">Track your medical visits and manage your healthcare schedule.</p>
        </div>
        <a href="{{ route('patient.appointments.book') }}" class="btn-primary-premium">
            <i class="fas fa-plus-circle me-2"></i> Book New Appointment
        </a>
    </div>

    @if (session('success_booking'))
        <div class="premium-alert-success mb-5">
            <div class="d-flex align-items-center gap-4">
                <div class="alert-icon-main">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content-area">
                    <h4 class="alert-title">Booking Confirmed!</h4>
                    <p class="alert-text">Your appointment has been successfully scheduled. <strong>Please settle the
                            consultation fee at the reception upon arrival.</strong></p>
                    <div class="booking-receipt-mini mt-3">
                        <div class="receipt-item">
                            <span class="r-label">Doctor</span>
                            <span class="r-value">Dr. {{ session('success_booking')['doctor'] }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="r-label">Schedule</span>
                            <span class="r-value">{{ session('success_booking')['date'] }} at
                                {{ session('success_booking')['time'] }}</span>
                        </div>
                        <div class="receipt-item">
                            <span class="r-label">Consultation Fee</span>
                            <span class="r-value text-success">₹{{ session('success_booking')['fee'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="content-panel">
        <div class="panel-header">
            <h3><i class="fas fa-history me-2"></i> Appointment History</h3>
            <span class="badge-count">{{ count($appointments) }} Scheduled</span>
        </div>
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>TKN</th>
                        <th>Consulting Specialist</th>
                        <th>Date & Time</th>
                        <th>Fee</th>
                        <th>Payment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="token-box">
                                    #{{ $appointment->token_no }}
                                </div>
                            </td>
                            <td class="doc-cell">
                                <div class="d-flex align-items-center">
                                    <div class="sm-avatar me-2"><i class="fas fa-user-md"></i></div>
                                    <div class="doc-label">Dr. {{ $appointment->doctor->name ?? 'Specialist' }}</div>
                                </div>
                            </td>
                            <td class="date-cell">
                                <span
                                    class="d-block font-weight-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}</span>
                                <small
                                    class="text-secondary">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                            </td>
                            <td class="fee-cell">₹{{ $appointment->consultation_fee }}</td>
                            <td>
                                <span
                                    class="status-pill {{ $appointment->payment_status == 'paid' ? 'bg-success-light' : 'bg-danger-light' }}">
                                    {{ ucfirst($appointment->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($appointment->status) {
                                        'completed' => 'bg-success-light',
                                        'cancelled' => 'bg-danger-light',
                                        'pending' => 'bg-info-light',
                                        default => 'bg-secondary-light'
                                    };
                                @endphp
                                <span class="status-pill {{ $statusClass }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-calendar">
                                    <i class="far fa-calendar-times"></i>
                                </div>
                                <p>No scheduled appointments found.</p>
                                <a href="{{ route('patient.appointments.book') }}" class="btn-text mt-2">Book your first
                                    appointment</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .main-content {
            background: #f8fafc !important;
            padding: 35px !important;
        }

        .btn-primary-premium {
            background: #2563eb;
            color: white !important;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .btn-primary-premium:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* Success Alert */
        .premium-alert-success {
            background: white;
            border: 1px solid #d1fae5;
            border-left: 5px solid #10b981;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);
        }

        .alert-icon-main {
            width: 60px;
            height: 60px;
            background: #ecfdf5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .alert-title {
            font-size: 20px;
            font-weight: 800;
            color: #064e3b;
            margin-bottom: 8px;
        }

        .alert-text {
            font-size: 15px;
            color: #065f46;
            margin-bottom: 0;
            line-height: 1.6;
        }

        .booking-receipt-mini {
            display: flex;
            gap: 30px;
            padding: 15px 25px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .receipt-item {
            display: flex;
            flex-direction: column;
        }

        .r-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .r-value {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }

        /* Panel Styling */
        .content-panel {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .panel-header {
            padding: 24px 30px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .badge-count {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Table Styling */
        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-table th {
            padding: 18px 30px;
            background: #f8fafc;
            font-size: 13px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .premium-table td {
            padding: 20px 30px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .token-box {
            width: 42px;
            height: 42px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 13px;
            border: 1px solid #dbeafe;
        }

        .sm-avatar {
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            color: #475569;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .doc-label {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        .date-cell {
            font-size: 14px;
            color: #1e293b;
        }

        .fee-cell {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        .status-pill {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .bg-success-light {
            background: #dcfce7;
            color: #166534;
        }

        .bg-danger-light {
            background: #fee2e2;
            color: #991b1b;
        }

        .bg-info-light {
            background: #dbeafe;
            color: #1e40af;
        }

        .bg-secondary-light {
            background: #f1f5f9;
            color: #475569;
        }

        .empty-state {
            text-align: center;
            padding: 80px 30px !important;
            color: #94a3b8;
        }

        .empty-calendar {
            font-size: 48px;
            color: #f1f5f9;
            margin-bottom: 15px;
        }

        .btn-text {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 991px) {
            .booking-receipt-mini {
                flex-direction: column;
                gap: 10px;
            }

            .premium-table th,
            .premium-table td {
                padding: 15px 20px;
            }
        }
    </style>
@endsection