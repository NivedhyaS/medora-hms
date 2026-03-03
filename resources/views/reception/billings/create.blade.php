@extends('layouts.dashboard')

@section('title', 'Create Bill')
@section('header', 'Billing Details')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('reception.billings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

            <div
                style="background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #64748b;">Patient:</span>
                    <span style="font-weight: 600;">{{ $appointment->patient->user->name ?? 'Deleted Patient' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #64748b;">Doctor:</span>
                    <span style="font-weight: 600;">Dr. {{ $appointment->doctor->name ?? 'Deleted Doctor' }}</span>
                </div>
                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Consultation Fee:</span>
                    <span style="font-weight: 700; color: #059669;">₹{{ $appointment->consultation_fee }}</span>
                </div>
            </div>

            <div class="form-group">
                <label>Additional Charges (Optional)</label>
                <input type="number" name="additional_charges" id="additional_charges" class="form-control" value="0"
                    min="0" step="0.01">
            </div>

            <div
                style="background: #1e293b; color: white; padding: 20px; border-radius: 10px; margin: 20px 0; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 18px; font-weight: 600;">Total Amount:</span>
                <span style="font-size: 24px; font-weight: 800; color: #10b981;">₹<span
                        id="total_display">{{ $appointment->consultation_fee }}</span></span>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="Cash">Cash</option>
                    <option value="Card">Card</option>
                    <option value="UPI">UPI</option>
                </select>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 15px; font-size: 16px; font-weight: 700; background: #2563eb;">
                    <i class="fas fa-check-circle"></i> Mark as Paid & Generate Receipt
                </button>
            </div>
        </form>
    </div>

    <script>
        const fee = {{ $appointment->consultation_fee }};
        const additionalInput = document.getElementById('additional_charges');
        const totalDisplay = document.getElementById('total_display');

        additionalInput.addEventListener('input', function () {
            const additional = parseFloat(this.value) || 0;
            totalDisplay.textContent = (fee + additional).toFixed(2);
        });
    </script>
@endsection