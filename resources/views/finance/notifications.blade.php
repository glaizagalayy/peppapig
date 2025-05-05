@extends('layouts.finance')

@section('title', 'Payment Notifications')
@section('page-title', 'Payment Notifications')

@push('styles')
<style>
    :root {
        --primary:    #005f99;
        --accent:     #FF9933;
        --bg-light:   #f5f6fa;
        --card-bg:    #ffffff;
        --text-dark:  #2c3e50;
        --text-muted: #6c757d;
        --border:     #e1e4e8;
        --success:    #28a745;
        --danger:     #dc3545;
        --warning:    #ffc107;
    }

    body, .container-fluid {
        font-family: 'Inter', sans-serif;
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .card-modern {
        background: var(--card-bg);
        border: none;
        border-radius: .75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card-header-modern {
        background: var(--accent);
        color: #fff;
        padding: 1rem 1.5rem;
        border-top-left-radius: .75rem;
        border-top-right-radius: .75rem;
    }
    .card-header-modern h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 0 0 .75rem .75rem;
    }
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: .9rem;
        min-width: 600px; /* allow scroll on small */
    }
    .table-modern thead th {
        background: #f1f3f5;
        color: var(--text-dark);
        font-weight: 600;
        padding: .75rem;
        text-transform: uppercase;
        border-bottom: 2px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .table-modern tbody td {
        padding: .75rem;
        border-bottom: 1px solid var(--border);
    }
    .table-modern tbody tr:nth-child(even) {
        background: var(--bg-light);
    }

    .badge-status {
        font-size: .75rem;
        padding: .3em .8em;
        border-radius: 1rem;
        text-transform: capitalize;
    }
    .badge-approved { background: var(--success); color: #fff; }
    .badge-declined { background: var(--danger);  color: #fff; }
    .badge-pending  { background: var(--warning); color: #212529; }

    .btn-sm {
        padding: .3rem .75rem;
        font-size: .85rem;
    }

    /* MOBILE: keep horizontal scroll on small viewports */
    @media (max-width: 576px) {
        .table-modern {
            min-width: 500px;
        }
    }
</style>
@endpush

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <h5>Recent Payments</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Mode of Payment</th>
                            <th>Reference</th>
                            <th>Payment Proof</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($students as $student)
                        @foreach ($student->payments->sortByDesc('created_at')->take(5) as $payment)
                          <tr>
                            <td data-label="Student">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td data-label="Date">{{ $payment->payment_date }}</td>
                            <td data-label="Amount">₱{{ number_format($payment->amount,2) }}</td>
                            <td data-label="Mode of Payment">{{ $payment->payment_mode }}</td>
                            <td data-label="Reference">{{ $payment->reference_number ?? 'N/A' }}</td>
                            <td data-label="Proof">
                              @if($payment->payment_proof)
                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank">View Proof</a>
                              @else
                                N/A
                              @endif
                            </td>
                            <td data-label="Status">
                              <span class="badge-status {{ 
                                $payment->status=='Approved' ? 'badge-approved' 
                                : ($payment->status=='Declined' ? 'badge-declined' 
                                : 'badge-pending') 
                              }}">
                                {{ $payment->status }}
                              </span>
                            </td>
                            <td data-label="Actions">
                              @if($payment->status=='Pending')
                                <form method="POST" action="{{ route('finance.verifyPayment',['payment'=>$payment->payment_id]) }}" class="d-inline">
                                  @csrf
                                  <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success">Approve</button>
                                  <button type="button" class="btn btn-sm btn-danger" onclick="showDeclineModal('{{ $payment->payment_id }}')">Decline</button>
                                </form>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Decline Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="declineForm" method="POST">
          @csrf
          <div class="mb-3">
            <label for="remarks" class="form-label">Reason</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="3" required></textarea>
          </div>
          <input type="hidden" name="status" value="Declined">
          <button type="submit" class="btn btn-sm btn-danger w-100">Decline</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function showDeclineModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('declineModal'));
    const form  = document.getElementById('declineForm');
    form.action = `{{ url('finance/payments/verify') }}/${id}`;
    modal.show();
  }
</script>
@endsection