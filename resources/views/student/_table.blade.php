{{-- Mobile “card” view --}}
<div class="d-md-none">
    @forelse($payments as $p)
      <div class="payment-card {{ Str::lower($p->status) }}">
        <div class="d-flex justify-content-between">
          <div>
            <div class="fw-bold">{{ $p->payment_date }}</div>
            <div class="text-muted small">Ref: {{ $p->reference_number ?? '–' }}</div>
          </div>
          <div class="text-end">
            <div class="fw-semibold">₱{{ number_format($p->amount,2) }}</div>
            <span class="badge bg-{{ $badgeClass($p->status) }}">{{ $p->status }}</span>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center text-muted">{{ $emptyMessage }}</div>
    @endforelse
  </div>
  
  {{-- Desktop “table” view --}}
  <div class="table-responsive d-none d-md-block">
    <table class="table table-hover mb-0"
           id="{{ $payments->first()?->status==='Declined' ? 'declined-table' : 'approved-table' }}">
      <thead class="table-light">
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Reference #</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $p)
          <tr>
            <td>{{ $p->payment_date }}</td>
            <td>₱{{ number_format($p->amount,2) }}</td>
            <td>{{ $p->reference_number ?? '–' }}</td>
            <td>
              <span class="badge bg-{{ $badgeClass($p->status) }}">{{ $p->status }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center text-muted">{{ $emptyMessage }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>