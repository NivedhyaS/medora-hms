@extends('layouts.dashboard')

@section('title', 'Appointments')
@section('header', 'Booked Appointments')


@section('content')

    <div class="card">

        {{-- Header --}}
        <div class="header-actions">
            <p style="color: #64748b; margin: 0;">View and manage all patient appointments</p>

            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book Appointment
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" class="header-actions"
            style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-top: 10px; border: 1px solid #e2e8f0;">

            <div style="display: flex; gap: 12px; flex: 1; min-width: 300px; flex-wrap: wrap;">
                {{-- Patient Search --}}
                <div class="search-wrapper" style="max-width: 200px;">
                    <i class="fas fa-user search-icon"></i>
                    <input list="patients" class="search-input" placeholder="Patient..." id="patientInput"
                        value="{{ optional($users->firstWhere('id', request('user_id')))->name }}" autocomplete="off">
                    <input type="hidden" name="user_id" id="patientId">
                    <datalist id="patients">
                        @foreach($users as $user)
                            <option data-id="{{ $user->id }}" value="{{ $user->name }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Doctor Search --}}
                <div class="search-wrapper" style="max-width: 200px;">
                    <i class="fas fa-user-md search-icon"></i>
                    <input list="doctors" class="search-input" placeholder="Doctor..." id="doctorInput"
                        value="{{ optional($doctors->firstWhere('id', request('doctor_id')))->name }}" autocomplete="off">
                    <input type="hidden" name="doctor_id" id="doctorId" value="{{ request('doctor_id') }}">
                    <datalist id="doctors">
                        @foreach($doctors as $doctor)
                            <option data-id="{{ $doctor->id }}" value="{{ $doctor->name }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="search-wrapper" style="max-width: 160px;">
                    <input type="date" name="date" class="search-input" value="{{ request('date') }}"
                        style="padding-left: 12px;">
                </div>

                <button type="submit" class="btn" style="background: #1e293b; color: white;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="80">Token</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Fee</th>
                        <th>Payment</th>
                        <th width="80" style="text-align: center;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div
                                    style="font-weight: 800; color: #2563eb; background: #eff6ff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #dbeafe;">
                                    #{{ $appointment->token_no }}
                                </div>
                            </td>
                            <td><strong>{{ $appointment->appointment_date }}</strong></td>
                            <td>
                                <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                                    <i class="far fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>{{ $appointment->user->name ?? 'Deleted Patient' }}</td>
                            <td>{{ $appointment->doctor->name ?? 'Deleted Doctor' }}</td>
                            <td style="font-weight: 600;">₹{{ $appointment->consultation_fee }}</td>
                            <td>
                                <span
                                    style="background: {{ $appointment->payment_status == 'paid' ? '#d1fae5' : '#fee2e2' }}; color: {{ $appointment->payment_status == 'paid' ? '#065f46' : '#b91c1c' }}; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700;">
                                    {{ strtoupper($appointment->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: center;">
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                                        onsubmit="return confirm('Delete this appointment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete-icon" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; color: #64748b;">
                                <i class="fas fa-calendar-times"
                                    style="display: block; font-size: 24px; margin-bottom: 10px;"></i>
                                No appointments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== Script ===== --}}
    <script>
        function bindSearch(inputId, hiddenId, listId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            const options = document.getElementById(listId).options;

            input.addEventListener('input', () => {
                let found = false;
                for (let option of options) {
                    if (option.value === input.value) {
                        hidden.value = option.dataset.id;
                        found = true;
                        break;
                    }
                }
                if (!found) hidden.value = '';
            });
        }

        bindSearch('patientInput', 'patientId', 'patients');
        bindSearch('doctorInput', 'doctorId', 'doctors');
    </script>

@endsection