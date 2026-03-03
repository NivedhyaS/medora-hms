@extends('layouts.dashboard')

@section('title', 'Pharmacist Details')
@section('header', 'Pharmacist Details')


@section('content')

    <style>
        .details-card {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .details-title {
            font-size: 24px;
            font-weight: 600;
        }

        .details-subtitle {
            color: #64748b;
            margin-top: 5px;
        }

        .btn-edit {
            background: #22c55e;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: #16a34a;
            color: white;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 30px;
            margin-top: 20px;
        }

        .detail-item {
            background: #f8fafc;
            border-radius: 6px;
            padding: 14px;
        }

        .detail-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 500;
            color: #0f172a;
        }

        .details-footer {
            margin-top: 30px;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-back:hover {
            background: #d1d5db;
            color: #111827;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card shadow-sm border-0 details-card">

        <!-- HEADER -->
        <div class="details-header">
            <div>

            </div>

            <a href="{{ route('admin.pharmacists.edit', $pharmacist->id) }}" class="btn-edit">
                Edit
            </a>
        </div>

        <!-- DETAILS -->
        <div class="details-grid">

            <div class="detail-item">
                <div class="detail-label">Pharmacist ID</div>
                <div class="detail-value">
                    PHARM-{{ str_pad($pharmacist->id, 4, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ $pharmacist->name }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Gender</div>
                <div class="detail-value">{{ $pharmacist->gender }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Date of Birth</div>
                <div class="detail-value">
                    {{ $pharmacist->dob ? \Carbon\Carbon::parse($pharmacist->dob)->format('d M Y') : '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Phone Number</div>
                <div class="detail-value">{{ $pharmacist->phone }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ $pharmacist->email }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Residential Address</div>
                <div class="detail-value">{{ $pharmacist->address ?? '-' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Emergency Contact</div>
                <div class="detail-value">{{ $pharmacist->emergency_contact ?? '-' }}</div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="details-footer">
            <a href="{{ route('admin.pharmacists.index') }}" class="btn-back">
                ← Back to Pharmacists
            </a>
        </div>

    </div>

@endsection