@extends('layouts.dashboard')

@section('title', 'Receipt')
@section('header', 'Payment Receipt')

@section('content')
    <div
        style="max-width: 600px; margin: 0 auto; background: white; padding: 40px; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
            <h2 style="margin: 0; color: #1e293b; letter-spacing: 1px;">MEDORA HOSPITAL</h2>
            <p style="margin: 5px 0; color: #64748b; font-size: 14px;">123 Healthcare Blvd, Medical City</p>
            <p style="margin: 0; color: #64748b; font-size: 14px;">Phone: +91 9988776655</p>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase;">Patient Details</p>
                <h4 style="margin: 5px 0;">{{ $billing->patient->user->name ?? 'Deleted Patient' }}</h4>
                <p style="margin: 0; font-size: 13px; color: #4b5563;">ID: {{ $billing->patient->patient_id }}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase;">Receipt Info</p>
                <p style="margin: 5px 0; font-weight: 600;">#REC-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p style="margin: 0; font-size: 13px; color: #4b5563;">Date: {{ $billing->created_at->format('d M, Y') }}
                </p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9;">
                    <th style="text-align: left; padding: 10px 0; color: #64748b; font-size: 13px;">Description</th>
                    <th style="text-align: right; padding: 10px 0; color: #64748b; font-size: 13px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 15px 0; border-bottom: 1px solid #f8fafc;">
                        <div style="font-weight: 600;">Consultation Fee</div>
                        <div style="font-size: 12px; color: #64748b;">Dr.
                            {{ $billing->appointment->doctor->name ?? 'Deleted Doctor' }}</div>
                    </td>
                    <td style="text-align: right; padding: 15px 0; border-bottom: 1px solid #f8fafc;">
                        ₹{{ number_format($billing->doctor_fee, 2) }}</td>
                </tr>
                @if($billing->additional_charges > 0)
                    <tr>
                        <td style="padding: 15px 0; border-bottom: 1px solid #f8fafc;">Additional Charges</td>
                        <td style="text-align: right; padding: 15px 0; border-bottom: 1px solid #f8fafc;">
                            ₹{{ number_format($billing->additional_charges, 2) }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding: 20px 0; font-size: 18px; font-weight: 700;">Total Amount</td>
                    <td style="text-align: right; padding: 20px 0; font-size: 18px; font-weight: 800; color: #10b981;">
                        ₹{{ number_format($billing->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="background: #f8fafc; padding: 15px; border-radius: 6px; margin-bottom: 30px;">
            <p style="margin: 0; font-size: 13px; color: #475569;"><strong>Payment Method:</strong>
                {{ $billing->payment_method }}</p>
            <p style="margin: 5px 0 0 0; font-size: 13px; color: #475569;"><strong>Status:</strong> <span
                    style="color: #059669; font-weight: 700;">PAID</span></p>
        </div>

        <div style="text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 20px;">
            <p style="margin: 0; color: #94a3b8; font-size: 12px;">This is a computer-generated receipt.</p>
            <button onclick="window.print()" class="btn btn-primary"
                style="margin-top: 20px; background: #64748b; border-color: #64748b;">
                <i class="fas fa-print"></i> Print Receipt
            </button>
            <a href="{{ route('reception.billings') }}" class="btn"
                style="margin-top: 20px; margin-left: 10px; text-decoration: none; color: #64748b;">Back to Billings</a>
        </div>
    </div>
@endsection