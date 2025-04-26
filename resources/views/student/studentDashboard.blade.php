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
                    @if(Auth::user()->notifications->count() > 0)
                        <ul class="list-group">
                            @foreach (Auth::user()->notifications as $notification)
                                <li class="list-group-item">
                                    @if($notification->data['type'] == 'batch_update')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                {{ $notification->data['message'] }}
                                            </div>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    @else
                                        {{ $notification->data['message'] ?? 'No message available.' }}
                                        <span class="badge bg-{{ $notification->data['status'] === 'Approved' ? 'success' : ($notification->data['status'] === 'Declined' ? 'danger' : 'warning') }}">
                                            {{ $notification->data['status'] }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No notifications available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
