@extends('layouts.dashboard')

@section('title', 'Edit Schedule - ' . $doctor->name)
@section('header', 'Manage Schedule for Dr. ' . $doctor->name)

@section('content')
    <div class="card">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <form action="{{ route('admin.schedules.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Working Hours</th>
                            <th>Slot Duration (mins)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $day)
                            @php
                                $schedule = $doctor->schedules->where('day_of_week', $day)->first();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $day }}</strong>
                                    <input type="hidden" name="schedules[{{ $day }}][day_of_week]" value="{{ $day }}">
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <input type="time" name="schedules[{{ $day }}][start_time]"
                                            value="{{ $schedule ? date('H:i', strtotime($schedule->start_time)) : '' }}"
                                            class="form-control" style="width: 130px;">
                                        <span>to</span>
                                        <input type="time" name="schedules[{{ $day }}][end_time]"
                                            value="{{ $schedule ? date('H:i', strtotime($schedule->end_time)) : '' }}"
                                            class="form-control" style="width: 130px;">
                                    </div>
                                </td>
                                <td>
                                    <select name="schedules[{{ $day }}][slot_duration]" class="form-control"
                                        style="width: 100px;">
                                        @foreach([10, 15, 20, 30, 45, 60] as $duration)
                                            <option value="{{ $duration }}" {{ ($schedule && $schedule->slot_duration == $duration) ? 'selected' : ($duration == 15 ? 'selected' : '') }}>
                                                {{ $duration }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if($schedule)
                                        <span style="color: #059669;"><i class="fas fa-check-circle"></i> Configured</span>
                                    @else
                                        <span style="color: #94a3b8;"><i class="fas fa-times-circle"></i> Not Set</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 600;">
                    <i class="fas fa-save"></i> Save Schedule
                </button>
            </div>
        </form>
    </div>
@endsection

@section('styles')
    <style>
        .form-control {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection