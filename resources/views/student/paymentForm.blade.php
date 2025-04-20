@extends('layouts.student')

@section('title', 'Upload Payment Proof')
@section('page-title', 'Upload Payment Proof')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Upload Payment Proof</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('student.uploadPaymentProof') }}" method="POST" enctype="multipart/form-data">
                @csrf

                
                <!-- Amount -->
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
                </div>

                <!-- Payment Date -->
                <div class="mb-3">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Reference Number -->
                <div class="mb-3">
                    <label for="reference_number" class="form-label">Reference Number (Optional)</label>
                    <input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="Enter Reference Number (if applicable)">
                </div>

                <!-- Upload Proof -->
                <div class="mb-3">
                    <label for="payment_proof" class="form-label">Upload Proof</label>
                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*,application/pdf" required>
                    <div class="form-text">Accepted formats: JPG, PNG, PDF (Max: 2MB)</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Submit Payment Proof</button>
            </form>
        </div>
    </div>
</div>
@endsection