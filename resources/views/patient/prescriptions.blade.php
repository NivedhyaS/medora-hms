@extends('layouts.dashboard')

@section('title', 'My Prescriptions')
@section('header', '')

@section('content')
    <div class="welcome-section mb-4">
        <h1 class="fw-bold">My Prescriptions</h1>
        <p class="text-secondary">Manage your medications and upload external prescriptions for fulfillment.</p>
    </div>

    <!-- Upload Section -->
    <div class="upload-container-card mb-5">
        <div class="upload-header">
            <div class="upload-icon-circle">
                <i class="fas fa-file-upload"></i>
            </div>
            <div class="upload-text">
                <h3>External Prescription Upload</h3>
                <p>Have a prescription from an outside doctor? Upload it here for our pharmacists to review.</p>
            </div>
        </div>

        <form action="{{ route('patient.prescriptions.upload') }}" method="POST" enctype="multipart/form-data"
            class="modern-upload-form">
            @csrf
            <div class="row align-items-end g-3">
                <div class="col-lg-6">
                    <label class="form-label-premium">Select Document (PDF, JPG, PNG)</label>
                    <div class="custom-file-input">
                        <input type="file" name="prescription_file" id="prescription_file" accept=".pdf,.jpg,.jpeg,.png"
                            required onchange="updateFileName(this)">
                        <label for="prescription_file" id="file_label">
                            <i class="fas fa-cloud-upload-alt me-2"></i> <span>Choose file...</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn-submit-premium">
                        Review & Fulfill <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
            <p class="mt-2 small text-secondary">Maximum file size: 5MB</p>
        </form>
    </div>

    <!-- Tables Section -->
    <div class="prescriptions-grid">
        <!-- Hospital Prescriptions -->
        <div class="content-panel mb-4">
            <div class="panel-header">
                <h3><i class="fas fa-hospital-alt me-2"></i> Medora Hospital Records</h3>
                <span class="badge-count">{{ count($prescriptions) }} Records</span>
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Issuing Doctor</th>
                            <th>Medications</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td class="doc-cell">
                                    <div class="doc-label">Dr. {{ $prescription->doctor?->name ?? 'Specialist' }}</div>
                                </td>
                                <td>
                                    <div class="med-list">{{ $prescription->medicines }}</div>
                                    @if ($prescription->dosage)
                                        <small class="dosage-note">{{ $prescription->dosage }}</small>
                                    @endif
                                </td>
                                <td class="date-cell">{{ $prescription->created_at->format('d M, Y') }}</td>
                                <td>
                                    <span
                                        class="status-pill {{ $prescription->status == 'dispensed' ? 'bg-success-light' : 'bg-warning-light' }}">
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">No hospital prescriptions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- External History -->
        @if (count($uploadedPrescriptions) > 0)
            <div class="content-panel">
                <div class="panel-header">
                    <h3><i class="fas fa-history me-2"></i> Uploaded Documents History</h3>
                </div>
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Submission Date</th>
                                <th>Prescription Document</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($uploadedPrescriptions as $up)
                                <tr>
                                    <td class="date-cell">{{ $up->created_at->format('d M, Y | H:i') }}</td>
                                    <td>
                                        <a href="{{ Storage::url($up->file_path) }}" target="_blank" class="file-link">
                                            <i class="fas fa-file-medical me-2"></i> View Attachment
                                        </a>
                                    </td>
                                    <td>
                                        <span
                                            class="status-pill {{ $up->status == 'dispensed' ? 'bg-success-light' : 'bg-warning-light' }}">
                                            {{ ucfirst($up->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'Choose file...';
            document.querySelector('#file_label span').textContent = fileName;
        }
    </script>

    <style>
        .main-content {
            background: #f8fafc !important;
            padding: 35px !important;
        }

        /* Upload Card */
        .upload-container-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .upload-header {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 35px;
        }

        .upload-icon-circle {
            width: 60px;
            height: 60px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .upload-text h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .upload-text p {
            color: #64748b;
            margin: 5px 0 0;
        }

        .form-label-premium {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: block;
        }

        .custom-file-input {
            position: relative;
            overflow: hidden;
        }

        .custom-file-input input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .custom-file-input label {
            display: block;
            padding: 14px 20px;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            color: #475569;
            font-weight: 600;
            transition: all 0.2s ease;
            text-align: center;
        }

        .custom-file-input:hover label {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
        }

        .btn-submit-premium {
            width: 100%;
            padding: 15px;
            background: #1e293b;
            color: white;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-submit-premium:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Panel Styling */
        .content-panel {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
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

        .doc-label {
            font-weight: 700;
            color: #1e293b;
        }

        .med-list {
            font-weight: 600;
            color: #0f172a;
        }

        .dosage-note {
            color: #64748b;
            font-size: 12px;
            display: block;
            margin-top: 4px;
        }

        .date-cell {
            font-size: 14px;
            color: #475569;
            font-weight: 500;
        }

        .status-pill {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .bg-success-light {
            background: #dcfce7;
            color: #166534;
        }

        .bg-warning-light {
            background: #fef9c3;
            color: #854d0e;
        }

        .file-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 60px !important;
            color: #94a3b8;
        }

        @media (max-width: 991px) {
            .upload-container-card {
                padding: 25px;
            }

            .premium-table th,
            .premium-table td {
                padding: 15px;
            }
        }
    </style>
@endsection