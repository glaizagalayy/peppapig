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

    <!-- Batch Filter -->
    <div class="mb-3">
        <label for="batchFilter" class="form-label">Filter by Batch Year</label>
        <select id="batchFilter" class="form-select" onchange="filterByBatch()">
            <option value="">All Batches</option>
            @foreach ($batches as $batch)
                <option value="{{ $batch->batch_year }}" {{ request('batch_year') == $batch->batch_year ? 'selected' : '' }}>
                    {{ $batch->batch_year }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Payment Table -->
    <div class="card shadow-sm">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #FF9933;">
            <h5 class="mb-0">Student Payments</h5>
            <!-- Settings Icon -->
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#manageBatchModal">
                <i class="fas fa-cog"></i> Manage Batches
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
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
                            $totalDue = $student->batch->total_due ?? 0; // Fetch total_due dynamically from the batch
                            $remainingBalance = max(0, $totalDue - $totalPaid); // Ensure remaining balance is not negative
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>₱{{ number_format($totalPaid, 2) }}</td>
                            <td>₱{{ number_format($remainingBalance, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                        onclick="loadAddPaymentForm('{{ $student->student_id }}')">
                                    Add Payment
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Manage Batch Modal -->
<div class="modal fade" id="manageBatchModal" tabindex="-1" aria-labelledby="manageBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageBatchModalLabel">Manage Batch Payment Amounts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('finance.updateBatch') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="batch_year" class="form-label">Batch Year</label>
                        <input type="text" name="batch_year" id="batch_year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_due" class="form-label">Total Amount Due</label>
                        <input type="number" name="total_due" id="total_due" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Payment Form -->
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
    function loadAddPaymentForm(studentId) {
        document.getElementById('student_id').value = studentId;
    }

    function filterByBatch() {
        const batchYear = document.getElementById('batchFilter').value;
        const url = new URL(window.location.href);
        if (batchYear) {
            url.searchParams.set('batch_year', batchYear);
        } else {
            url.searchParams.delete('batch_year');
        }
        window.location.href = url.toString();
    }
</script>
@endsection