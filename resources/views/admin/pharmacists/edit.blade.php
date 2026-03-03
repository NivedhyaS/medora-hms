@extends('layouts.dashboard')

@section('title', 'Edit Pharmacist')
@section('header', 'Edit Pharmacist')


@section('content')

    <style>
        .form-card {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-subtitle {
            color: #64748b;
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        .form-group textarea {
            resize: none;
            height: 80px;
        }

        .readonly {
            background: #f1f5f9;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            gap: 12px;
        }

        .btn-update {
            background: #22c55e;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
        }

        .btn-update:hover {
            background: #16a34a;
        }

        .btn-cancel {
            padding: 10px 18px;
            border-radius: 6px;
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card shadow-sm border-0 form-card">


        <form method="POST" action="{{ route('admin.pharmacists.update', $pharmacist->id) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <!-- Pharmacist ID -->
                <div class="form-group">
                    <label>Pharmacist ID</label>
                    <input type="text" value="PHARM-{{ str_pad($pharmacist->id, 4, '0', STR_PAD_LEFT) }}" readonly
                        class="readonly">
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $pharmacist->name) }}" required>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" {{ $pharmacist->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $pharmacist->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $pharmacist->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ $pharmacist->dob }}">
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="phone" value="{{ $pharmacist->phone }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ $pharmacist->email }}" required>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label>Residential Address</label>
                    <textarea name="address">{{ $pharmacist->address }}</textarea>
                </div>

                <!-- Emergency Contact -->
                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ $pharmacist->emergency_contact }}">
                </div>

                <!-- Password -->
                <div class="form-group" style="grid-column: span 2;">
                    <label>Login Password (Leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="Minimum 6 characters">
                </div>

            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-update">Update</button>
                <a href="{{ route('admin.pharmacists.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

@endsection