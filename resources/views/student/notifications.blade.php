@extends('layouts.student')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Notifications</div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <p>No notifications available.</p>
                    @else
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    <h5>{{ $notification->title }}</h5>
                                    <p>{{ $notification->message }}</p>
                                    @if($notification->receipt_path)
                                        <a href="{{ asset('storage/' . $notification->receipt_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                            Download Receipt
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection