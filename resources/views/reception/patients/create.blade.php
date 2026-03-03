@extends('layouts.dashboard')

@section('title', 'Add Patient')
@section('header', 'Add New Patient')

@section('content')
    <style>
        .card {
            max-width: 900px;
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            margin: 0 auto;
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

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #0f172a;
        }

        input,
        select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 1px #2563eb;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-cancel {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            background: #e0e7ff;
        }

        .btn-cancel:hover {
            background: #c7d2fe;
        }
    </style>

    <div class="card">
        <form method="POST" action="{{ route('reception.patients.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full name">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">-- Select --</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" required>
                </div>

                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" required>
                        <option value="">-- Select --</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" required>
                </div>

                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" required placeholder="Full address">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Login Password</label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters">
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">
                    Create Patient
                </button>

                <a href="{{ route('reception.patients') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection