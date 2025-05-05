@extends('layouts.finance')

@section('title', 'Payment History')
@section('page-title', 'Payment History for ' . $student->first_name . ' ' . $student->last_name)

@push('styles')
<style>
    :root {
        --primary:   #005f99;
        --secondary: #00aaff;
        --bg-light:  #f5f6fa;
        --card-bg:   #ffffff;
        --text-dark: #2c3e50;
        --text-muted:#6c757d;
        --border:    #e1e4e8;
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
        background: linear-gradient(90deg, var(--primary), var(--secondary));
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

    /* Student overview grid */
    .student-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: var(--bg-light);
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
    }
    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .detail-item i {
        font-size: 1.2rem;
        color: var(--primary);
    }
    .detail-item .label {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .detail-item .value {
        font-size: 1rem;
        font-weight: 600;
    }

    /* Table styling */
    .table-responsive {
        overflow-x: auto;
        border-radius: 0 0 0.75rem 0.75rem;
    }
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
        min-width: 600px; /* triggers horizontal scroll on small */
    }
    .table-modern thead th {
        background: var(--primary);
        color: #fff;
        font-weight: 600;
        padding: 0.75rem;
        text-transform: uppercase;
        position: sticky;
        top: 0;
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

    /* Status badges */
    .badge-status {
        padding: 0.25em 0.75em;
        border-radius: 12px;
        font-size: 0.75rem;
        text-transform: capitalize;
    }
    .badge-approved { background: #d4edda; color: #155724; }
    .badge-declined { background: #f8d7da; color: #721c24; }
    .badge-added    { background: #cce5ff; color: #004085; }

    /* Mobile tweaks */
    @media (max-width: 576px) {
        .student-details {
            grid-template-columns: 1fr;
        }
        .table-modern {
            min-width: 500px;
        }
    }

    .modal {
        background: rgba(0, 0, 0, 0.5);
    }
    .modal-backdrop {
        display: none;
    }
    .modal.show {
        display: block !important;
    }
</style>
@endpush

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item">
                <a href="{{ route('finance.financeDashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Payment History
            </li>
        </ol>
    </nav>

    <!-- Student Overview -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <h5>Student Overview</h5>
        </div>
        <div class="student-details">
            <div class="detail-item">
                <i class="fas fa-id-badge"></i>
                <div>
                    <p class="label">Student ID</p>
                    <p class="value">{{ $student->student_id }}</p>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <div>
                    <p class="label">Name</p>
                    <p class="value">{{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <p class="label">Batch Year</p>
                    <p class="value">{{ $student->batch->batch_year }}</p>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-money-bill-wave"></i>
                <div>
                    <p class="label">Total Due</p>
                    <p class="value">₱{{ number_format($totalDue,2) }}</p>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-wallet"></i>
                <div>
                    <p class="label">Total Paid</p>
                    <p class="value">₱{{ number_format($totalPaid,2) }}</p>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-balance-scale"></i>
                <div>
                    <p class="label">Remaining</p>
                    <p class="value">₱{{ number_format($remainingBalance,2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Transactions -->
    <div class="card card-modern">
        <div class="card-header-modern">
            <h5>Payment Transactions</h5>
        </div>
        <div class="card-body p-0">
            @if($payments->isEmpty())
                <div class="text-center py-4 text-muted">
                    No payment transactions found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Mode</th>
                                <th>Reference Number</th>
                                <th>Proof of Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @if (in_array($payment->status, ['Approved','Added by Finance']))
                                    <tr>
                                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_mode) }}</td>
                                        <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                        <td>
                                            @if($payment->payment_proof)
                                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank">
                                                    View Proof
                                                </a>
                                            @else
                                                No Proof Uploaded or Added by Finance
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($payment->status) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                        data-bs-target="#editPaymentModal{{ $payment->payment_id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('finance.deletePayment', $payment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this payment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Payment Modal -->
                                    <div class="modal fade" 
                                         id="editPaymentModal{{ $payment->payment_id }}" 
                                         tabindex="-1" 
                                         role="dialog"
                                         data-bs-backdrop="static"
                                         aria-labelledby="editPaymentModal{{ $payment->payment_id }}Label">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPaymentModal{{ $payment->payment_id }}Label">
                                                        Edit Payment
                                                    </h5>
                                                    <button type="button" 
                                                            class="btn-close" 
                                                            data-bs-dismiss="modal" 
                                                            aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finance.editPayment', $payment) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="amount" class="form-label">Amount</label>
                                                            <input type="number" class="form-control" id="amount" name="amount"
                                                                   value="{{ $payment->amount }}" step="0.01" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_date" class="form-label">Date</label>
                                                            <input type="date" class="form-control" id="payment_date"
                                                                   name="payment_date" value="{{ $payment->payment_date->format('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_mode" class="form-label">Mode</label>
                                                            <select class="form-select" id="payment_mode" name="payment_mode" required>
                                                                <option value="cash" {{ $payment->payment_mode == 'cash' ? 'selected' : '' }}>Cash</option>
                                                                <option value="gcash" {{ $payment->payment_mode == 'gcash' ? 'selected' : '' }}>GCash</option>
                                                                <option value="bank_transfer" {{ $payment->payment_mode == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="reference_number" class="form-label">Reference Number</label>
                                                            <input type="text" class="form-control" id="reference_number"
                                                                   name="reference_number" value="{{ $payment->reference_number }}">
                                                        </div>
                                                        {{-- <div class="mb-3">
                                                            <label for="student_id" class="form-label">Student</label>
                                                            <select class="form-select" id="student_id" name="student_id" required>
                                                                @foreach($students as $student)
                                                                    <option value="{{ $student->student_id }}" 
                                                                            {{ $payment->student_id == $student->student_id ? 'selected' : '' }}>
                                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div> --}}
                                                        <input type="hidden" name="student_id" value="{{ $payment->student_id }}">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection