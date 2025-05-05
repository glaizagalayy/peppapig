@extends('layouts.finance')

@section('title', 'Finance Dashboard')

@section('content')
<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .card-header {
        background: linear-gradient(90deg, #FF9933, #FFAA55);
        color: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: #FF9933;
        box-shadow: 0 0 0 0.2rem rgba(255, 153, 51, 0.25);
    }
    .spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .chart-container {
        position: relative;
        min-height: 200px;
        width: 100%;
    }
    .no-data {
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }
    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .card-header h5 {
            font-size: 1.1rem;
        }
        .chart-container {
            min-height: 150px;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0r
        }
        .form-label {
            font-size: 0.9rem;
        }
        .form-select, .form-control {
            font-size: 0.9rem;
        }
        .no-data {
            font-size: 0.9rem;
        }
        .g-3 {
            gap: 0.75rem !important;
        }
    }
    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .export-csv-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Summary Boxes -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 fade-in">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="fas fa-calendar-alt text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">This Month's Collected</h6>
                        <h4 class="mb-0 fw-bold text-primary">₱<span id="monthlyCollected">0.00</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 fade-in">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="fas fa-calendar text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">Yearly Collected</h6>
                        <h4 class="mb-0 fw-bold text-success">₱<span id="yearlyCollected">0.00</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 fade-in">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="fas fa-wallet text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">Overall Collected</h6>
                        <h4 class="mb-0 fw-bold text-warning">₱<span id="overallCollected">0.00</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Analytics -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Payment Analytics</h5>
                    <button class="btn btn-sm btn-light export-csv-btn" aria-label="Export chart data as CSV">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-4 g-3">
                        <div class="col-12 col-md-3">
                            <label for="batchFilter" class="form-label fw-semibold">Batch Year</label>
                            <select id="batchFilter" class="form-select" aria-label="Filter by batch year">
                                <option value="">Overall</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->batch_year }}">{{ $batch->batch_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="paymentModeFilter" class="form-label fw-semibold">Payment Mode</label>
                            <select id="paymentModeFilter" class="form-select" aria-label="Filter by payment mode">
                                <option value="">All Modes</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="dateRange" class="form-label fw-semibold">Date Range</label>
                            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" aria-label="Select date range">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="studentFilter" class="form-label fw-semibold">Student</label>
                            <select id="studentFilter" class="form-select" aria-label="Filter by student">
                                <option value="">All Students</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->student_id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary btn-sm me-2 apply-filters-btn">Apply Filters</button>
                            <button class="btn btn-outline-secondary btn-sm reset-filters-btn">Reset Filters</button>
                        </div>
                    </div>

                    <!-- Monthly Payments Chart -->
                    <h6>Monthly Payment Trends</h6>
                    <div class="chart-container">
                        <div class="spinner-border text-primary spinner" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <canvas id="monthlyPaymentsChart" height="100"></canvas>
                        <div id="monthlyNoData" class="no-data" style="display: none;">No data available for the selected filters.</div>
                    </div>
                    <hr>
                    <!-- Yearly Payments Chart -->
                    <h6>Yearly Payment Trends</h6>
                    <div class="chart-container">
                        <div class="spinner-border text-primary spinner" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <canvas id="yearlyPaymentsChart" height="100"></canvas>
                        <div id="yearlyNoData" class="no-data" style="display: none;">No data available for the selected filters.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let monthlyPaymentsChart, yearlyPaymentsChart;

        const monthlyCtx = document.getElementById('monthlyPaymentsChart').getContext('2d');
        const yearlyCtx = document.getElementById('yearlyPaymentsChart').getContext('2d');

        // Initialize Date Range Picker
        if (typeof jQuery !== 'undefined') {
            $('#dateRange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', (ev, picker) => {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            }).on('cancel.daterangepicker', () => {
                $(this).val('');
            });
        } else {
            console.error('jQuery is not loaded. DateRangePicker will not work.');
        }

        function showSpinner(show) {
            document.querySelectorAll('.spinner').forEach(spinner => {
                spinner.style.display = show ? 'block' : 'none';
            });
        }

        function fetchPayments(url, params = {}) {
            const query = new URLSearchParams(params).toString();
            showSpinner(true);
            return fetch(`${url}?${query}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .catch(error => {
                    console.error(`Error fetching data from ${url}:`, error);
                    return [];
                })
                .finally(() => showSpinner(false));
        }

        function updateCharts() {
            const batchYear = document.getElementById('batchFilter').value;
            const paymentMode = document.getElementById('paymentModeFilter').value;
            const dateRange = document.getElementById('dateRange').value;
            const studentId = document.getElementById('studentFilter').value;

            const params = {};
            if (batchYear) params.batch_year = batchYear;
            if (paymentMode) params.payment_mode = paymentMode;
            if (dateRange) {
                const [startDate, endDate] = dateRange.split(' - ');
                params.start_date = startDate;
                params.end_date = endDate;
            }
            if (studentId) params.student_id = studentId;

            // Update Monthly Payments Chart
            fetchPayments('/finance/monthly-payments', params).then(data => {
                const monthlyNoData = document.getElementById('monthlyNoData');
                const monthlyChart = document.getElementById('monthlyPaymentsChart');

                if (data.length === 0) {
                    monthlyNoData.style.display = 'block';
                    monthlyChart.style.display = 'none';
                    return;
                }

                monthlyNoData.style.display = 'none';
                monthlyChart.style.display = 'block';

                const labels = data.map(item => `${item.year}-${String(item.month).padStart(2, '0')}`);
                const totals = data.map(item => item.total);

                if (monthlyPaymentsChart) {
                    monthlyPaymentsChart.destroy();
                }

                monthlyPaymentsChart = new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Payments (₱)',
                            data: totals,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { font: { size: window.innerWidth < 768 ? 10 : 12 } } },
                            datalabels: {
                                color: '#333',
                                anchor: 'end',
                                align: 'top',
                                font: { size: window.innerWidth < 768 ? 8 : 10 },
                                formatter: value => `₱${value.toLocaleString()}`
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { font: { size: window.innerWidth < 768 ? 8 : 10 } }
                            },
                            x: {
                                ticks: {
                                    maxRotation: window.innerWidth < 768 ? 90 : 45,
                                    minRotation: window.innerWidth < 768 ? 90 : 45,
                                    font: { size: window.innerWidth < 768 ? 8 : 10 }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            });

            // Update Yearly Payments Chart
            fetchPayments('/finance/yearly-payments', params).then(data => {
                const yearlyNoData = document.getElementById('yearlyNoData');
                const yearlyChart = document.getElementById('yearlyPaymentsChart');

                if (data.length === 0) {
                    yearlyNoData.style.display = 'block';
                    yearlyChart.style.display = 'none';
                    return;
                }

                yearlyNoData.style.display = 'none';
                yearlyChart.style.display = 'block';

                const labels = data.map(item => item.year);
                const totals = data.map(item => item.total);

                if (yearlyPaymentsChart) {
                    yearlyPaymentsChart.destroy();
                }

                yearlyPaymentsChart = new Chart(yearlyCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Payments (₱)',
                            data: totals,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { font: { size: window.innerWidth < 768 ? 10 : 12 } } },
                            datalabels: {
                                color: '#333',
                                anchor: 'end',
                                align: 'top',
                                font: { size: window.innerWidth < 768 ? 8 : 10 },
                                formatter: value => `₱${value.toLocaleString()}`
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { font: { size: window.innerWidth < 768 ? 8 : 10 } }
                            },
                            x: {
                                ticks: { font: { size: window.innerWidth < 768 ? 8 : 10 } }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            });
        }

        function resetFilters() {
            document.getElementById('batchFilter').value = '';
            document.getElementById('paymentModeFilter').value = '';
            document.getElementById('dateRange').value = '';
            document.getElementById('studentFilter').value = '';
            updateCharts();
        }

        function exportToCSV() {
            const batchYear = document.getElementById('batchFilter').value;
            const paymentMode = document.getElementById('paymentModeFilter').value;
            const dateRange = document.getElementById('dateRange').value;
            const studentId = document.getElementById('studentFilter').value;

            const params = {};
            if (batchYear) params.batch_year = batchYear;
            if (paymentMode) params.payment_mode = paymentMode;
            if (dateRange) {
                const [startDate, endDate] = dateRange.split(' - ');
                params.start_date = startDate;
                params.end_date = endDate;
            }
            if (studentId) params.student_id = studentId;

            fetchPayments('/finance/monthly-payments', params).then(data => {
                if (data.length === 0) {
                    alert('No data available to export.');
                    return;
                }
                const csvRows = ['Year,Month,Total Payments (₱)'];
                data.forEach(item => {
                    csvRows.push(`${item.year},${item.month},${item.total}`);
                });
                const csvContent = csvRows.join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.setAttribute('href', url);
                a.setAttribute('download', 'monthly_payments.csv');
                a.click();
                window.URL.revokeObjectURL(url);
            });
        }
        function fetchSummaryData() {
        fetch('/finance/summary-data')
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                // Update the dashboard values
                document.getElementById('monthlyCollected').textContent = parseFloat(data.monthly_collected).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('yearlyCollected').textContent = parseFloat(data.yearly_collected).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('overallCollected').textContent = parseFloat(data.overall_collected).toLocaleString('en-US', { minimumFractionDigits: 2 });
            })
            .catch(error => {
                console.error('Error fetching summary data:', error);
            });
        }
        fetchSummaryData();

        // Attach event listeners
        document.querySelector('.apply-filters-btn').addEventListener('click', updateCharts);
        document.querySelector('.reset-filters-btn').addEventListener('click', resetFilters);
        document.querySelector('.export-csv-btn').addEventListener('click', exportToCSV);

        // Initialize charts on page load
        updateCharts();

        // Update charts on window resize for font size adjustments
        window.addEventListener('resize', updateCharts);
    });
</script>
@endsection