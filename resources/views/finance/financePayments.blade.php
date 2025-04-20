@extends('layouts.finance')

@section('title', 'Payments')
@section('page-title', 'Payment Management')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Payment Table -->
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #FF9933;">
            <h5 class="mb-0">Student Payments</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Total Paid</th>
                        <th>Remaining Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        @php
                            $totalPaid = $student->payments->sum('amount');
                            $totalDue = 500 * 24; // 500 pesos per month for 2 years
                            $remainingBalance = $totalDue - $totalPaid;
                        @endphp
                        <tr>
                            {{-- <td>{{ $loop->iteration }}</td> --}}
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>₱{{ number_format($totalPaid, 2) }}</td>
                            <td>₱{{ number_format($remainingBalance, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                        onclick="loadPaymentHistory('{{ $student->student_id }}')">
                                    View & Add Payment
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Payment History -->
                <h6>Payment History</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="paymentHistory">
                        <!-- Payment history will be loaded here via JavaScript -->
                    </tbody>
                </table>

                <!-- Add Payment Form -->
                <h6 class="mt-4">Add Payment</h6>
                <form id="addPaymentForm" method="POST" action="{{ route('finance.addPayment') }}">
                    @csrf
                    <input type="hidden" id="student_id" name="student_id">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Payment Mode</label>
                        <select class="form-select" id="payment_mode" name="payment_mode" required>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function loadPaymentHistory(studentId) {
        document.getElementById('student_id').value = studentId;

        // Fetch payment history via AJAX
        fetch(`/finance/payments/history/${studentId}`)
            .then(response => response.json())
            .then(data => {
                const paymentHistory = document.getElementById('paymentHistory');
                paymentHistory.innerHTML = '';

                data.forEach((payment, index) => {
                    paymentHistory.innerHTML += `
                        <tr>
                           {{-- <td>${index + 1}</td> --}}
                            <td>₱${payment.amount.toFixed(2)}</td>
                            <td>${payment.payment_date}</td>
                            <td>${payment.payment_mode}</td>
                            <td>${payment.status}</td>
                        </tr>
                    `;
                });
            });
    }
</script>
@endsection