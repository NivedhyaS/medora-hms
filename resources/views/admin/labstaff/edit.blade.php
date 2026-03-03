@extends('layouts.dashboard')

@section('title', 'Edit Lab Staff')
@section('header', 'Edit Lab Staff')

@section('content')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .card {
            max-width: 900px;
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
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

        input[readonly] {
            background-color: #f3f4f6;
            cursor: not-allowed;
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
        <form method="POST" action="{{ route('admin.labstaff.update', $staff->id) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Lab ID</label>
                    <input type="text" value="{{ $staff->lab_id }}" readonly>
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $staff->name }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $staff->email }}" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ $staff->phone }}" required>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department" required>
                        @foreach(['Clinical Pathology', 'Hematology', 'Biochemistry', 'Microbiology', 'Histopathology', 'Cytology', 'Immunology / Serology', 'Molecular Diagnostics'] as $dept)
                            <option value="{{ $dept }}" {{ $staff->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Login Password (Leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="Minimum 6 characters">
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">
                    Update Staff Profile
                </button>

                <a href="{{ route('admin.labstaff.index') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection