@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info shadow-sm">
                <h4>Hello, <strong>{{ strtok($student->first_name, ' ') }}</strong>!</h4>
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
                        $totalPaid = $student->payments->sum('amount');
                        $totalDue = $student->batch->total_due ?? 0;
                        $remainingBalance = max(0, $totalDue - $totalPaid);
                    @endphp
                    <p><strong>Total Paid:</strong> ₱{{ number_format($totalPaid, 2) }}</p>
                    <p><strong>Remaining Balance:</strong> ₱{{ number_format($remainingBalance, 2) }}</p>
                    <p><strong>Batch Year:</strong> {{ $student->batch_year }}</p>
                    <p><strong>Total Amount Due:</strong> ₱{{ number_format($totalDue, 2) }}</p>
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
                    @forelse($user->notifications as $notification)
                        <div class="notification-item mb-3">
                            @if(isset($notification->data['type']) && $notification->data['type'] === 'receipt_available')
                                <div class="notification-content">
                                    <i class="fas fa-file-invoice text-primary"></i>
                                    <div class="notification-text">
                                        <p class="mb-1">{!! $notification->data['message'] !!}</p>
                                        <p class="text-muted small mb-1">Receipt Number: {{ $notification->data['receipt_number'] }}</p>
                                        <p class="text-muted small mb-1">Amount: ₱{{ number_format($notification->data['amount'], 2) }}</p>
                                        <p class="text-muted small mb-1">Date: {{ \Carbon\Carbon::parse($notification->data['payment_date'])->format('F j, Y') }}</p>
                                        <a href="{{ $notification->data['download_url'] }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-download"></i> Download Receipt
                                        </a>
                                    </div>
                                </div>
                            @elseif(isset($notification->data['type']) && $notification->data['type'] === 'batch_update')
                                <div class="notification-content">
                                    <i class="fas fa-info-circle text-info"></i>
                                    <div class="notification-text">
                                        {!! $notification->data['message'] !!}
                                    </div>
                                </div>
                            @else
                                <div class="notification-content">
                                    <i class="fas fa-money-bill-wave text-success"></i>
                                    <div class="notification-text">
                                        {!! $notification->data['message'] !!}
                                    </div>
                                </div>
                            @endif
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No notifications available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
