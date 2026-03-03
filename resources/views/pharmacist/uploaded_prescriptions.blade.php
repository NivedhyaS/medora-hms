@extends('layouts.dashboard')

@section('title', 'Uploaded Prescriptions')
@section('header', 'External Uploaded Prescriptions')

@section('content')
    <div class="card full-width">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-file-upload"></i> Review External Prescriptions</h3>
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb;">
                    <th style="padding: 12px;">Patient Details</th>
                    <th style="padding: 12px;">Prescription File</th>
                    <th style="padding: 12px;">Patient Note</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uploadedPrescriptions as $up)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <div style="font-weight: 600;">{{ $up->patient?->user?->name ?? 'N/A' }}</div>
                            <div style="font-size: 11px; color: #6b7280;">ID: {{ $up->patient?->patient_id ?? 'N/A' }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ $up->patient?->user?->mobile ?? 'N/A' }}</div>
                        </td>
                        <td style="padding: 12px;">
                            <a href="{{ Storage::url($up->file_path) }}" target="_blank" class="btn"
                                style="height: auto; padding: 5px 10px; background: #3b82f6; color: white; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-eye"></i> View/Download
                            </a>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">{{ $up->patient_note ?? 'No note' }}</td>
                        <td style="padding: 12px;">
                            @if($up->status == 'pending')
                                <form id="form-{{ $up->id }}"
                                    action="{{ route('pharmacist.uploaded_prescriptions.update', $up->id) }}" method="POST">
                                    @csrf
                                    <select name="status"
                                        style="width: 100%; padding: 5px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 12px;">
                                        <option value="pending" {{ $up->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dispensed" {{ $up->status == 'dispensed' ? 'selected' : '' }}>Dispensed
                                        </option>
                                    </select>
                                </form>
                            @else
                                <span @php
                                    $colors = [
                                        'pending' => ['#fef3c7', '#92400e'],
                                        'dispensed' => ['#d1fae5', '#065f46'],
                                    ];
                                    $color = $colors[$up->status] ?? ['#f3f4f6', '#1f2937'];
                                @endphp
                                    style="background: {{ $color[0] }}; color: {{ $color[1] }}; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600;">
                                    {{ ucfirst($up->status) }}
                                </span>
                            @endif

                            @if($up->status == 'pending')
                                <div style="margin-top: 5px;">
                                    @php
                                        $colors = [
                                            'pending' => ['#fef3c7', '#92400e'],
                                            'dispensed' => ['#d1fae5', '#065f46'],
                                        ];
                                        $color = $colors[$up->status] ?? ['#f3f4f6', '#1f2937'];
                                    @endphp
                                    <span
                                        style="background: {{ $color[0] }}; color: {{ $color[1] }}; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 600;">
                                        Current: {{ ucfirst($up->status) }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            @if($up->status == 'pending')
                                <button type="submit" form="form-{{ $up->id }}" class="btn"
                                    style="height: auto; padding: 8px 15px; background: #059669; color: white; display: inline-flex; align-items: center; gap: 5px; font-size: 12px;">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            @else
                                <span style="color: #64748b; font-size: 12px;">
                                    <i class="fas fa-lock"></i> No actions
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #9ca3af;">No uploaded prescriptions to
                            review.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection