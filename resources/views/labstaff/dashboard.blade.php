@extends('layouts.dashboard')

@section('title', 'Lab Staff Dashboard')
@section('header', 'Lab Staff Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Pending Test Requests</h3>
            <p style="font-size: 24px; font-weight: bold; color: #6366f1;">{{ count($pendingTests) }}</p>
        </div>
    </div>

    <div class="card full-width" style="margin-top: 30px;">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-flask"></i> Pending Test Requests</h3>
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb;">
                    <th style="padding: 12px;">Patient Name</th>
                    <th style="padding: 12px;">Doctor Name</th>
                    <th style="padding: 12px;">Test Name</th>
                    <th style="padding: 12px;">Requested Date</th>
                    <th style="padding: 12px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingTests as $test)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px; font-weight: 500;">{{ $test->patient->user->name ?? 'N/A' }}</td>
                        <td style="padding: 12px;">Dr. {{ $test->doctor->name ?? 'N/A' }}</td>
                        <td style="padding: 12px; font-weight: 600; color: #111827;">{{ $test->test_name }}</td>
                        <td style="padding: 12px; font-size: 13px; color: #6b7280;">
                            {{ $test->requested_at ? $test->requested_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="showUploadForm({{ $test->id }})" class="btn btn-primary"
                                style="height: auto; padding: 8px 15px; font-size: 12px; background: #6366f1;">
                                <i class="fas fa-upload"></i> Upload Report
                            </button>

                            <div id="upload-form-{{ $test->id }}"
                                style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); z-index: 1000; width: 500px; max-height: 95vh; overflow-y: auto; text-align: left; border: 1px solid #e5e7eb;">
                                <h3 style="margin-bottom: 20px; color: #111827;">Upload Lab Report</h3>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">Patient:
                                    <strong>{{ $test->patient->user->name ?? 'N/A' }}</strong><br>Test:
                                    <strong>{{ $test->test_name }}</strong></p>

                                <form action="{{ route('labstaff.tests.upload', $test->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px; padding-right: 10px;">
                                        @if($test->testType && $test->testType->parameters->count() > 0)
                                            <h4 style="font-size: 14px; margin-bottom: 12px; color: #374151; border-bottom: 1px solid #eee; padding-bottom: 5px;">Test Parameters</h4>
                                            @foreach($test->testType->parameters as $parameter)
                                                <div style="margin-bottom: 12px;">
                                                    <label style="display: flex; justify-content: space-between; margin-bottom: 4px; font-weight: 500; font-size: 13px;">
                                                        <span>{{ $parameter->parameter_name }}</span>
                                                        <span style="font-size: 11px; color: #6b7280;">
                                                            @if($parameter->min_value !== null)
                                                                Range: {{ $parameter->min_value }} - {{ $parameter->max_value }} {{ $parameter->unit }}
                                                            @endif
                                                        </span>
                                                    </label>
                                                    <input type="{{ $parameter->min_value !== null ? 'number' : 'text' }}" 
                                                           step="any"
                                                           name="param_{{ $parameter->id }}" 
                                                           class="search-input" 
                                                           style="position: static; width: 100%; padding: 8px;" 
                                                           placeholder="Enter {{ $parameter->parameter_name }}..."
                                                           {{ $parameter->is_required ? 'required' : '' }}>
                                                </div>
                                            @endforeach
                                        @else
                                            <div style="margin-bottom: 15px;">
                                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Result</label>
                                                <textarea name="result" class="search-input" style="height: 100px; padding: 10px; position: static;" required></textarea>
                                            </div>
                                        @endif
                                    </div>

                                    <div style="margin-bottom: 15px;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px;">Remarks (Optional)</label>
                                        <input type="text" name="remarks" class="search-input" style="position: static; width: 100%; padding: 8px;">
                                    </div>

                                    <div style="display: flex; gap: 12px;">
                                        <button type="button" onclick="hideUploadForm({{ $test->id }})" class="btn btn-edit"
                                            style="flex: 1; height: auto; padding: 12px; background: #f3f4f6; color: #4b5563;">Cancel</button>
                                        <button type="submit" class="btn btn-primary"
                                            style="flex: 2; height: auto; padding: 12px; background: #6366f1;">Submit
                                            Report</button>
                                    </div>
                                </form>
                            </div>
                            <div id="overlay-{{ $test->id }}" onclick="hideUploadForm({{ $test->id }})"
                                style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 999;">
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 60px; text-align: center; color: #9ca3af;">
                            <i class="fas fa-flask"
                                style="font-size: 32px; display: block; margin-bottom: 15px; color: #e5e7eb;"></i>
                            No pending test requests.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function showUploadForm(id) {
            document.getElementById('upload-form-' + id).style.display = 'block';
            document.getElementById('overlay-' + id).style.display = 'block';
        }
        function hideUploadForm(id) {
            document.getElementById('upload-form-' + id).style.display = 'none';
            document.getElementById('overlay-' + id).style.display = 'none';
        }
    </script>
@endsection