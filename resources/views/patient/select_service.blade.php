@extends('layouts.dashboard')

@section('title', 'Select Service')
@section('header', '') {{-- Clearing default header to use custom one --}}

@section('content')
    <div class="welcome-section mb-5">
        <h1 class="fw-bold">Welcome back, {{ Auth::user()->name }}!</h1>
    </div>

    <div class="services-row">
        <!-- Doctor Consultation -->
        <div class="service-mega-card">
            <div class="service-content">
                <div class="icon-box consultation">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Doctor Consultation</h3>
                <p>Connect with expert specialists for personalized medical advice and treatment plans.</p>
                <div class="action-footer">
                    <a href="{{ route('patient.appointments.book') }}" class="btn-main">
                        Book Appointment <i class="fas fa-chevron-right ms-2 mt-1" style="font-size: 12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pharmacy -->
        <div class="service-mega-card">
            <div class="service-content">
                <div class="icon-box pharmacy">
                    <i class="fas fa-pills"></i>
                </div>
                <h3>Pharmacy Services</h3>
                <p>Access your digital prescriptions and order medicines.</p>
                <div class="action-footer">
                    <a href="{{ route('patient.prescriptions') }}" class="btn-main">
                        Pharmacy Portal <i class="fas fa-chevron-right ms-2 mt-1" style="font-size: 12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Lab Tests -->
        <div class="service-mega-card">
            <div class="service-content">
                <div class="icon-box lab">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Laboratory Tests</h3>
                <p>Schedule medical tests and access your detailed diagnostic reports securely.</p>
                <div class="action-footer dual">
                    <a href="{{ route('patient.lab.book') }}" class="btn-main outline">Book Test</a>
                    <a href="{{ route('patient.lab_reports') }}" class="btn-main">Reports</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --accent: #4f46e5;
            --gradient: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
        }

        .main-content {
            background: #f8fafc !important;
            padding: 40px !important;
        }

        .welcome-section h1 {
            font-size: 36px;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .services-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .service-mega-card {
            background: white;
            border-radius: 24px;
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .service-mega-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .consultation {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }

        .pharmacy {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .lab {
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
        }

        .service-mega-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .service-content h3 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .service-content p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 35px;
            min-height: 48px;
        }

        .btn-main {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px 24px;
            background: var(--gradient);
            color: white !important;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
        }

        .btn-main.outline {
            background: white;
            border: 2px solid #e2e8f0;
            color: #1e293b !important;
        }

        .btn-main.outline:hover {
            border-color: var(--primary);
            color: var(--primary) !important;
        }

        .btn-main:hover:not(.outline) {
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            transform: translateY(-2px);
        }

        .action-footer.dual {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 12px;
        }

        .dashboard-shortcut {
            text-align: center;
            padding: 24px;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .dashboard-shortcut:hover {
            background: #f1f5f9;
        }

        .dashboard-shortcut a {
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        @media (max-width: 991px) {
            .main-content {
                padding: 20px !important;
            }

            .services-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection