@extends('layouts.dashboard')

@section('title', 'Patient Dashboard')
@section('header', '')

@section('content')
    <div class="welcome-section mb-4">
        <h1 class="fw-bold">My Medical Dashboard</h1>
        <p class="text-secondary">Overview of your health records, appointments, and prescriptions.</p>
    </div>

    <div class="dashboard-stats mb-5">
        <div class="stat-card">
            <div class="stat-icon appointment">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ count($appointments) }}</h3>
                <p>Scheduled Appointments</p>
                <div class="mt-2">
                    <a href="{{ route('patient.appointments.book') }}" class="small-link">Book New <i
                            class="fas fa-plus ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon lab">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-info">
                <h3>{{ count($labTests) }}</h3>
                <p>Medical Reports</p>
                <div class="mt-2">
                    <a href="{{ route('patient.lab_reports') }}" class="small-link">View All <i
                            class="fas fa-external-link-alt ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon prescription">
                <i class="fas fa-file-prescription"></i>
            </div>
            <div class="stat-info">
                <h3>Pharmacy</h3>
                <p>Active Prescriptions</p>
                <div class="mt-2">
                    <a href="{{ route('patient.prescriptions') }}" class="small-link">Portal Access <i
                            class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-row">
        <!-- Personal Info -->
        <div class="profile-card mb-4">
            <div class="section-header">
                <h3><i class="fas fa-user-circle me-2"></i> Personal Information</h3>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <p>{{ $user->name }}</p>
                </div>
                <div class="info-item">
                    <label>Email Address</label>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="info-item">
                    <label>Gender</label>
                    <p>{{ $user->gender }}</p>
                </div>
                <div class="info-item">
                    <label>Blood Group</label>
                    <p class="badge-blood">{{ $user->blood_group }}</p>
                </div>
                <div class="info-item">
                    <label>Date of Birth</label>
                    <p>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M, Y') : 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Emergency Contact</label>
                    <p>{{ $user->emergency_contact }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="table-card">
            <div class="section-header">
                <h3><i class="fas fa-clock me-2"></i> Recent Appointments</h3>
                <a href="{{ route('patient.appointments') }}" class="btn-text">View All</a>
            </div>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments->take(5) as $appointment)
                            <tr>
                                <td class="doctor-name">
                                    <div class="d-flex align-items-center">
                                        <div class="sm-avatar me-2"><i class="fas fa-user-md"></i></div>
                                        <span>{{ $appointment->doctor ? 'Dr. ' . $appointment->doctor->name : 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <span
                                            class="d-block">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}</span>
                                        <small class="text-secondary">{{ $appointment->appointment_time }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($appointment->status) {
                                            'completed' => 'status-success',
                                            'pending' => 'status-warning',
                                            'cancelled' => 'status-danger',
                                            default => 'status-info'
                                        };
                                    @endphp
                                    <span class="status-pill {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                        @if($appointments->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <p class="text-secondary">No appointments found.</p>
                                    <a href="{{ route('patient.appointments.book') }}" class="btn-primary-sm mt-2">Book Your
                                        First Appointment</a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .main-content {
            background: #f8fafc !important;
            padding: 35px !important;
        }

        .welcome-section h1 {
            font-size: 32px;
            color: #1e293b;
            margin-bottom: 5px;
        }

        /* Dashboard Stats */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .appointment {
            background: #eff6ff;
            color: #2563eb;
        }

        .lab {
            background: #f0fdf4;
            color: #16a34a;
        }

        .prescription {
            background: #fef2f2;
            color: #dc2626;
        }

        .stat-info h3 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stat-info p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }

        .small-link {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        /* Profile & Table Cards */
        .profile-card,
        .table-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 15px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .info-item label {
            display: block;
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .info-item p {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
            font-size: 15px;
        }

        .badge-blood {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 10px;
            border-radius: 6px;
            font-weight: 800;
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table th {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            padding: 15px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .modern-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
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

        .doctor-name span {
            font-weight: 600;
            color: #1e293b;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-text {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection