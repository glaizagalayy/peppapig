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

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Account Balance Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Account Balance</h5>
                </div>
                <div class="card-body">
                    @php
                        // Dummy balance value for sample UI
                        $balance = 2500;
                    @endphp
                    <h2 class="fw-bold">₱{{ number_format($balance, 2) }}</h2>
                    @if($balance > 12000)
                        <p class="text-success">
                            You have exceeded ₱12,000 by ₱{{ number_format($balance - 12000, 2) }}.
                        </p>
                    @else
                        <p class="text-danger">
                            Your balance is below ₱12,000.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dashboard Overview Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Dashboard Overview</h5>
                </div>
                <div class="card-body">
                    <p>
                        Here you can check your recent transactions, update your profile details, and review important notifications.
                    </p>
                    <!-- You can add additional sample UI components here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
