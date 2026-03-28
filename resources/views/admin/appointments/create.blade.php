@extends('layouts.dashboard')

@section('title', 'Book Appointment')
@section('header', 'Book New Appointment')

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

        /* Slots */
        .slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .slot-item {
            padding: 12px;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            background: #fff;
            transition: all 0.2s;
        }

        .slot-item:hover:not(.booked) {
            border-color: #10b981;
            background: #f0fdf4;
            color: #047857;
        }

        .slot-item.active {
            background: #059669;
            color: #fff;
            border-color: #059669;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
        }

        .slot-item.booked {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #e2e8f0;
            text-decoration: line-through;
        }

        .hint-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
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
            <form method="POST" action="{{ route('admin.appointments.store') }}" id="bookForm">
                @csrf

                <div class="form-grid">
                    {{-- Patient --}}
                    <div class="form-group full-width">
                        <label class="form-label">Select Patient</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Choose Patient --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Doctor --}}
                    <div class="form-group full-width">
                        <label class="form-label">Select Doctor</label>
                        <select name="doctor_id" id="doctor" class="form-select" required>
                            <option value="">-- Choose Doctor --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" data-fee="{{ $doctor->consultation_fee }}"
                                    data-days="{{ json_encode($doctor->available_days) }}">
                                    Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Doctor Info Display --}}
                    <div id="doctor_info" class="form-group full-width"
                        style="display: none; background: #f0f7ff; padding: 15px; border-radius: 10px; border: 1px solid #bfdbfe;">
                        <div style="display: flex; justify-content: space-between;">
                            <p style="margin: 0; font-size: 13px;"><strong>Available:</strong> <span id="info_days"></span>
                            </p>
                            <p style="margin: 0; font-size: 13px;"><strong>Fee:</strong> <span
                                    style="color: #059669; font-weight: 700;">₹<span id="info_fee"></span></span></p>
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="form-group full-width">
                        <label class="form-label">Appointment Date</label>
                        <input type="date" name="appointment_date" id="date" class="form-control"
                            min="{{ now()->toDateString() }}" required>
                        <p id="day_error"
                            style="display: none; color: #dc2626; font-size: 12px; margin-top: 5px; font-weight: 600;"></p>
                    </div>

                    {{-- Slots --}}
                    <div class="form-group full-width">
                        <label class="form-label">Available Time Slots</label>
                        <div id="slots" class="slots-grid"></div>
                        <p class="hint-text" id="slotHint">
                            <i class="fas fa-info-circle"></i> Select doctor and date to view available timings
                        </p>
                    </div>

                    {{-- Hidden selected time --}}
                    <input type="hidden" name="appointment_time" id="appointment_time" required>
                </div>

                <div class="btn-actions">
                    <button type="submit" id="submit_btn" class="btn btn-primary" style="padding: 12px 30px;">
                        <i class="fas fa-check-circle"></i> Confirm Booking
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn"
                        style="background: #f1f5f9; color: #475569;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const doctorSelect = document.getElementById('doctor');
        const dateInput = document.getElementById('date');
        const slotsDiv = document.getElementById('slots');
        const timeInput = document.getElementById('appointment_time');
        const hint = document.getElementById('slotHint');
        const doctorInfo = document.getElementById('doctor_info');
        const infoDays = document.getElementById('info_days');
        const infoFee = document.getElementById('info_fee');
        const dayError = document.getElementById('day_error');
        const submitBtn = document.getElementById('submit_btn');

        function updateDoctorInfo() {
            const selected = doctorSelect.options[doctorSelect.selectedIndex];
            if (selected.value) {
                const days = JSON.parse(selected.getAttribute('data-days'));
                infoDays.textContent = (days && days.length) ? days.join(', ') : 'Per schedule';
                infoFee.textContent = selected.getAttribute('data-fee');
                doctorInfo.style.display = 'block';
                loadSlots();
            } else {
                doctorInfo.style.display = 'none';
            }
        }

        function loadSlots() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;

            if (!doctorId || !date) return;

            dayError.style.display = 'none';
            submitBtn.disabled = false;
            slotsDiv.innerHTML = '';
            hint.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking availability...';

            fetch(`/admin/appointments/slots/${doctorId}/${date}`)
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(d => { throw d; });
                    }
                    return res.json();
                })
                .then(data => {
                    slotsDiv.innerHTML = '';

                    if (!data || data.length === 0) {
                        hint.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i> No slots available for the selected date';
                        return;
                    }

                    hint.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> Select a preferred time slot';

                    data.forEach(slot => {
                        const div = document.createElement('div');
                        div.textContent = slot.time;
                        div.classList.add('slot-item');

                        if (slot.booked) {
                            div.classList.add('booked');
                            div.title = 'Already booked';
                        } else {
                            div.onclick = () => {
                                document
                                    .querySelectorAll('.slot-item')
                                    .forEach(s => s.classList.remove('active'));

                                div.classList.add('active');
                                timeInput.value = slot.time;
                            };
                        }

                        slotsDiv.appendChild(div);
                    });
                })
                .catch(err => {
                    slotsDiv.innerHTML = '';
                    const msg = (err && err.error) ? err.error : 'Doctor unavailable on this day';
                    hint.innerHTML = '<i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i> ' + msg;
                    dayError.textContent = msg;
                    dayError.style.display = 'block';
                });
        }

        doctorSelect.addEventListener('change', updateDoctorInfo);
        dateInput.addEventListener('change', loadSlots);

        document.getElementById('bookForm').onsubmit = function (e) {
            if (!timeInput.value) {
                alert('Please select a time slot before confirming.');
                e.preventDefault();
                return false;
            }
        };
    </script>

@endsection