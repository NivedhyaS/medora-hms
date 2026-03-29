@extends('layouts.dashboard')

@section('title', 'Add Lab Staff')
@section('header', 'Add Laboratory Personnel')

@section('content')
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
            transition: all 0.2s;
            background-color: #f8fafc;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #10b981;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-control[readonly] {
            background-color: #f1f5f9;
            cursor: not-allowed;
            color: #64748b;
            font-weight: 600;
        }

        .btn-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .form-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('admin.labstaff.store') }}" method="POST" id="labStaffForm">
                @csrf

                <div class="form-grid">
                    <!-- Name -->
                    <div class="form-group full-width">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required autocomplete="off">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="" required autocomplete="off">
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required autocomplete="off">
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="department" id="department" class="form-select" required>
                            <option value="">-- Select Department --</option>
                            <option value="Clinical Pathology">Clinical Pathology</option>
                            <option value="Hematology">Hematology</option>
                            <option value="Biochemistry">Biochemistry</option>
                            <option value="Microbiology">Microbiology</option>
                            <option value="Histopathology">Histopathology</option>
                            <option value="Cytology">Cytology</option>
                            <option value="Immunology / Serology">Immunology / Serology</option>
                            <option value="Molecular Diagnostics">Molecular Diagnostics</option>
                        </select>
                    </div>

                    <!-- Generated Lab ID -->
                    <div class="form-group">
                        <label class="form-label">Generated Lab ID</label>
                        <input type="text" name="lab_id" id="lab_id" class="form-control" readonly
                            placeholder="Select department first">
                        <p class="form-hint">Auto-generated based on department</p>
                    </div>

                    <!-- Password -->
                    <div class="form-group full-width">
                        <label class="form-label">Login Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters"
                            required>
                        <p class="form-hint">This will be used for the staff member's login account</p>
                    </div>
                </div>

                <div class="btn-actions">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                        <i class="fas fa-check-circle"></i> Save Personnel
                    </button>
                    <a href="{{ route('admin.labstaff.index') }}" class="btn" style="background: #f1f5f9; color: #475569;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const departmentSelect = document.getElementById('department');
        const labIdInput = document.getElementById('lab_id');

        const departmentCodes = {
            'Clinical Pathology': 'CP',
            'Hematology': 'HM',
            'Biochemistry': 'BC',
            'Microbiology': 'MB',
            'Histopathology': 'HP',
            'Cytology': 'CT',
            'Immunology / Serology': 'IS',
            'Molecular Diagnostics': 'MD'
        };

        departmentSelect.addEventListener('change', function () {
            const dept = this.value;
            if (dept && departmentCodes[dept]) {
                const prefix = departmentCodes[dept];
                const random = Math.floor(1000 + Math.random() * 9000); // 4 digit random number
                labIdInput.value = `LAB-${prefix}-${random}`;
            } else {
                labIdInput.value = '';
            }
        });

        // Handle form validation for password length
        document.getElementById('labStaffForm').onsubmit = function (e) {
            const pass = this.password.value;
            if (pass.length < 6) {
                alert('Password must be at least 6 characters long');
                e.preventDefault();
                return false;
            }
            return true;
        };
    </script>
@endsection