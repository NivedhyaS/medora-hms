<div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-top: 15px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; text-align: left;">
                <th style="padding: 12px; font-weight: 600; color: #374151;">Parameter</th>
                <th style="padding: 12px; font-weight: 600; color: #374151;">Value</th>
                <th style="padding: 12px; font-weight: 600; color: #374151;">Normal Range</th>
                <th style="padding: 12px; font-weight: 600; color: #374151; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lt->parameterValues as $pv)
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 12px; font-weight: 500;">{{ $pv->parameter->parameter_name }}</td>
                    <td style="padding: 12px;">{{ $pv->value }} <span
                            style="font-size: 11px; color: #6b7280;">{{ $pv->parameter->unit }}</span></td>
                    <td style="padding: 12px; color: #6b7280;">
                        @if($pv->parameter->min_value !== null)
                            {{ $pv->parameter->min_value }} - {{ $pv->parameter->max_value }} {{ $pv->parameter->unit }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @php
                            $colors = [
                                'low' => ['bg' => '#fee2e2', 'text' => '#dc2626'],
                                'normal' => ['bg' => '#d1fae5', 'text' => '#059669'],
                                'high' => ['bg' => '#ffedd5', 'text' => '#ea580c']
                            ];
                            $color = $colors[$pv->status] ?? $colors['normal'];
                        @endphp
                        <span
                            style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            {{ $pv->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if($lt->remarks)
        <div style="padding: 15px; background: #fdfdfd; border-top: 1px solid #eee;">
            <strong style="display: block; font-size: 12px; color: #6b7280; text-transform: uppercase;">Remarks:</strong>
            <p style="margin-top: 5px; color: #374151;">{{ $lt->remarks }}</p>
        </div>
    @endif
</div>