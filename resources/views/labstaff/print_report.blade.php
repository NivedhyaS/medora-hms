<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $lt->test_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .info-item label {
            font-size: 12px;
            color: #777;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-item p {
            margin: 2px 0;
            font-weight: 600;
        }

        .report-title {
            background: #f8fafc;
            padding: 10px 15px;
            border-left: 4px solid #2563eb;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #f1f5f9;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 13px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .status-badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .status-low {
            color: #dc2626;
        }

        .status-high {
            color: #ea580c;
        }

        .status-normal {
            color: #059669;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        .signature-area {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .sig {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Print Report
        </button>
    </div>

    <div class="header">
        <h1>Medora Hospital Group</h1>
        <p>123 Medical Avenue, Health City | Tel: +1 (234) 567-890</p>
        <p><strong>LABORATORY INVESTIGATION REPORT</strong></p>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <label>Patient Name</label>
            <p>{{ $lt->patient->user->name }}</p>
        </div>
        <div class="info-item">
            <label>Report Date</label>
            <p>{{ now()->format('d M, Y h:i A') }}</p>
        </div>
        <div class="info-item">
            <label>Patient ID</label>
            <p>{{ $lt->patient->patient_id }}</p>
        </div>
        <div class="info-item">
            <label>Requested By</label>
            <p>{{ $lt->doctor ? 'Dr. ' . $lt->doctor->name : 'Lab-Only Request' }}</p>
        </div>
        <div class="info-item">
            <label>Age / Gender</label>
            <p>{{ \Carbon\Carbon::parse($lt->patient->user->dob)->age }}Y / {{ $lt->patient->user->gender }}</p>
        </div>
        <div class="info-item">
            <label>Department</label>
            <p>{{ $lt->department }}</p>
        </div>
    </div>

    <div class="report-title">{{ $lt->test_name }}</div>

    <table>
        <thead>
            <tr>
                <th>Investigation</th>
                <th>Result</th>
                <th>Reference Range</th>
                <th>Units</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lt->parameterValues as $pv)
                <tr>
                    <td style="font-weight: 500;">{{ $pv->parameter->parameter_name }}</td>
                    <td style="font-weight: 700;">{{ $pv->value }}</td>
                    <td>
                        @if($pv->parameter->min_value !== null)
                            {{ $pv->parameter->min_value }} - {{ $pv->parameter->max_value }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="color: #666;">{{ $pv->parameter->unit }}</td>
                    <td>
                        <span class="status-badge status-{{ $pv->status }}">
                            {{ $pv->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($lt->remarks)
        <div style="margin-top: 20px;">
            <strong style="font-size: 13px; text-transform: uppercase;">Pathologist's Remarks:</strong>
            <p style="margin-top: 5px; background: #f9fafb; padding: 15px; border: 1px solid #edf2f7; border-radius: 5px;">
                {{ $lt->remarks }}
            </p>
        </div>
    @endif

    <div class="signature-area">
        <div class="sig">Lab Technician<br><small>(Prepared By)</small></div>
        <div class="sig">Consultant Pathologist<br><small>(Verified By)</small></div>
    </div>

    <div class="footer">
        <p>This is a computer-generated report and does not require a physical signature.</p>
        <p>© {{ date('Y') }} Medora Hospital Group - Confidential Medical Report</p>
    </div>
</body>

</html>