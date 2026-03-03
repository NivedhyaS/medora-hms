@extends('layouts.dashboard')

@section('title', 'Prescriptions')
@section('header', 'Recent Prescriptions')

@section('content')
    <div class="card full-width">
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
                    <th style="padding: 10px;">Patient</th>
                    <th style="padding: 10px;">Diagnosis</th>
                    <th style="padding: 10px;">Medicines</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">{{ $prescription->patient->name }}</td>
                        <td style="padding: 10px;">{{ $prescription->diagnosis }}</td>
                        <td style="padding: 10px;">{{ $prescription->medicines }}</td>
                        <td style="padding: 10px;">{{ $prescription->created_at->format('M d, Y') }}</td>
                        <td style="padding: 10px;">
                            <span
                                style="background: {{ $prescription->status == 'dispensed' ? '#d1fae5' : '#fee2e2' }}; color: {{ $prescription->status == 'dispensed' ? '#065f46' : '#991b1b' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($prescription->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection