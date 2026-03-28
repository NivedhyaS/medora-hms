@extends('layouts.dashboard')

@section('title', 'Doctor Schedules')
@section('header', 'Doctor Schedule Management')

@section('content')
    <div class="card">
        <div class="header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="doctorSearch" class="search-input"
                    placeholder="Search doctors by name or specialization..."
                    onkeyup="filterTable('doctorSearch', 'scheduleTable')">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Active Schedules</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody id="scheduleTable">
                @foreach($doctors as $doctor)
                    <tr>
                        <td><strong>{{ $doctor->name }}</strong></td>
                        <td>
                            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                                {{ $doctor->specialization->name ?? $doctor->specialization }}
                            </span>
                        </td>
                        <td>
                            @if($doctor->schedules->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($doctor->schedules as $schedule)
                                        <span
                                            style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px;">
                                            {{ substr($schedule->day_of_week, 0, 3) }}:
                                            {{ date('h:i A', strtotime($schedule->start_time)) }} -
                                            {{ date('h:i A', strtotime($schedule->end_time)) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">No specific schedule set</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.schedules.edit', $doctor->id) }}"
                                    class="btn"
                                    style="background: #eff6ff; color: #2563eb; padding: 8px 16px; white-space: nowrap;"
                                    title="Manage Schedule">
                                    <i class="fas fa-calendar-alt"></i> Manage Schedule
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function filterTable(inputId, tableId) {
            let input = document.getElementById(inputId);
            let filter = input.value.toLowerCase();
            let tbody = document.getElementById(tableId);
            let tr = tbody.getElementsByTagName("tr");

            for (let i = 0; i < tr.length; i++) {
                let text = tr[i].innerText.toLowerCase();
                tr[i].style.display = text.includes(filter) ? "" : "none";
            }
        }
    </script>
@endsection