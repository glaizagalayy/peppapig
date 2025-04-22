@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info shadow-sm">
                <h4>Hello, Sample Student!</h4>
                <p>Welcome to your personalized finance dashboard.</p>
            </div>
        </div>
    </div>



    <!-- Payment Summary Card -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalPaid = Auth::user()->student->payments->sum('amount');
                        $totalDue = Auth::user()->student->batch->total_due ?? 0; // Fetch total_due dynamically from the batch
                        $remainingBalance = max(0, $totalDue - $totalPaid); // Ensure remaining balance is not negative
                    @endphp
                    <p><strong>Total Paid:</strong> ₱{{ number_format($totalPaid, 2) }}</p>
                    <p><strong>Remaining Balance:</strong> ₱{{ number_format($remainingBalance, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
