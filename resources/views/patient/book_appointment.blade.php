@extends('layouts.dashboard')

@section('title', 'Book Appointment')
@section('header', 'Book New Appointment')

@section('content')
    <div class="card" style="max-width: 900px; margin: 0 auto;">
        <form action="{{ route('patient.appointments.store') }}" method="POST" id="appointmentForm">
            @csrf

            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Select Specialization -->
                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Step 1: Select
                        Specialization</label>
                    <select id="specialization" class="form-control"
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="">-- Choose Specialization --</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Doctor -->
                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Step 2: Select Doctor</label>
                    <select name="doctor_id" id="doctor_id" class="form-control" required disabled
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="">-- Select Specialization First --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-specialization-id="{{ $doctor->specialization_id }}"
                                data-fee="{{ $doctor->consultation_fee }}"
                                data-days="{{ json_encode($doctor->available_days) }}"
                                data-schedules="{{ json_encode($doctor->schedules) }}" style="display: none;">
                                Dr. {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Info Display -->
                <div id="doctor_info" class="form-group"
                    style="grid-column: span 2; display: none; background: #f0f7ff; padding: 15px; border-radius: 10px; border: 1px solid #bfdbfe;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="margin: 0; font-size: 14px; color: #1e40af;"><strong>Available Days:</strong> <span
                                    id="info_days" style="font-weight: 700;"></span></p>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 16px; color: #111827;"><strong>Consultation Fee:</strong> <span
                                    style="color: #059669; font-weight: 800;">₹<span id="info_fee"></span></span></p>
                        </div>
                    </div>
                </div>

                <!-- Select Date -->
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Step 3: Appointment Date</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" required
                        min="{{ date('Y-m-d') }}"
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                    <p id="day_error"
                        style="display: none; color: #dc2626; font-size: 12px; margin-top: 5px; font-weight: 600;"></p>
                </div>

                <!-- Select Time Slot -->
                <div class="form-group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Step 4: Available Slots</label>
                    <select name="appointment_time" id="appointment_time" class="form-control" required disabled
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="">Select doctor and date first</option>
                    </select>
                    <p id="slotLoading" style="display: none; font-size: 12px; color: #6b7280; margin-top: 5px;">
                        <i class="fas fa-spinner fa-spin"></i> Checking availability...
                    </p>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" id="submit_btn" class="btn btn-primary" style="padding: 12px 30px; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> Confirm Booking
                </button>
                <a href="{{ route('patient.appointments') }}" class="btn"
                    style="padding: 12px 30px; background: #f3f4f6; color: #374151; font-weight: 600; text-decoration: none; border-radius: 8px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        const doctorSelect = document.getElementById('doctor_id');
        const specSelect = document.getElementById('specialization');
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        const loadingText = document.getElementById('slotLoading');
        const doctorInfo = document.getElementById('doctor_info');
        const infoDays = document.getElementById('info_days');
        const infoFee = document.getElementById('info_fee');
        const dayError = document.getElementById('day_error');
        const submitBtn = document.getElementById('submit_btn');

        let availableDays = [];
        let doctorSchedules = [];

        specSelect.addEventListener('change', function () {
            const specId = this.value;
            doctorSelect.innerHTML = '<option value="">-- Choose a Doctor --</option>';

            if (specId) {
                doctorSelect.disabled = false;
                Array.from(doctorSelect.options).forEach(option => {
                    // This is tricky because I'm iterating over the same select I'm clearing...
                    // Let's use the hidden options logic instead if I didn't clear it.
                });

                // Better approach:
                const options = @json($doctors);
                options.forEach(doc => {
                    if (doc.specialization_id == specId) {
                        const opt = document.createElement('option');
                        opt.value = doc.id;
                        opt.textContent = `Dr. ${doc.name}`;
                        opt.setAttribute('data-fee', doc.consultation_fee);
                        opt.setAttribute('data-days', JSON.stringify(doc.available_days));
                        opt.setAttribute('data-schedules', JSON.stringify(doc.schedules));
                        doctorSelect.appendChild(opt);
                    }
                });
                if (doctorSelect.options.length === 1) {
                    doctorSelect.innerHTML = '<option value="">-- No doctors found --</option>';
                    doctorSelect.disabled = true;
                }
            } else {
                doctorSelect.disabled = true;
                doctorSelect.innerHTML = '<option value="">-- Select Specialization First --</option>';
            }
            updateDoctorInfo();
        });

        function updateDoctorInfo() {
            const selected = doctorSelect.options[doctorSelect.selectedIndex];
            if (selected && selected.value) {
                availableDays = JSON.parse(selected.getAttribute('data-days') || '[]');
                doctorSchedules = JSON.parse(selected.getAttribute('data-schedules') || '[]');

                // If there are specific schedules, use those for available days display
                if (doctorSchedules.length > 0) {
                    availableDays = doctorSchedules.map(s => s.day_of_week);
                }

                infoDays.textContent = availableDays.join(', ');
                infoFee.textContent = selected.getAttribute('data-fee');
                doctorInfo.style.display = 'block';
                validateDate();
            } else {
                doctorInfo.style.display = 'none';
                availableDays = [];
                doctorSchedules = [];
            }
            fetchSlots();
        }

        function validateDate() {
            if (!dateInput.value || availableDays.length === 0) return true;

            const date = new Date(dateInput.value);
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = days[date.getDay()];

            if (!availableDays.includes(dayName)) {
                dayError.textContent = `Doctor is not available on ${dayName}s. Please pick another day.`;
                dayError.style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                timeSelect.disabled = true;
                return false;
            } else {
                dayError.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                return true;
            }
        }

        function fetchSlots() {
            const doctorId = doctorSelect.value;
            const date = dateInput.value;

            if (doctorId && date && validateDate()) {
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">Loading slots...</option>';
                loadingText.style.display = 'block';

                fetch(`/patient/appointments/slots/${doctorId}/${date}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Not available');
                        return response.json();
                    })
                    .then(slots => {
                        loadingText.style.display = 'none';
                        timeSelect.disabled = false;
                        timeSelect.innerHTML = '<option value="">-- Choose a Time --</option>';

                        if (slots.length === 0) {
                            timeSelect.innerHTML = '<option value="">No slots available for this day</option>';
                            timeSelect.disabled = true;
                            return;
                        }

                        slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.time;
                            option.textContent = slot.time + (slot.booked ? ' (Booked)' : '');
                            if (slot.booked) option.disabled = true;
                            timeSelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        loadingText.style.display = 'none';
                        timeSelect.innerHTML = '<option value="">Doctor unavailable on this day</option>';
                    });
            }
        }

        doctorSelect.addEventListener('change', updateDoctorInfo);
        dateInput.addEventListener('change', () => {
            if (validateDate()) {
                fetchSlots();
            }
        });

        // Pre-selection logic
        window.addEventListener('DOMContentLoaded', (event) => {
            const selectedDocId = "{{ $selected_doctor_id ?? '' }}";
            if (selectedDocId) {
                const doctors = @json($doctors);
                const selectedDoc = doctors.find(d => d.id == selectedDocId);

                if (selectedDoc) {
                    // Set specialization
                    specSelect.value = selectedDoc.specialization_id;

                    // Trigger specialization change logic to populate doctor list
                    const event = new Event('change');
                    specSelect.dispatchEvent(event);

                    // Set doctor
                    doctorSelect.value = selectedDocId;
                    updateDoctorInfo();
                }
            }
        });
    </script>
@endsection