@extends('layouts.student')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Your Notifications</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($notifications as $notification)
                    <li class="list-group-item">
                        @if(isset($notification->data['type']) && $notification->data['type'] === 'batch_update')
                            <div class="notification-content">
                                <p class="mb-3">{!! str_replace("\n", "<br>", $notification->data['message']) !!}</p>
                                <div class="notification-details">
                                    <strong>Batch Year:</strong> {{ $notification->data['batch_year'] }}<br>
                                    @if(isset($notification->data['old_amount']))
                                        <strong>Previous Amount:</strong> <strong>₱{{ number_format($notification->data['old_amount'], 2) }}</strong><br>
                                    @endif
                                    <strong>New Amount:</strong> <strong>₱{{ number_format($notification->data['new_amount'], 2) }}</strong><br>
                                    <strong>Date:</strong> {{ $notification->data['payment_date'] }}<br>
                                    <strong>Status:</strong> 
                                    <span class="badge bg-info">
                                        {{ $notification->data['status'] }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <p>
                                <strong>Amount:</strong> ₱{{ number_format($notification->data['amount'], 2) }}<br>
                                <strong>Date:</strong> {{ $notification->data['payment_date'] }}<br>
                                <strong>Reference Number:</strong> {{ $notification->data['reference_number'] ?? 'N/A' }}<br>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $notification->data['status'] === 'Approved' ? 'success' : ($notification->data['status'] === 'Declined' ? 'danger' : 'warning') }}">
                                    {{ $notification->data['status'] }}
                                </span>
                            </p>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item">No notifications available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection