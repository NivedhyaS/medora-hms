<table class="table" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid #f3f4f6;">
            <th style="padding: 12px; background: #f9fafb; color: #4b5563;">Patient Name</th>
            <th style="padding: 12px; background: #f9fafb; color: #4b5563;">Date & Time</th>
            <th style="padding: 12px; background: #f9fafb; color: #4b5563;">Status</th>
            <th style="padding: 12px; background: #f9fafb; color: #4b5563;">Payment</th>
            <th style="padding: 12px; background: #f9fafb; color: #4b5563;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appointment)
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <td style="padding: 12px;">{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                <td style="padding: 12px;">
                    <div>{{ $appointment->appointment_date }}</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ $appointment->appointment_time }}</div>
                </td>
                <td style="padding: 12px;">
                    @php
                        $color = match($appointment->status) {
                            'completed' => '#059669',
                            'cancelled' => '#dc2626',
                            default => '#2563eb'
                        };
                    @endphp
                    <span style="background: {{ $color }}15; color: {{ $color }}; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600;">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </td>
                <td style="padding: 12px;">
                    @if($appointment->payment_status === 'paid')
                        <span style="color: #059669; font-weight: 700; font-size: 13px;">
                            <i class="fas fa-check-circle"></i> Paid
                        </span>
                    @else
                        <span style="color: #dc2626; font-weight: 700; font-size: 13px;">
                            <i class="fas fa-exclamation-circle"></i> Pending
                        </span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route('doctor.patient.profile', $appointment->patient_id) }}" class="btn btn-view" title="View Patient Profile">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding: 40px; text-align: center; color: #9ca3af;">
                    <i class="fas fa-calendar-times" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                    No appointments found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
