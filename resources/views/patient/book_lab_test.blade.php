@extends('layouts.dashboard')

@section('title', 'Book Lab Test')
@section('header', 'Book Lab Test')

@section('content')
    <div class="header-actions">
        <a href="{{ route('patient.select_service') }}" class="btn btn-edit" style="width: auto; padding: 0 15px;"><i
                class="fas fa-arrow-left"></i> Back to Services</a>
    </div>

    <div class="card" style="max-width: 600px; margin: 30px auto; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div
                style="width: 60px; height: 60px; background: #6366f115; color: #6366f1; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 15px;">
                <i class="fas fa-flask"></i>
            </div>
            <h2 style="font-size: 24px; color: #111827; margin-bottom: 8px;">Request a Lab Test</h2>
            <p style="color: #6b7280; font-size: 14px;">Select the test you'd like to perform. Our lab staff will process
                your request.</p>
        </div>

        <form action="{{ route('patient.lab.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Select
                    Department</label>
                <select id="department-select" class="search-input"
                    style="position: static; width: 100%; border: 1px solid #e2e8f0; padding: 12px;" required
                    onchange="updateTestTypes()">
                    <option value="">-- Choose Department --</option>
                    @foreach($testTypes as $dept => $types)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Select
                    Lab Test</label>
                <select id="test-type-select" name="test_type_id" class="search-input"
                    style="position: static; width: 100%; border: 1px solid #e2e8f0; padding: 12px;" required>
                    <option value="">-- First choose a department --</option>
                </select>
            </div>

            <div style="margin-bottom: 30px;">
                <label
                    style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">Additional
                    Instructions (Optional)</label>
                <textarea name="instructions" class="search-input"
                    style="position: static; width: 100%; height: 100px; border: 1px solid #e2e8f0; padding: 12px;"
                    placeholder="Any specific requirements or notes for the lab staff..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; padding: 14px; background: #6366f1; font-size: 16px; font-weight: 600;">
                <i class="fas fa-paper-plane" style="margin-right: 8px;"></i> Submit Test Request
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const testData = @json($testTypes);

        function updateTestTypes() {
            const dept = document.getElementById('department-select').value;
            const testSelect = document.getElementById('test-type-select');

            testSelect.innerHTML = '<option value="">-- Select Test --</option>';

            if (dept && testData[dept]) {
                testData[dept].forEach(test => {
                    const option = document.createElement('option');
                    option.value = test.id;
                    option.textContent = test.test_name;
                    testSelect.appendChild(option);
                });
            } else {
                testSelect.innerHTML = '<option value="">-- First choose a department --</option>';
            }
        }
    </script>
@endsection