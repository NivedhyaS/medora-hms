@extends('layouts.dashboard')

@section('title', 'Edit Doctor')
@section('header', 'Edit Doctor')

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

        .form-group.full-width {
            grid-column: span 2;
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
            width: 100%;
            background: #fff;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 1px #2563eb;
        }

        .time-selection {
            display: flex;
            gap: 12px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .time-block {
            flex: 1;
        }

        .time-input-group {
            display: flex;
            gap: 8px;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 12px;
        }

        .btn-primary {
            background: #22c55e;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .btn-cancel {
            color: #4b5563;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            background: #f3f4f6;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .section-title {
            grid-column: span 2;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin: 10px 0 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #2563eb;
        }

        /* Improved Schedule Styling */
        .schedule-container {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .schedule-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 12px 15px;
            text-align: left;
        }
        
        .schedule-row {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
        }
        
        .schedule-row:last-child {
            border-bottom: none;
        }
        
        .schedule-row.inactive {
            background: #f8fafc;
            opacity: 0.6;
        }
        
        .schedule-row:hover:not(.inactive) {
            background: #f0f9ff;
        }
        
        .day-cell {
            padding: 12px 15px;
            width: 160px;
        }
        
        .day-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0;
            line-height: 1;
        }
        
        .time-cell {
            padding: 12px 15px;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: fit-content;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 10px;
            color: #94a3b8;
            font-size: 12px;
            pointer-events: none;
        }
        
        .schedule-input {
            padding: 8px 10px 8px 30px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            width: 130px !important;
            transition: all 0.2s;
            background: #fff;
        }
        
        .schedule-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none;
        }
        
        .duration-select {
            padding: 8px 10px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            width: 90px !important;
            background: #fff;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #2563eb;
            margin: 0;
        }

        .copy-all-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: auto;
        }

        .copy-all-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
    </style>

    <div class="card">
        <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="section-title">
                    <i class="fas fa-user-md"></i> Update Basic Information
                </div>

                <div class="form-group">
                    <label>Doctor Name</label>
                    <input type="text" name="name" value="{{ $doctor->name }}" required>
                </div>

                <div class="form-group">
                    <label>Specialization</label>
                    <select name="specialization_id" required>
                        <option value="">-- Select Specialization --</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->id }}" {{ $doctor->specialization_id == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ $doctor->phone }}" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ $doctor->email }}" required>
                </div>

                <div class="form-group full-width">
                    <label>Consultation Fee (₹)</label>
                    <input type="number" name="consultation_fee" value="{{ $doctor->consultation_fee }}" required min="0" step="0.01">
                </div>

                <div class="section-title">
                    <i class="fas fa-lock"></i> Account Security
                </div>

                <div class="form-group full-width">
                    <label>New Login Password (Leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="Minimum 6 characters">
                </div>

                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i> Weekly Schedule (Select days and set times)
                </div>

                <div class="form-group full-width">
                    <div class="schedule-container">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>From Time</th>
                                    <th>To Time</th>
                                    <th>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            Slot (min)
                                            <button type="button" class="copy-all-btn" onclick="copyFirstToAll()" title="Copy first day schedule to all selected days">
                                                <i class="fas fa-copy"></i> Copy to All
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $currentDays = $doctor->available_days ?? []; 
                                    $doctorSchedules = $doctor->schedules->keyBy('day_of_week');
                                @endphp
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    @php 
                                        $sched = $doctorSchedules->get($day); 
                                        $startTime = $sched ? date('H:i', strtotime($sched->start_time)) : '';
                                        $endTime = $sched ? date('H:i', strtotime($sched->end_time)) : '';
                                        $duration = $sched ? $sched->slot_duration : 15;
                                        
                                        if (!$sched && in_array($day, $currentDays)) {
                                            $startTime = date('H:i', strtotime($doctor->availability_start));
                                            $endTime = date('H:i', strtotime($doctor->availability_end));
                                        }

                                        $isChecked = in_array($day, $currentDays);
                                    @endphp
                                    <tr class="schedule-row {{ $isChecked ? '' : 'inactive' }}" id="row_{{ $day }}">
                                        <td class="day-cell">
                                            <label class="day-label">
                                                <input type="checkbox" name="available_days[]" value="{{ $day }}" 
                                                    id="day_{{ $day }}" class="day-check custom-checkbox"
                                                    onchange="toggleRow('{{ $day }}')"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                {{ $day }}
                                            </label>
                                        </td>
                                        <td class="time-cell">
                                            <div class="input-wrapper">
                                                <i class="fas fa-clock"></i>
                                                <input type="time" name="schedules[{{ $day }}][start_time]" 
                                                    value="{{ old('schedules.'.$day.'.start_time', $startTime) }}"
                                                    class="schedule-input" {{ $isChecked ? '' : 'disabled' }}>
                                            </div>
                                        </td>
                                        <td class="time-cell">
                                            <div class="input-wrapper">
                                                <i class="fas fa-clock"></i>
                                                <input type="time" name="schedules[{{ $day }}][end_time]" 
                                                    value="{{ old('schedules.'.$day.'.end_time', $endTime) }}"
                                                    class="schedule-input" {{ $isChecked ? '' : 'disabled' }}>
                                            </div>
                                        </td>
                                        <td class="time-cell">
                                            <select name="schedules[{{ $day }}][slot_duration]" class="duration-select" {{ $isChecked ? '' : 'disabled' }}>
                                                @foreach([10, 15, 20, 30, 45, 60] as $dur)
                                                    <option value="{{ $dur }}" {{ old('schedules.'.$day.'.slot_duration', $duration) == $dur ? 'selected' : '' }}>{{ $dur }} min</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('available_days')
                        <small style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <script>
                    function toggleRow(day) {
                        const checkbox = document.getElementById('day_' + day);
                        const row = document.getElementById('row_' + day);
                        const inputs = row.querySelectorAll('input[type="time"], select');
                        
                        if (checkbox.checked) {
                            row.classList.remove('inactive');
                            inputs.forEach(input => input.disabled = false);
                        } else {
                            row.classList.add('inactive');
                            inputs.forEach(input => input.disabled = true);
                        }
                    }

                    function copyFirstToAll() {
                        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        const firstDay = days[0];
                        const start = document.querySelector(`input[name="schedules[${firstDay}][start_time]"]`).value;
                        const end = document.querySelector(`input[name="schedules[${firstDay}][end_time]"]`).value;
                        const duration = document.querySelector(`select[name="schedules[${firstDay}][slot_duration]"]`).value;

                        if (!start || !end) {
                            alert('Please set the time for Monday first.');
                            return;
                        }

                        days.slice(1).forEach(day => {
                            const checkbox = document.getElementById('day_' + day);
                            if (checkbox.checked) {
                                document.querySelector(`input[name="schedules[${day}][start_time]"]`).value = start;
                                document.querySelector(`input[name="schedules[${day}][end_time]"]`).value = end;
                                document.querySelector(`select[name="schedules[${day}][slot_duration]"]`).value = duration;
                            }
                        });
                    }
                </script>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection