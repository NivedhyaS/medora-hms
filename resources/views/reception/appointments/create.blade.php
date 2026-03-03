@extends('layouts.dashboard')

@section('title', 'Book Appointment')
@section('header', 'Book New Appointment')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('reception.appointments.store') }}" method="POST" id="appointmentForm">
            @csrf

            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Select Patient -->
                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Select Patient</label>
                    <select name="patient_id" id="patient_id" class="form-control" required
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="">-- Choose Patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->user->name }} ({{ $patient->patient_id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Doctor -->
                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Select Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="">-- Choose a Doctor --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-fee="{{ $doctor->consultation_fee }}"
                                data-days="{{ json_encode($doctor->available_days) }}">
                                Dr. {{ $doctor->name }} ({{ $doctor->specialization }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Info Display -->
                <div id="doctor_info" class="form-group"
                    style="grid-column: span 2; display: none; background: #f0f7ff; padding: 15px; border-radius: 10px; border: 1px solid #bfdbfe;">
                    <div style="display: flex; justify-content: space-between;">
                        <p style="margin: 0;"><strong>Available:</strong> <span id="info_days"></span></p>
                        <p style="margin: 0;"><strong>Fee:</strong> <span style="color: #059669; font-weight: 700;">₹<span
                                    id="info_fee"></span></span></p>
                    </div>
                </div>

                <!-- Select Date -->
                <div class="form-group">
                    <label>Appointment Date</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" required
                        min="{{ date('Y-m-d') }}">
                    <p id="day_error"
                        style="display: none; color: #dc2626; font-size: 12px; margin-top: 5px; font-weight: 600;"></p>
                </div>

                <!-- Select Time Slot -->
                <div class="form-group">
                    <label>Available Slots</label>
                    <select name="appointment_time" id="appointment_time" class="form-control" required disabled>
                        <option value="">Select doctor and date first</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" id="submit_btn" class="btn btn-primary" style="padding: 12px 30px; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> Confirm Booking
                </button>
                <a href="{{ route('reception.appointments') }}" class="btn"
                    style="padding: 12px 30px; text-decoration: none; color: #64748b;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        const doctorInfo = document.getElementById('doctor_info');
        const infoDays = document.getElementById('info_days');
        const infoFee = document.getElementById('info_fee');
        const dayError = document.getElementById('day_error');
        const submitBtn = document.getElementById('submit_btn');

        let availableDays = [];

        doctorSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                availableDays = JSON.parse(selected.getAttribute('data-days'));
                infoDays.textContent = availableDays.join(', ');
                infoFee.textContent = selected.getAttribute('data-fee');
                doctorInfo.style.display = 'block';
                validateDate();
            } else {
                doctorInfo.style.display = 'none';
            }
        });

        dateInput.addEventListener('change', validateDate);

        function validateDate() {
            if (!dateInput.value || availableDays.length === 0) return;

            const date = new Date(dateInput.value);
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = days[date.getDay()];

            if (!availableDays.includes(dayName)) {
                dayError.textContent = `Doctor is unavailable on ${dayName}s.`;
                dayError.style.display = 'block';
                submitBtn.disabled = true;
                timeSelect.disabled = true;
            } else {
                dayError.style.display = 'none';
                submitBtn.disabled = false;
                fetchSlots();
            }
        }

        function fetchSlots() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;
            if (!doctorId || !date) return;

            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/reception/appointments/slots/${doctorId}/${date}`)
                .then(res => res.json())
                .then(slots => {
                    timeSelect.disabled = false;
                    timeSelect.innerHTML = '<option value="">-- Choose Time --</option>';
                    slots.forEach(slot => {
                        const opt = document.createElement('option');
                        opt.value = slot.time;
                        opt.textContent = slot.time + (slot.booked ? ' (Booked)' : '');
                        if (slot.booked) opt.disabled = true;
                        timeSelect.appendChild(opt);
                    });
                });
        }
    </script>
@endsection