@extends('layouts.student')

@section('title', 'Upload Payment Proof')
@section('page-title', 'Upload Payment Proof')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Submit Payment Proof</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('student.uploadPaymentProof') }}" enctype="multipart/form-data" id="paymentForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" 
                                class="form-control" 
                                id="amount" 
                                name="amount" 
                                step="0.01" 
                                min="1" 
                                value="{{ old('amount') }}" 
                                required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" 
                            class="form-control" 
                            id="payment_date" 
                            name="payment_date" 
                            value="{{ old('payment_date', date('Y-m-d')) }}" 
                            max="{{ date('Y-m-d') }}"
                            required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="payment_mode" class="form-label">Payment Mode</label>
                        <select class="form-select" id="payment_mode" name="payment_mode">
                            <option value="" hidden>Select Payment Mode</option>
                            <option value="GCash" {{ old('payment_mode') == 'GCash' ? 'selected' : '' }}>GCash</option>
                            <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" 
                            class="form-control" 
                            id="reference_number" 
                            name="reference_number" 
                            value="{{ old('reference_number') }}"
                            placeholder="Enter payment reference number"
                            required>
                        <div class="form-text">Enter the reference number from your payment receipt/screenshot</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="payment_proof" class="form-label">Payment Proof</label>
                    <input type="file" 
                        class="form-control" 
                        id="payment_proof" 
                        name="payment_proof" 
                        accept=".jpg,.jpeg,.png,.pdf" 
                        required>
                    <div class="form-text">Upload a clear image/screenshot of your payment (Max 2MB, JPG/PNG/PDF only)</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirmSubmission()">
                        <i class="fas fa-paper-plane me-2"></i>Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmSubmission() {
    const form = document.getElementById('paymentForm');

    // Check if the form is valid
    if (!form.checkValidity()) {
        form.reportValidity(); // Show native validation messages
        return false; // Prevent submission
    }

    // Show confirmation dialog
    return confirm('Are you sure you want to submit this payment? Please ensure all details are correct as this cannot be modified once submitted.');
}
</script>
@endpush
@endsection