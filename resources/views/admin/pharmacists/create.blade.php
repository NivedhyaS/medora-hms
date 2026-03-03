@extends('layouts.dashboard')

@section('title', 'Add Pharmacist')
@section('header', 'Add Pharmacist')


@section('content')

    <style>
        .form-wrapper {
            max-width: 900px;
            margin: auto;
        }

        .form-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #1f2937;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .full-width {
            grid-column: span 2;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: #374151;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }

        .form-actions {
            margin-top: 35px;
            display: flex;
            gap: 15px;
        }

        .btn-save {
            background: #2563eb;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .btn-cancel {
            padding: 10px 25px;
            background: #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            font-weight: 500;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }
    </style>

    <div class="form-wrapper">
        <div class="form-card">



            <form method="POST" action="{{ route('admin.pharmacists.store') }}">
                @csrf

                <div class="form-grid">

                    <div>
                        <label>Pharmacist ID</label>
                        <input type="text" name="pharm_id" value="PHARM-{{ rand(1000, 9999) }}" required>
                    </div>

                    <div>
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter full name" required>
                    </div>

                    <div>
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div>
                        <label>Date of Birth</label>
                        <input type="date" name="dob">
                    </div>

                    <div>
                        <label>Contact Number</label>
                        <input type="text" name="phone" placeholder="Enter phone number" required>
                    </div>

                    <div>
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter email address" required>
                    </div>

                    <div class="full-width">
                        <label>Residential Address</label>
                        <textarea name="address" rows="3" placeholder="Enter address"></textarea>
                    </div>

                    <div class="full-width">
                        <label>Emergency Contact</label>
                        <input type="text" name="emergency_contact" placeholder="Emergency contact number">
                    </div>

                    <div class="full-width">
                        <label>Login Password</label>
                        <input type="password" name="password" placeholder="Minimum 6 characters" required>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Save</button>
                    <a href="{{ route('admin.pharmacists.index') }}" class="btn-cancel">Cancel</a>
                </div>

            </form>

        </div>
    </div>

@endsection