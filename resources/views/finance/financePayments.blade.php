@extends('layouts.finance')

@section('title', 'Payments')
@section('page-title', 'Payment Management')

@push('styles')
<style>
    :root {
        --primary:    #FF9933;
        --secondary:  #32abe3;
        --bg-light:   #f5f6fa;
        --card-bg:    #ffffff;
        --text-dark:  #2c3e50;
        --text-muted: #6c757d;
        --border:     #e1e4e8;
        --accent:     #FF9933;
    }

    body, .container-fluid {
        font-family: 'Inter', sans-serif;
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }

    .card-modern {
        background: var(--card-bg);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .card-header-modern {
        background: linear-gradient(90deg, var(--primary));
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .card-header-modern h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .filter-group input,
    .filter-group select {
        padding: 0.5rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        width: 200px;
    }

    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 0 0 0.75rem 0.75rem;
    }
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
        min-width: 700px; /* ensures scroll on small */
    }
    .table-modern thead th {
        position: sticky;
        top: 0;
        background: var(--card-bg);
        color: var(--primary);
        font-weight: 600;
        padding: 0.75rem;
        text-transform: uppercase;
        border-bottom: 2px solid var(--border);
        z-index: 2;
    }
    .table-modern tbody td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .table-modern tbody tr:nth-child(even) {
        background: var(--bg-light);
    }
    .table-modern tbody tr:hover {
        background: var(--secondary);
        color: #fff;
    }

    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
    }
    .manage-btn {
        padding: 0.55rem 1rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
    }
    .manage-btn:hover {
        opacity: 0.9;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(0,95,153,0.25) !important;
    }
    .modal-content {
        border-radius: 0.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* MOBILE tweaks */
    @media (max-width: 576px) {
        .filters {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        .filter-group input,
        .filter-group select {
            width: 100%;
        }
        .manage-btn {
            width: 100%;
            text-align: center;
        }
        .table-responsive {
            max-height: none;
        }
        /* allow horizontal scroll rather than block layout */
        .table-modern {
            min-width: 600px;
        }
    }
</style>
@endpush

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="searchInput">Search</label>
            <input type="text"
                   id="searchInput"
                   placeholder="Name or Student ID"
                   onkeyup="filterTable()">
        </div>
        <div class="filter-group">
            <label for="batchFilter">Batch Year</label>
            <select id="batchFilter" onchange="filterByBatch()">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->batch_year }}"
                        {{ request('batch_year') == $batch->batch_year ? 'selected' : '' }}>
                        {{ $batch->batch_year }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="manage-btn btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#manageBatchModal">
            <i class="fas fa-cog me-1"></i>
            Manage Batches
        </button>
    </div>

    <!-- Payment Table -->
    <div class="card card-modern">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <h5>Student Payments</h5>
        </div>
        <div class="table-responsive">
            <table class="table-modern mb-0" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Batch Year</th>
                        <th>Total Paid</th>
                        <th>Remaining Balance</th>
                        <th>Payables</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students->sortBy('last_name') as $student)
                        @if(!request('batch_year') || $student->batch_year == request('batch_year'))
                            <tr>
                                <td data-label="Student ID">{{ $student->student_id }}</td>
                                <td data-label="Name">{{ $student->last_name }} {{ $student->first_name }} </td>
                                <td data-label="Batch Year">{{ $student->batch_year }}</td>
                                <td data-label="Total Paid">₱{{ number_format($student->total_paid,2) }}</td>
                                <td data-label="Remaining">₱{{ number_format($student->remaining_balance,2) }}</td>
                                <td data-label="Due">₱{{ number_format($student->batch->total_due ?? 0,2) }}</td>
                                <td data-label="Actions">
                                    <button class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentModal"
                                            onclick="loadAddPaymentForm('{{ $student->student_id }}')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <a href="{{ route('finance.getPaymentHistory',['studentId'=>$student->student_id]) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manage Batch Modal -->
    <div class="modal fade" id="manageBatchModal" tabindex="-1" aria-labelledby="manageBatchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Batch Amounts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('finance.updateBatch') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="batch_year" class="form-label fw-semibold">Batch Year</label>
                            <select name="batch_year" id="batch_year" class="form-select" required>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->batch_year }}">{{ $batch->batch_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_due" class="form-label fw-semibold">Amount Due</label>
                            <input type="number" name="total_due" id="total_due" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" method="POST" action="{{ route('finance.addPayment') }}">
                        @csrf
                        <input type="hidden" id="student_id" name="student_id">
                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                   step="0.01" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label fw-semibold">Date</label>
                            <input type="date" class="form-control" id="payment_date"
                                   name="payment_date" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_mode" class="form-label fw-semibold">Mode</label>
                            <select class="form-select" id="payment_mode" name="payment_mode" required>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Add Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function loadAddPaymentForm(id) {
        document.getElementById('student_id').value = id;
    }
    function filterByBatch() {
        const batchYear = document.getElementById('batchFilter').value;
        const url = new URL(window.location.href);
        batchYear
            ? url.searchParams.set('batch_year', batchYear)
            : url.searchParams.delete('batch_year');
        window.location.href = url;
    }
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#paymentsTable tbody tr').forEach(row => {
            const idCell   = row.querySelector('td[data-label="Student ID"]').innerText.toLowerCase();
            const nameCell = row.querySelector('td[data-label="Name"]').innerText.toLowerCase();
            row.style.display = (idCell.includes(query) || nameCell.includes(query)) ? '' : 'none';
        });
    }
</script>
@endsection