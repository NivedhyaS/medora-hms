@extends('layouts.dashboard')

@section('title', 'Patient Profile')
@section('header', 'Patient Profile')

@section('content')
    <div class="header-actions">
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-edit" style="width: auto; padding: 0 15px;"><i
                class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <!-- SECTION A – Patient Basic Info -->
    <div class="card full-width" style="margin-bottom: 30px;">
        <h3 style="border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 20px;"><i
                class="fas fa-user-circle"></i> Basic Information</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <label
                    style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Name</label>
                <p style="font-size: 16px; font-weight: 500;">{{ $patient->user->name }}</p>
            </div>
            <div>
                <label
                    style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Age</label>
                <p style="font-size: 16px; font-weight: 500;">
                    {{ $patient->user->dob ? \Carbon\Carbon::parse($patient->user->dob)->age : 'N/A' }} years
                </p>
            </div>
            <div>
                <label
                    style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Gender</label>
                <p style="font-size: 16px; font-weight: 500;">{{ ucfirst($patient->user->gender ?? 'N/A') }}</p>
            </div>
            <div>
                <label
                    style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Phone</label>
                <p style="font-size: 16px; font-weight: 500;">{{ $patient->user->mobile ?? 'N/A' }}</p>
            </div>
            <div>
                <label
                    style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Blood
                    Group</label>
                <p style="font-size: 16px; font-weight: 500;"><span
                        style="background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 4px;">{{ $patient->user->blood_group ?? 'N/A' }}</span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Consultation Form -->
        <div class="card">
            <h3 style="border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 20px;"><i
                    class="fas fa-notes-medical"></i> After Consultation</h3>
            @php
                $activeAppointment = $patient->appointments()->where('status', 'pending')->where('doctor_id', auth()->user()->doctor->id)->latest()->first();
            @endphp

            @if($activeAppointment)
                <form action="{{ route('doctor.appointments.complete', ['id' => $activeAppointment->id]) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Diagnosis</label>
                        <textarea name="diagnosis" class="search-input" style="height: 100px; padding: 10px; position: static;"
                            required></textarea>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Clinical Notes</label>
                        <textarea name="clinical_notes" class="search-input"
                            style="height: 100px; padding: 10px; position: static;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: auto; padding: 12px;"><i
                            class="fas fa-check-circle"></i> Mark Appointment as Completed</button>
                </form>
            @else
                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center; color: #6b7280;">
                    No pending appointment found for this patient today.
                </div>
            @endif

            <hr style="margin: 30px 0; border: none; border-top: 1px solid #f3f4f6;">

            <h3><i class="fas fa-file-prescription"></i> Quick Actions</h3>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <button onclick="toggleForm('prescription-form')" class="btn btn-primary-lg"
                    style="flex: 1; font-size: 14px; height: auto;"><i class="fas fa-pills"></i> Add Prescription</button>
                <button onclick="toggleForm('lab-form')" class="btn btn-primary-lg"
                    style="flex: 1; font-size: 14px; background: #6366f1; height: auto;"><i class="fas fa-flask"></i>
                    Request Lab Test</button>
            </div>

            <!-- Prescription Form (Hidden) -->
            <div id="prescription-form"
                style="display: none; margin-top: 20px; background: #f9fafb; padding: 20px; border-radius: 8px;">
                <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 13px;">Diagnosis</label>
                        <input type="text" name="diagnosis" class="search-input" style="position: static;" required>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label
                            style="display: block; margin-bottom: 5px; font-size: 13px; font-weight: 600;">Medicines</label>
                        <div style="position: relative; margin-bottom: 10px;">
                            <input type="text" id="medicine-search" class="search-input" style="position: static;"
                                placeholder="Search medicine..." autocomplete="off">
                            <div id="medicine-results"
                                style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #e2e8f0; border-radius: 4px; z-index: 1000; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                            </div>
                        </div>

                        <div id="selected-medicines-list"
                            style="margin-bottom: 10px; display: flex; flex-direction: column; gap: 8px;">
                            <!-- Selected medicines will appear here -->
                        </div>

                        <textarea name="medicines" id="medicines-hidden" style="display: none;" required></textarea>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 13px;">Dosage</label>
                        <input type="text" name="dosage" class="search-input" style="position: static;"
                            placeholder="1-0-1, After food" required>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 13px;">Instructions</label>
                        <textarea name="instructions" class="search-input"
                            style="height: 60px; padding: 10px; position: static;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: auto; padding: 10px;">Save
                        Prescription</button>
                </form>
            </div>

            <!-- Lab Form (Hidden) -->
            <div id="lab-form"
                style="display: none; margin-top: 20px; background: #f9fafb; padding: 20px; border-radius: 8px;">
                <form action="{{ route('doctor.lab_tests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    @if($activeAppointment)
                        <input type="hidden" name="appointment_id" value="{{ $activeAppointment->id }}">
                    @endif

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;">Department</label>
                        <select id="department-select" class="search-input" style="position: static; padding: 0 10px;"
                            required onchange="updateTestTypes()">
                            <option value="">Select Department</option>
                            @foreach($testTypes as $dept => $types)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;">Test
                            Type</label>
                        <select id="test-type-select" name="test_type_id" class="search-input"
                            style="position: static; padding: 0 10px;" required>
                            <option value="">Select Test</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 13px;">Instructions</label>
                        <textarea name="instructions" class="search-input"
                            style="height: 60px; padding: 10px; position: static;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; height: auto; padding: 10px; background: #6366f1;">Request Test</button>
                </form>
            </div>
        </div>

        <!-- SECTION B – Visit History -->
        <div class="card" style="display: flex; flex-direction: column;">
            <h3 style="border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 20px;"><i
                    class="fas fa-history"></i> Visit History</h3>
            <div style="overflow-y: auto; flex: 1; max-height: 600px;">
                @forelse($visitHistory as $visit)
                    <div style="margin-bottom: 15px; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-weight: 700; color: #374151;">{{ $visit->appointment_date }}</span>
                            <span
                                style="background: {{ $visit->status == 'completed' ? '#d1fae5' : '#dbeafe' }}; color: {{ $visit->status == 'completed' ? '#065f46' : '#1e40af' }}; padding: 2px 8px; border-radius: 4px; font-size: 11px;">{{ ucfirst($visit->status) }}</span>
                        </div>
                        <div style="font-size: 14px;"><strong>Diagnosis:</strong> {{ $visit->diagnosis ?? 'N/A' }}</div>
                        <div style="font-size: 14px; color: #6b7280; margin-top: 5px;"><strong>Notes:</strong>
                            {{ $visit->clinical_notes ?? 'None' }}</div>
                    </div>
                @empty
                    <div style="text-align: center; color: #9ca3af; padding: 20px;">No visit history found.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
        <!-- SECTION C – Past Prescriptions -->
        <div class="card">
            <h3 style="border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 20px;"><i
                    class="fas fa-file-prescription"></i> Past Prescriptions</h3>
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medicines</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $p)
                        <tr>
                            <td>{{ $p->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $p->medicines }}</div>
                                <div style="font-size: 11px; color: #6b7280;">{{ $p->dosage }}</div>
                            </td>
                            <td>
                                <span
                                    style="color: {{ $p->status == 'dispensed' ? '#059669' : '#d97706' }}; font-weight: 600;">{{ ucfirst($p->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #9ca3af; padding: 20px;">No prescriptions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- SECTION D – Lab Reports -->
        <div class="card">
            <h3 style="border-bottom: 2px solid #f3f4f6; padding-bottom: 10px; margin-bottom: 20px;"><i
                    class="fas fa-vial"></i> Lab Reports</h3>
            <div style="overflow-y: auto; max-height: 600px;">
                @forelse($labTests as $lt)
                    <div style="margin-bottom: 25px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 15px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <h4 style="margin: 0; color: #111827;">{{ $lt->test_name }}</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: #6b7280;">
                                    Requested: {{ $lt->requested_at ? $lt->requested_at->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <span
                                    style="background: {{ $lt->status == 'completed' ? '#d1fae5' : '#fef3c7' }}; color: {{ $lt->status == 'completed' ? '#065f46' : '#92400e' }}; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600;">
                                    {{ ucfirst($lt->status) }}
                                </span>
                            </div>
                        </div>

                        @if($lt->status == 'completed' && $lt->parameterValues->count() > 0)
                            @include('partials._lab_report_table', ['lt' => $lt])
                        @elseif($lt->status == 'completed' && $lt->result)
                            <div style="background: #f9fafb; padding: 12px; border-radius: 8px; font-size: 14px; margin-top: 10px;">
                                <strong>Result:</strong> {{ $lt->result }}
                            </div>
                        @else
                            <div
                                style="text-align: center; padding: 20px; background: #f9fafb; border-radius: 8px; color: #9ca3af; font-size: 13px; margin-top: 10px;">
                                Result description will appear here once processed by lab staff.
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; color: #9ca3af; padding: 20px;">No lab tests found.</div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const testData = @json($testTypes);

        function toggleForm(id) {
            var form = document.getElementById(id);
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

        function updateTestTypes() {
            const dept = document.getElementById('department-select').value;
            const testSelect = document.getElementById('test-type-select');

            testSelect.innerHTML = '<option value="">Select Test</option>';

            if (dept && testData[dept]) {
                testData[dept].forEach(test => {
                    const option = document.createElement('option');
                    option.value = test.id;
                    option.textContent = test.test_name;
                    testSelect.appendChild(option);
                });
            }
        }

        // Medicine Search Logic
        const medicineSearch = document.getElementById('medicine-search');
        const medicineResults = document.getElementById('medicine-results');
        const selectedList = document.getElementById('selected-medicines-list');
        const medicinesHidden = document.getElementById('medicines-hidden');
        let selectedMedicines = [];

        medicineSearch.addEventListener('input', function () {
            const term = this.value;
            if (term.length < 2) {
                medicineResults.style.display = 'none';
                return;
            }

            fetch(`{{ route('doctor.medicines.search') }}?term=${term}`)
                .then(response => response.json())
                .then(data => {
                    medicineResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(med => {
                            const div = document.createElement('div');
                            div.style.padding = '10px';
                            div.style.cursor = 'pointer';
                            div.style.borderBottom = '1px solid #f3f4f6';
                            div.innerHTML = `<strong>${med.name}</strong> <span style="font-size: 11px; color: #6b7280;">(Stock: ${med.quantity})</span>`;
                            div.onmouseover = () => div.style.background = '#f9fafb';
                            div.onmouseout = () => div.style.background = 'white';
                            div.onclick = () => addMedicine(med);
                            medicineResults.appendChild(div);
                        });
                        medicineResults.style.display = 'block';
                    } else {
                        medicineResults.style.display = 'none';
                    }
                });
        });

        function addMedicine(med) {
            if (selectedMedicines.some(m => m.id === med.id)) {
                alert('Medicine already added');
                return;
            }

            selectedMedicines.push({ ...med, qty: 1 });
            renderMedicines();
            medicineSearch.value = '';
            medicineResults.style.display = 'none';
        }

        function removeMedicine(id) {
            selectedMedicines = selectedMedicines.filter(m => m.id !== id);
            renderMedicines();
        }

        function updateQty(id, qty) {
            const med = selectedMedicines.find(m => m.id === id);
            if (med) {
                med.qty = parseInt(qty) || 1;
                updateHiddenInput();
            }
        }

        function renderMedicines() {
            selectedList.innerHTML = '';
            selectedMedicines.forEach(med => {
                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.alignItems = 'center';
                div.style.gap = '10px';
                div.style.background = 'white';
                div.style.padding = '8px 12px';
                div.style.borderRadius = '6px';
                div.style.border = '1px solid #e2e8f0';
                div.innerHTML = `
                            <div style="flex: 1; font-weight: 500;">${med.name}</div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="font-size: 12px; color: #64748b;">Qty:</span>
                                <input type="number" value="${med.qty}" min="1" onchange="updateQty(${med.id}, this.value)" 
                                    style="width: 50px; padding: 4px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;">
                            </div>
                            <button type="button" onclick="removeMedicine(${med.id})" 
                                style="color: #ef4444; background: none; border: none; cursor: pointer; padding: 5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                selectedList.appendChild(div);
            });
            updateHiddenInput();
        }

        function updateHiddenInput() {
            const text = selectedMedicines.map(m => `${m.name} (${m.qty})`).join(', ');
            medicinesHidden.value = text;
        }

        // Close results when clicking outside
        document.addEventListener('click', function (e) {
            if (!medicineSearch.contains(e.target) && !medicineResults.contains(e.target)) {
                medicineResults.style.display = 'none';
            }
        });
    </script>
@endsection