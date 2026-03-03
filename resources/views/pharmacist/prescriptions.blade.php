@extends('layouts.dashboard')

@section('title', 'Prescriptions List')
@section('header', 'Manage Prescriptions')

@section('content')
    <div class="card full-width">
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
                    <th style="padding: 10px;">Patient</th>
                    <th style="padding: 10px;">Doctor</th>
                    <th style="padding: 10px;">Medicines</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">{{ $prescription->patient?->user?->name ?? 'N/A' }}</td>
                        <td style="padding: 10px;">Dr. {{ $prescription->doctor?->name ?? 'N/A' }}</td>
                        <td style="padding: 10px; max-width: 250px;">{{ $prescription->medicines }}</td>
                        <td style="padding: 10px;">{{ $prescription->created_at->format('M d, Y') }}</td>
                        <td style="padding: 10px;">
                            <span
                                style="background: {{ $prescription->status == 'dispensed' ? '#d1fae5' : '#fee2e2' }}; color: {{ $prescription->status == 'dispensed' ? '#065f46' : '#991b1b' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($prescription->status) }}
                            </span>
                        </td>
                        <td style="padding: 10px;">
                            @if($prescription->status == 'pending')
                                <form action="{{ route('pharmacist.prescriptions.dispense', $prescription->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary"
                                        style="padding: 5px 10px; font-size: 12px; border: none; cursor: pointer;">Dispense</button>
                                </form>
                            @else
                                <span class="text-muted small">Completed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection