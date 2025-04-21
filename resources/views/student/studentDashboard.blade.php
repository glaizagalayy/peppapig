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
                        $totalDue = 500 * 24; // 500 pesos per month for 2 years
                        $remainingBalance = $totalDue - $totalPaid;
                    @endphp
                    <p><strong>Total Paid:</strong> ₱{{ number_format($totalPaid, 2) }}</p>
                    <p><strong>Remaining Balance:</strong> ₱{{ number_format($remainingBalance, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach (Auth::user()->notifications as $notification)
                            <li class="list-group-item">
                                {{ $notification->data['message'] ?? 'No message available.' }}
                                <span class="badge bg-{{ $notification->data['status'] === 'Approved' ? 'success' : ($notification->data['status'] === 'Declined' ? 'danger' : 'warning') }}">
                                    {{ $notification->data['status'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
