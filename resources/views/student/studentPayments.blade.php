@extends('layouts.student')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('styles')
  <!-- …existing CSS… -->
@endsection

@section('content')
@php
    $approvedFinance = $payments->filter(fn($p) => in_array($p->status, ['Approved','Added by Finance']));
    $declined        = $payments->filter(fn($p) => $p->status === 'Declined');
    $pending         = $payments->filter(fn($p) => $p->status === 'Pending');
    $approvedTotal   = $approvedFinance->sum('amount');
    $declinedTotal   = $declined->sum('amount');
    $pendingTotal    = $pending->sum('amount');
@endphp

<div class="container-fluid">
  {{-- Global Search --}}
  <input
    type="text"
    id="global-search"
    class="form-control mb-3"
    placeholder="🔍 Search payments…"
  />

  {{-- Summary Card --}}
  <div class="row mb-4 justify-content-center">
    <!-- … existing Approved total card … -->
  </div>

  {{-- Status Tabs --}}
  <ul class="nav nav-pills justify-content-center mb-3" id="payment-tabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active"
              id="approved-tab"
              data-bs-toggle="pill"
              data-bs-target="#approved"
              type="button" role="tab">
        Approved <span class="badge bg-white text-success">{{ $approvedFinance->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link"
              id="declined-tab"
              data-bs-toggle="pill"
              data-bs-target="#declined"
              type="button" role="tab">
        Declined <span class="badge bg-white text-danger">{{ $declined->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link"
              id="pending-tab"
              data-bs-toggle="pill"
              data-bs-target="#pending"
              type="button" role="tab">
        Pending <span class="badge bg-white text-warning">{{ $pending->count() }}</span>
      </button>
    </li>
  </ul>

  {{-- Tab Panes --}}
  <div class="tab-content" id="payment-tabs-content">
    <div class="tab-pane fade show active" id="approved" role="tabpanel">
      @include('student._table', [
        'payments'     => $approvedFinance,
        'emptyMessage' => 'Nothing approved yet.',
        'badgeClass'   => fn($s) => $s==='Approved' ? 'success' : 'info'
      ])
    </div>

    <div class="tab-pane fade" id="declined" role="tabpanel">
      @include('student._table', [
        'payments'     => $declined,
        'emptyMessage' => 'No declined transactions.',
        'badgeClass'   => fn($s) => 'danger'
      ])
    </div>

    <div class="tab-pane fade" id="pending" role="tabpanel">
      @include('student._table', [
        'payments'     => $pending,
        'emptyMessage' => 'No pending payments.',
        'badgeClass'   => fn($s) => 'warning'
      ])
    </div>
  </div>

  {{-- Doughnut Summary --}}
  <div class="chart-container mt-4">
    <canvas id="paymentChart"></canvas>
  </div>
</div>
@endsection

@section('scripts')
  <!-- jQuery, DataTables & Chart.js -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    $(function(){
      // Init DataTables for each tab
      const dtApproved = $('#approved-table').DataTable({ paging:true, ordering:true, info:false });
      const dtDeclined = $('#declined-table').DataTable({ paging:true, ordering:true, info:false });
+     const dtPending  = $('#pending-table').DataTable({ paging:true, ordering:true, info:false });

      // Global search across all
      $('#global-search').on('keyup', function(){
        dtApproved.search(this.value).draw();
        dtDeclined.search(this.value).draw();
+       dtPending.search(this.value).draw();
      });

      // Doughnut with 3 statuses
      new Chart($('#paymentChart'), {
        type: 'doughnut',
        data: {
          labels: ['Approved','Declined','Pending'],
          datasets: [{
            data: [{{ $approvedTotal }},{{ $declinedTotal }},{{ $pendingTotal }}],
            backgroundColor: [
              'var(--brand-success)',
              'var(--brand-danger)',
              'var(--brand-warning)'
            ]
          }]
        },
        options: {
          cutout: '80%',
          maintainAspectRatio: false,
          plugins: { legend: { display: false } }
        }
      });
    });
  </script>
@endsection