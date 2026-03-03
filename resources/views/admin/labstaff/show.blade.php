@extends('layouts.dashboard')

@section('title', 'Lab Staff Details')
@section('header', 'Lab Staff Details')

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

        .btn-edit-details {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-edit-details:hover {
            background: #059669;
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
            border: 1px solid #e2e8f0;
        }

        .detail-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 500;
            color: #0f172a;
        }

        .details-footer {
            margin-top: 30px;
        }

        .btn-back-details {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .btn-back-details:hover {
            color: #1e293b;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card details-card">
        <div class="details-header">
            <div>
                <h2 style="margin: 0; color: #1e293b;">{{ $staff->name }}</h2>
                <p style="margin: 5px 0 0; color: #64748b;">{{ $staff->lab_id }}</p>
            </div>
            <a href="{{ route('admin.labstaff.edit', $staff->id) }}" class="btn-edit-details">
                <i class="fas fa-edit"></i> Edit Staff
            </a>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">Lab ID</div>
                <div class="detail-value">{{ $staff->lab_id }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ $staff->name }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Department</div>
                <div class="detail-value">
                    <span
                        style="background: #ecfdf5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                        {{ $staff->department }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Phone Number</div>
                <div class="detail-value">{{ $staff->phone }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ $staff->email }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Date Added</div>
                <div class="detail-value">{{ $staff->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <div class="details-footer">
            <a href="{{ route('admin.labstaff.index') }}" class="btn-back-details">
                <i class="fas fa-arrow-left"></i> Back to Lab Staff List
            </a>
        </div>
    </div>
@endsection