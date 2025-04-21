@extends('layouts.finance')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Payment Notifications</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($notifications as $notification)
                    <li class="list-group-item">
                        <p>
                            <strong>Student ID:</strong> {{ $notification->data['student_id'] }}<br>
                            <strong>Amount:</strong> ₱{{ number_format($notification->data['amount'], 2) }}<br>
                            <strong>Date:</strong> {{ $notification->data['payment_date'] }}<br>
                            <strong>Reference Number:</strong> {{ $notification->data['reference_number'] ?? 'N/A' }}
                        </p>
                        <form method="POST" action="{{ route('finance.verifyPayment', $notification->id) }}">
                            @csrf
                            <button type="submit" name="status" value="Approved" class="btn btn-success btn-sm">Approve</button>
                            <button type="submit" name="status" value="Declined" class="btn btn-danger btn-sm">Decline</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item">No notifications available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection