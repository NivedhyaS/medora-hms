@extends('layouts.dashboard')

@section('title', 'My Lab Reports')
@section('header', '')

@section('content')
    <div class="reports-header-wrapper">
        <div class="header-text">
            <h1 class="fw-bold">My Lab Reports</h1>
            <p class="text-secondary">Track your medical tests and access certified laboratory results.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('patient.lab.book') }}" class="btn-primary-premium">
                <i class="fas fa-plus-circle me-2"></i> Request New Test
            </a>
        </div>
    </div>

    <div class="reports-masonry-grid">
        @forelse($labTests as $lt)
            <div class="report-mega-card {{ $lt->status == 'completed' ? 'completed' : 'pending' }}">
                <div class="card-status-bar {{ $lt->status == 'completed' ? 'bg-success' : 'bg-warning' }}"></div>

                <div class="report-header">
                    <div class="header-flex-row">
                        <div class="test-title-area">
                            <span class="category-tag">Diagnostic Report</span>
                            <h3 class="test-name">{{ $lt->test_name }}</h3>
                        </div>
                        <div class="status-badge-wrapper">
                            <span class="status-badge {{ $lt->status == 'completed' ? 'completed' : 'pending' }}">
                                {{ ucfirst($lt->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="report-meta">
                    <div class="meta-flex-grid">
                        <div class="meta-item">
                            <i class="fas fa-user-md"></i>
                            <span class="label">Requested By:</span>
                            <span class="value">
                                @if ($lt->doctor)
                                    Dr. {{ $lt->doctor->name }}
                                @else
                                    <span class="text-indigo">Direct Lab Request</span>
                                @endif
                            </span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-check"></i>
                            <span class="label">Issued:</span>
                            <span
                                class="value">{{ $lt->requested_at ? $lt->requested_at->format('d M, Y') : 'Processing' }}</span>
                        </div>
                    </div>
                </div>

                <div class="report-content-body">
                    @if ($lt->status == 'completed')
                        <div class="result-box-container">
                            @if ($lt->parameterValues->count() > 0)
                                @include('partials._lab_report_table', ['lt' => $lt])
                            @elseif($lt->result)
                                <div class="result-summary">
                                    <span class="summary-label">Physician's Summary:</span>
                                    <p class="summary-text">{{ $lt->result }}</p>
                                </div>
                            @endif

                            @if ($lt->file_path)
                                <div class="report-download-action">
                                    <a href="{{ asset('storage/' . $lt->file_path) }}" target="_blank" class="btn-download-report">
                                        <i class="fas fa-file-pdf me-2"></i> Download Digital PDF Result
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="processing-state">
                            <div class="loader-icon">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <p>Our laboratory specialists are currently processing your samples. Results will appear here once
                                verified.</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-reports-card">
                <div class="empty-vials">
                    <i class="fas fa-vials"></i>
                </div>
                <h3>No laboratory history found</h3>
                <p>You haven't requested any medical tests yet. Request your first diagnostic test through our digital portal.
                </p>
                <a href="{{ route('patient.lab.book') }}" class="btn-request-first mt-3">Start New Lab Request</a>
            </div>
        @endforelse
    </div>

    <style>
        .main-content {
            background: #f8fafc !important;
            padding: 35px !important;
        }

        /* Fixed Header Layout */
        .reports-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
            padding-bottom: 25px;
            width: 100%;
        }

        .header-text h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }

        .header-text p {
            margin: 8px 0 0 0;
            color: #64748b;
            font-size: 16px;
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
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .btn-primary-premium:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        }

        /* Grid System */
        .reports-masonry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(480px, 1fr));
            gap: 30px;
            width: 100%;
        }

        .report-mega-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            height: 100%;
        }

        .report-mega-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .card-status-bar {
            height: 6px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .report-header {
            padding: 35px 35px 0;
        }

        .header-flex-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }

        .category-tag {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            display: block;
            margin-bottom: 8px;
        }

        .test-name {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-badge.completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .report-meta {
            padding: 20px 35px;
        }

        .meta-flex-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .meta-item i {
            color: #64748b;
            font-size: 16px;
        }

        .meta-item .label {
            color: #94a3b8;
            font-weight: 600;
        }

        .meta-item .value {
            color: #1e293b;
            font-weight: 700;
        }

        .text-indigo {
            color: #6366f1;
        }

        .report-content-body {
            padding: 0 35px 35px;
            flex: 1;
        }

        .result-box-container {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #f1f5f9;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            display: block;
            margin-bottom: 10px;
        }

        .summary-text {
            color: #334155;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
        }

        .report-download-action {
            margin-top: 25px;
        }

        .btn-download-report {
            background: white;
            color: #1e293b !important;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-decoration: none !important;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-download-report:hover {
            background: #f8fafc;
            border-color: #2563eb;
            color: #2563eb !important;
        }

        .processing-state {
            text-align: center;
            padding: 50px 20px;
            color: #64748b;
        }

        .loader-icon {
            font-size: 40px;
            color: #e2e8f0;
            margin-bottom: 20px;
        }

        .empty-reports-card {
            grid-column: 1 / -1;
            padding: 120px 40px;
            text-align: center;
            background: white;
            border-radius: 32px;
            border: 1px dashed #cbd5e1;
        }

        .empty-vials {
            font-size: 80px;
            color: #f1f5f9;
            margin-bottom: 30px;
        }

        .empty-reports-card h3 {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
        }

        .empty-reports-card p {
            color: #64748b;
            margin-bottom: 30px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .btn-request-first {
            display: inline-block;
            padding: 16px 35px;
            background: #f1f5f9;
            color: #1e293b;
            border-radius: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-request-first:hover {
            background: #e2e8f0;
        }

        @media (max-width: 991px) {
            .reports-masonry-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .reports-header-wrapper {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .header-text h1 {
                font-size: 28px;
            }

            .report-header,
            .report-meta,
            .report-content-body {
                padding: 25px;
            }
        }
    </style>
@endsection