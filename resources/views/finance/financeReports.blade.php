@extends('layouts.finance')

@section('title', 'Reports')
@section('page-title', 'Reports Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Finance Reports</h5>
                </div>
                <div class="card-body">
                    <form id="reportForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="batchYear" class="form-label fw-semibold">Batch Year</label>
                                <select id="batchYear" name="batch_year" class="form-select">
                                    <option value="">All Batches</option>
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->batch_year }}">{{ $batch->batch_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="reportType" class="form-label fw-semibold">Report Type</label>
                                <select id="reportType" name="report_type" class="form-select">
                                    <option value="total_paid_per_student">Total Paid Per Student</option>
                                    <option value="total_paid_per_batch">Total Paid Per Batch</option>
                                    <option value="total_paid_per_year">Total Paid Per Year</option>
                                    <option value="total_paid_per_batch_year">Total Paid Per Batch by Year</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label fw-semibold">Year</label>
                                <input type="number" id="year" name="year" class="form-control" placeholder="Enter year (optional)">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary" id="generateReportBtn">Generate Report</button>
                            <button type="button" class="btn btn-success" id="downloadReportBtn">Download Report</button>
                        </div>
                    </form>
                    <hr>
                    <div id="reportContainer" class="mt-4">
                        <h5 class="text-center text-muted">No report generated yet.</h5>
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
        const generateReportBtn = document.getElementById('generateReportBtn');
        const downloadReportBtn = document.getElementById('downloadReportBtn');
        const reportContainer = document.getElementById('reportContainer');

        // Generate Report
        generateReportBtn.addEventListener('click', () => {
            const batchYear = document.getElementById('batchYear').value;
            const reportType = document.getElementById('reportType').value;
            const year = document.getElementById('year').value;

            const params = {
                batch_year: batchYear,
                report_type: reportType,
                year: year
            };

            fetch(`/finance/reports/generate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(params)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    reportContainer.innerHTML = data.html;
                } else {
                    reportContainer.innerHTML = `<h5 class="text-center text-danger">${data.message}</h5>`;
                }
            })
            .catch(error => {
                console.error('Error generating report:', error);
                reportContainer.innerHTML = `<h5 class="text-center text-danger">An error occurred while generating the report.</h5>`;
            });
        });

        // Download Report
        downloadReportBtn.addEventListener('click', () => {
            const batchYear = document.getElementById('batchYear').value;
            const reportType = document.getElementById('reportType').value;
            const year = document.getElementById('year').value;

            const params = new URLSearchParams({
                batch_year: batchYear,
                report_type: reportType,
                year: year
            });

            window.location.href = `/finance/reports/download?${params.toString()}`;
        });
    });
</script>
@endsection