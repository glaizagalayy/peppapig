@extends('layouts.finance')

@section('title', 'Dashboard')
@section('page-title', 'Finance Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header text-white "style="background-color: #FF9933;">
                        <h5 class="mb-0">Dashboard Overview</h5>
                    </div>
                    <div class="card-body">
                        <p>Welcome to your finance dashboard!</p>
                        <canvas id="financeChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('financeChart').getContext('2d');
        const financeChart = new Chart(ctx, {
            type: 'bar', // Change to 'line', 'pie', etc., as needed
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Revenue',
                    data: [Class 2025, Class 2026]
                    backgroundColor: 'rgba(255, 153, 51, 0.5)',
                    borderColor: 'rgba(255, 153, 51, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush