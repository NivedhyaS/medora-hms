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

    <div class="card full-width" style="margin-top: 30px; overflow: visible;">
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

    {{-- Modals moved outside the table and card to prevent layout shifting --}}
    @foreach($pendingTests as $test)
        <div id="upload-form-{{ $test->id }}"
            style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); z-index: 1100; width: 500px; max-height: 90vh; overflow-y: auto; text-align: left; border: 1px solid #e5e7eb;">
            
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #111827;">Upload Lab Report</h3>
                <button type="button" onclick="hideUploadForm({{ $test->id }})" style="background: none; border: none; font-size: 20px; color: #9ca3af; cursor: pointer;">&times;</button>
            </div>

            <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
                Patient: <strong>{{ $test->patient->user->name ?? 'N/A' }}</strong><br>
                Test: <strong>{{ $test->test_name }}</strong>
            </p>

            <form action="{{ route('labstaff.tests.upload', $test->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div style="max-height: 40vh; overflow-y: auto; margin-bottom: 20px; padding-right: 14px; border-bottom: 1px solid #f3f4f6;">
                    @if($test->testType && $test->testType->parameters->count() > 0)
                        <h4 style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px; color: #6b7280;">Test Parameters</h4>
                        @foreach($test->testType->parameters as $parameter)
                            <div style="margin-bottom: 15px;">
                                <label style="display: flex; justify-content: space-between; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #374151;">
                                    <span>{{ $parameter->parameter_name }}</span>
                                    @if($parameter->min_value !== null)
                                        <span style="font-size: 11px; color: #2563eb; background: #eff6ff; padding: 2px 6px; border-radius: 4px;">
                                            {{ $parameter->min_value }} - {{ $parameter->max_value }} {{ $parameter->unit }}
                                        </span>
                                    @endif
                                </label>
                                <input type="{{ $parameter->min_value !== null ? 'number' : 'text' }}" 
                                       step="any"
                                       name="param_{{ $parameter->id }}" 
                                       class="search-input" 
                                       style="position: static; width: 100%; padding: 10px; border: 1px solid #d1d5db;" 
                                       placeholder="Enter value..."
                                       {{ $parameter->is_required ? 'required' : '' }}>
                            </div>
                        @endforeach
                    @else
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px;">Result Summary</label>
                            <textarea name="result" class="search-input" style="height: 120px; padding: 10px; position: static; width: 100%;" placeholder="Describe the test result..." required></textarea>
                        </div>
                    @endif
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px;">Remarks (Optional)</label>
                    <input type="text" name="remarks" class="search-input" style="position: static; width: 100%; padding: 10px; border: 1px solid #d1d5db;" placeholder="Any additional notes...">
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="button" onclick="hideUploadForm({{ $test->id }})" 
                        style="flex: 1; padding: 12px; background: #ffffff; border: 1px solid #d1d5db; color: #4b5563; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel</button>
                    <button type="submit" 
                        style="flex: 2; padding: 12px; background: #6366f1; border: none; color: white; border-radius: 8px; font-weight: 600; cursor: pointer;">Submit Lab Report</button>
                </div>
            </form>
        </div>
        
        <div id="overlay-{{ $test->id }}" onclick="hideUploadForm({{ $test->id }})"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1050;">
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        function showUploadForm(id) {
            document.getElementById('upload-form-' + id).style.display = 'block';
            document.getElementById('overlay-' + id).style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
        function hideUploadForm(id) {
            document.getElementById('upload-form-' + id).style.display = 'none';
            document.getElementById('overlay-' + id).style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection