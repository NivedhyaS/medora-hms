@extends('layouts.dashboard')

@section('title', 'Find a Doctor')
@section('header', '')

@section('content')
    <div class="welcome-section mb-4">
        <h1 class="fw-bold">Find a Specialist</h1>
        <p class="text-secondary">Search and connect with our world-class medical professionals.</p>
    </div>

    <div class="search-section card mb-5">
        <form action="{{ route('patient.find_doctor') }}" method="GET" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by specialty, name, or expertise..." class="modern-input">
            </div>
            <div class="search-actions">
                <button type="submit" class="btn-search">Search Professionals</button>
                @if (request('search'))
                    <a href="{{ route('patient.find_doctor') }}" class="btn-clear" title="Clear Search">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="doctors-grid">
        @forelse($doctors as $doctor)
            <div class="doctor-mega-card">
                <div class="card-body">
                    <div class="doc-header">
                        <div class="doc-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="doc-info">
                            <h3>Dr. {{ $doctor->name }}</h3>
                            <div class="specialty-tag">
                                {{ $doctor->specialization->name ?? $doctor->specialization }}
                            </div>
                        </div>
                    </div>

                    <div class="doc-meta-stats mt-4">
                        <div class="meta-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Consultation: <strong>₹{{ $doctor->consultation_fee }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star text-warning"></i>
                            <span>Highly Rated</span>
                        </div>
                    </div>

                    <div class="availability-section mt-4">
                        <h4 class="section-label">Weekly Schedule</h4>
                        @if ($doctor->schedules->count() > 0)
                            <div class="schedule-mini-list">
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $schedules = $doctor->schedules->keyBy('day_of_week');
                                @endphp
                                @foreach ($days as $day)
                                    @if (isset($schedules[$day]))
                                        <div class="schedule-row">
                                            <span class="day-name">{{ $day }}</span>
                                            <span class="time-badge">
                                                {{ \Carbon\Carbon::parse($schedules[$day]->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($schedules[$day]->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="empty-schedule">
                                <i class="fas fa-calendar-alt me-2"></i> Contact clinic for availability
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer-action">
                    <a href="{{ route('patient.appointments.book', ['doctor_id' => $doctor->id]) }}" class="btn-action-main">
                        Request Appointment <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results-card">
                <div class="empty-icon"><i class="fas fa-user-md"></i></div>
                <h3>No specialists found</h3>
                <p>We couldn't find any doctors matching your search criteria. Please try different keywords.</p>
                <a href="{{ route('patient.find_doctor') }}" class="btn-reset">View All Doctors</a>
            </div>
        @endforelse
    </div>

    <style>
        .main-content {
            background: #f8fafc !important;
            padding: 35px !important;
        }

        .welcome-section h1 {
            font-size: 32px;
            color: #1e293b;
        }

        /* Search Section */
        .search-section {
            background: white;
            padding: 24px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-input-group {
            flex: 1;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
        }

        .modern-input {
            width: 100%;
            padding: 14px 14px 14px 50px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .modern-input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-search {
            background: #1e293b;
            color: white;
            padding: 14px 25px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-search:hover {
            background: #0f172a;
            transform: translateY(-1px);
        }

        .btn-clear {
            padding: 14px 18px;
            background: #f1f5f9;
            color: #64748b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .search-actions {
            display: flex;
            gap: 10px;
        }

        /* Doctors Grid */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 30px;
        }

        .doctor-mega-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .doctor-mega-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .card-body {
            padding: 30px;
            flex: 1;
        }

        .doc-header {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .doc-avatar {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .doc-info h3 {
            font-size: 19px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .specialty-tag {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .doc-meta-stats {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #64748b;
        }

        .meta-item i {
            font-size: 16px;
        }

        .section-label {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .schedule-mini-list {
            display: grid;
            gap: 10px;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .day-name {
            font-weight: 600;
            color: #475569;
        }

        .time-badge {
            background: #f8fafc;
            color: #1e293b;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        .card-footer-action {
            padding: 20px 30px 30px;
        }

        .btn-action-main {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white !important;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.3s ease;
        }

        .btn-action-main:hover {
            background: #1d4ed8;
            transform: scale(1.02);
            box-shadow: 0 10px 15px rgba(37, 99, 235, 0.2);
        }

        .no-results-card {
            grid-column: 1 / -1;
            padding: 80px;
            text-align: center;
            background: white;
            border-radius: 24px;
            border: 1px dashed #cbd5e1;
        }

        .empty-icon {
            font-size: 60px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .no-results-card p {
            color: #64748b;
            margin-bottom: 30px;
        }

        .btn-reset {
            display: inline-block;
            padding: 12px 30px;
            background: #f1f5f9;
            color: #1e293b;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 640px) {
            .search-form {
                flex-direction: column;
            }

            .search-actions {
                width: 100%;
            }

            .btn-search {
                flex: 1;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection