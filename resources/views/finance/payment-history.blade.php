@extends('layouts.finance')

@section('title', 'Payment History')
@section('page-title', 'Student Payment History')

@section('content')
<div class="container-fluid">
    <!-- Payment History Table -->
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Student Payment History</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Batch Year</th>
                        <th>Total Paid</th>
                        <th>Remaining Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        @php
                            $totalPaid = $student->payments->sum('amount');
                            $totalDue = $student->batch->total_due ?? 0; // Fetch total_due dynamically from the batch
                            $remainingBalance = max(0, $totalDue - $totalPaid); // Ensure remaining balance is not negative
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->batch_year }}</td>
                            <td>₱{{ number_format($totalPaid, 2) }}</td>
                            <td>₱{{ number_format($remainingBalance, 2) }}</td>
                            <td>
                                <a href="{{ route('finance.getPaymentHistory', ['studentId' => $student->student_id]) }}" class="btn btn-sm btn-primary">
                                    View Payment History
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection