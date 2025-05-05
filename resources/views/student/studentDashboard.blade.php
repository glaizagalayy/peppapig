@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
<style>
    /* Theme vars */
    :root {
        --primary-gradient: linear-gradient(135deg, #FF9933, #DD7A22);
        --info-gradient: linear-gradient(135deg, #22BBEA, #1A91B8);
        --accent-color: #2D3748;
        --secondary-accent: #4A5568;
        --card-radius: 20px;
        --box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        --skeleton-bg: #E4EBF1;
        --glow-color: rgba(255, 153, 51, 0.15);
    }

    /* Global resets */
    body {
        font-family: var(--font-primary);
        background: #E9ECEF;
        letter-spacing: -0.02em;
    }

    /* Fade-in animation with stagger */
    .fade-in {
        animation: fadeIn 0.8s ease-out;
        animation-delay: calc(var(--order) * 0.1s);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card styling */
    .card, .chart-section {
        border: none;
        border-radius: var(--card-radius);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(8px);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .card:hover, .chart-section:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
        background: rgba(255, 255, 255, 0.96);
    }
    .card::before, .chart-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid transparent;
        border-image: var(--primary-gradient) 1;
        border-radius: var(--card-radius);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .card:hover::before, .chart-section:hover::before {
        opacity: 0.2;
    }

    /* Welcome banner */
    .welcome-banner {
        background: var(--primary-gradient);
        border-radius: var(--card-radius);
        box-shadow: var(--box-shadow);
        padding: 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        --order: 1;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        opacity: 0.4;
    }
    .welcome-banner h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #fff;
    }
    .welcome-banner p {
        margin: 0;
        opacity: 0.9;
        color: #fff;
    }

    /* Summary Metrics */
    .summary-card {
        --order: 2;
        

    }
    .summary-card .card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        padding: 2.25rem;

    }
    .summary-metric {
        text-align: center;
        padding: 1.25rem;
        border-radius: 12px;
        background: rgba(133, 163, 241, 0.85);
        transition: var(--transition);
    }
    .summary-metric:hover {
        background: rgba(32, 68, 185, 0.9);
        transform: scale(1.03);
    }
    .summary-metric h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--accent-color);
        margin-bottom: 0.75rem;
    }
    .summary-metric p {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--accent-color);
    }
    .summary-metric p.text-danger {
        color: #E53E3E;
    }

    /* Chart sections */
    .chart-section {
        padding: 2rem;
        --order: 3;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .section-header h5 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--accent-color);
        position: relative;
    }
    .section-header h5::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 0;
        height: 3px;
        background: var(--primary-gradient);
        transition: width 0.3s ease;
    }
    .section-header h5:hover::after {
        width: 50%;
    }
    .section-controls {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .section-controls select,
    .section-controls button {
        font-family: var(--font-primary);
        font-size: 0.9rem;
        padding: 0.6rem 1rem;
        border: 1px solid rgba(203, 213, 224, 0.5);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.9);
        cursor: pointer;
        transition: var(--transition);
        backdrop-filter: blur(4px);
    }
    .section-controls select:hover,
    .section-controls button:hover {
        background: var(--glow-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .section-controls button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--secondary-accent);
    }
    .section-controls button i {
        font-size: 1rem;
        transition: transform 0.2s;
    }
    .section-controls button:hover i {
        transform: scale(1.2);
    }

    /* Skeleton loader */
    .skeleton-chart {
        position: relative;
        overflow: hidden;
        background: var(--skeleton-bg);
        border-radius: 12px;
        height: 260px;
    }
    .skeleton-chart::after {
        content: '';
        position: absolute;
        top: 0;
        left: -150%;
        width: 150%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 1.8s infinite;
    }
    @keyframes shimmer {
        to { transform: translateX(150%); }
    }

    /* Chart container */
    .chart-section > div:last-child {
        position: relative;
        min-height: 260px;
        max-height: 300px;
    }

    /* No-data message */
    .no-data {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--secondary-accent);
        font-size: 1rem;
        font-weight: 500;
    }

    /* Notifications */
    .card:last-child {
        --order: 4;
    }
    .list-group-item {
        border: none;
        margin-bottom: 0.75rem;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: rgba(248, 250, 255, 0.85);
        transition: var(--transition);
    }
    .list-group-item:hover {
        background: rgba(237, 239, 252, 0.9);
        transform: translateX(4px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .bg-success { background: #38A169 !important; }
    .bg-danger { background: #E53E3E !important; }
    .bg-warning { background: #FFAA33 !important; }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .summary-card .card-body { grid-template-columns: 1fr; }
        .summary-metric p { font-size: 1.6rem; }
        .chart-section > div:last-child { min-height: 200px; max-height: 250px; }
        .skeleton-chart { height: 200px; }
        .welcome-banner { padding: 1.75rem; }
        .section-controls { flex-direction: column; align-items: stretch; }
        .section-controls select,
        .section-controls button { font-size: 0.85rem; padding: 0.5rem 0.75rem; }
        .section-header h5 { font-size: 1.1rem; }
    }

    /* === ADDED CSS for flex & wrappers === */
    .h-100 { height: 100% !important; }
    .chart-wrapper {
        width: 100%;
        min-height: 260px;
        max-height: 350px;
    }
    @media (max-width: 768px) {
        .chart-wrapper {
            min-height: 200px;
            max-height: 250px;
        }
    }
    .chart-wrapper canvas {
        position: absolute !important;
        top: 0; left: 0;
        width: 100% !important;
        height: 100% !important;
    }
    @media (max-width: 576px) {
        .summary-card .card-body {
            padding: 1.5rem;
        }
        .summary-metric h6 {
            font-size: 0.85rem;
        }
        .summary-metric p {
            font-size: 1.6rem;
        }
    }
</style>

<div class="container-fluid py-5">
    <!-- Welcome Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-banner fade-in">
                <h4>Hello, {{ Auth::user()->profile_name ?? 'Student' }}!</h4>
                <p>Explore your personalized finance dashboard with ease.</p>
            </div>
        </div>
    </div>

    <!-- Payment Summary + Balance Overview (swapped) -->
    <div class="row mb-4 align-items-stretch">
        <!-- Payment Summary Card (now first) -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card summary-card fade-in h-100 d-flex flex-column">
                <div class="card-header bg-gradient-primary">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>
                <div class="card-body flex-grow-1 d-flex flex-column justify-content-center">
                    <div class="summary-metric mb-3 text-center">
                        <h6>Total Paid</h6>
                        <p>₱{{ number_format(Auth::user()->student->total_paid, 2) }}</p>
                    </div>
                    <div class="summary-metric text-center">
                        <h6>Remaining Balance</h6>
                        <p class="text-danger">₱{{ number_format(Auth::user()->student->remaining_balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart (now second) -->
        <div class="col-md-8">
            <section id="balanceSection" class="chart-section fade-in h-100 d-flex flex-column">
                <div class="section-header">
                    <h5>Balance Overview</h5>
                    <div class="section-controls">
                        <select id="balanceFilter">
                            <option value="all">All Time</option>
                            <option value="year">This Year</option>
                            <option value="6months">Last 6 Months</option>
                        </select>
                        <button id="downloadBalanceCsv" title="Download CSV">
                            <i class="fas fa-download"></i> CSV
                        </button>
                    </div>
                </div>
                <div class="skeleton-chart flex-grow-1"></div>
                <div class="chart-wrapper flex-grow-1 position-relative">
                    <canvas id="balanceChart" style="display:none;"></canvas>
                </div>
            </section>
        </div>
    </div>

    <!-- Payment History Section -->
    <div class="row">
        <div class="col-12">
            <section id="historySection" class="chart-section fade-in">
                <div class="section-header">
                    <h5>Payment History</h5>
                    <div class="section-controls">
                        <select id="historyFilter">
                            <option value="all">All Time</option>
                            <option value="year">This Year</option>
                            <option value="6months">Last 6 Months</option>
                        </select>
                        <button id="downloadHistoryCsv" title="Download CSV">
                            <i class="fas fa-download"></i> CSV
                        </button>
                    </div>
                </div>
                <div class="skeleton-chart"></div>
                <div class="chart-wrapper position-relative">
                    <canvas id="paymentHistoryChart" style="display:none;"></canvas>
                    <div id="paymentNoData" class="no-data" style="display:none;">
                        No payment history yet.
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card fade-in">
                <div class="card-header bg-gradient-info">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse (Auth::user()->notifications as $notification)
                            <li class="list-group-item">
                                {{ $notification->data['message'] ?? 'No message.' }}
                                <span class="badge bg-{{
                                    $notification->data['status'] === 'Approved'   ? 'success'
                                    : ($notification->data['status'] === 'Declined' ? 'danger' : 'warning')
                                }}">
                                    {{ $notification->data['status'] }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                You have no notifications.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Utility to show/hide skeleton vs canvas
    const toggleSkeleton = (showSkeleton, sectionId) => {
        document.querySelectorAll(`#${sectionId} .skeleton-chart`)
                .forEach(el => el.style.display = showSkeleton ? 'block' : 'none');
        document.querySelectorAll(`#${sectionId} canvas`)
                .forEach(el => el.style.display = showSkeleton ? 'none' : 'block');
    };

    // --- Balance Overview with destroy-on-reload fix ---
    const balanceCtx = document.getElementById('balanceChart').getContext('2d');
    let balanceChart = null;

    const loadBalanceChart = () => {
        toggleSkeleton(true, 'balanceSection');
        if (balanceChart) balanceChart.destroy();

        balanceChart = new Chart(balanceCtx, {
            type: 'pie',
            data: {
                labels: ['Total Paid', 'Remaining Balance'],
                datasets: [{
                    data: [
                        {{ Auth::user()->student->total_paid }},
                        {{ Auth::user()->student->remaining_balance }}
                    ],
                    backgroundColor: ['#FF9933', '#22BBEA'],
                    borderColor: '#FFF',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 13, family: 'Inter' } }
                    },
                    datalabels: {
                        color: '#fff',
                        font: { size: 12, weight: '600', family: 'Inter' },
                        formatter: v => `₱${v.toLocaleString()}`
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        toggleSkeleton(false, 'balanceSection');
    };

    // --- Payment History ---
    const historyCtx = document.getElementById('paymentHistoryChart').getContext('2d');
    let historyChart = null;
    let cachedData = null;

    const fetchHistory = async () => {
        if (cachedData) return cachedData;
        try {
            const res = await fetch('/student/dashboard/payments');
            if (!res.ok) throw new Error(res.statusText);
            cachedData = await res.json();
            return cachedData;
        } catch {
            document.getElementById('paymentNoData').textContent = 'Failed to load payment history.';
            document.getElementById('paymentNoData').style.display = 'block';
            return [];
        }
    };

    const loadHistoryChart = async () => {
        toggleSkeleton(true, 'historySection');
        const data = await fetchHistory();
        toggleSkeleton(false, 'historySection');

        const noData = !data.length;
        document.getElementById('paymentNoData').style.display = noData ? 'block' : 'none';

        if (historyChart) historyChart.destroy();
        if (noData) return;

        data.sort((a,b)=> new Date(a.year, a.month-1) - new Date(b.year, b.month-1));
        const labels = data.map(i=> new Date(i.year, i.month-1).toLocaleString('en-US',{ month:'short', year:'numeric' }));
        const amounts = data.map(i=> i.total);

        historyChart = new Chart(historyCtx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Approved Payments (₱)',
                    data: amounts,
                    backgroundColor: 'rgba(255, 153, 51, 0.2)',
                    borderColor: '#FF9933',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#FF9933',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 12, family: 'Inter' } } },
                    datalabels: {
                        color: '#2D3748',
                        anchor: 'end',
                        align: 'top',
                        font: { size: 10, family: 'Inter' },
                        formatter: v => `₱${v.toLocaleString()}`
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { 
                            callback: v => `₱${v.toLocaleString()}`, 
                            font: { size: 10, family: 'Inter' } 
                        } 
                    },
                    x: { 
                        ticks: { 
                            maxRotation: 45, 
                            minRotation: 45, 
                            font: { size: 10, family: 'Inter' } 
                        } 
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    };

    // Initial render
    loadBalanceChart();
    loadHistoryChart();

    // Hooks for filters
    document.getElementById('balanceFilter').addEventListener('change', loadBalanceChart);
    document.getElementById('historyFilter').addEventListener('change', loadHistoryChart);

    // CSV download placeholders
    document.getElementById('downloadBalanceCsv').addEventListener('click', () => {
        alert('CSV download functionality not implemented.');
    });
    document.getElementById('downloadHistoryCsv').addEventListener('click', () => {
        alert('CSV download functionality not implemented.');
    });
});
</script>
@endsection