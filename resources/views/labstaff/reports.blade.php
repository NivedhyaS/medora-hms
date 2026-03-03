@extends('layouts.dashboard')

@section('title', 'Lab Reports')
@section('header', 'Lab Reports Management')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('labstaff.reports.upload') }}" class="btn-primary"
            style="padding: 10px 20px; text-decoration: none; border-radius: 6px;">Upload New Report</a>
    </div>

    <div class="card full-width">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
                    <th style="padding: 10px;">Patient</th>
                    <th style="padding: 10px;">Test Name</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">File</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labReports as $report)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">{{ $report->patient->name }}</td>
                        <td style="padding: 10px;">{{ $report->test_name }}</td>
                        <td style="padding: 10px;">{{ $report->test_date }}</td>
                        <td style="padding: 10px;">
                            <span
                                style="background: {{ $report->status == 'completed' ? '#d1fae5' : '#fee2e2' }}; color: {{ $report->status == 'completed' ? '#065f46' : '#991b1b' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td style="padding: 10px;">
                            @if($report->report_file)
                                <a href="{{ asset('storage/' . $report->report_file) }}" target="_blank"
                                    style="color: #2563eb; text-decoration: none;">Download</a>
                            @else
                                <span class="text-muted small">No file</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection