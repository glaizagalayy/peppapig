<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Report</title>
    <!-- Professional Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #2c3e50;
            --primary: #2980b9;
            --bg-light: #ecf0f1;
            --bg-card: #fff;
            --text-dark: #2c3e50;
            --text-muted: #7f8c8d;
            --border: #dfe6e9;
        }
        *, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            line-height:1.5;
        }
        .container {
            max-width: 900px;
            margin: 1rem auto;
            padding: 0 0.5rem;
        }
        /* Company header */
        .company-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }
        .company-header .logo {
            height: 60px;
        }
        .company-details {
            text-align: right;
            font-size: 0.85rem;
            color: black
        }
        .company-details p {
            margin: 0.1rem 0;
        }
        .report-header {
            background: rgb(94, 166, 195);
            color: #111111;
            text-align: center;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .report-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        .report-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .report-intro {
            background: var(--bg-card);
            border-left: 4px solid var(--primary);
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .report-intro p {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .table-wrapper {
            overflow-x: auto;
            background: var(--bg-card);
            border-radius: 0.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        table {
            width:100%;
            border-collapse: collapse;
            min-width: 500px;
        }
        thead th {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid var(--border);
            text-align:left;
        }
        tbody td {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
        }
        tbody tr:nth-child(even) { background: #f7f9fa; }
        tbody tr:hover { background: var(--primary); color:#fff; transition:0.2s; }
        .report-footer {
            text-align: center;
            margin: 1rem 0;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        @media (max-width: 600px) {
            .company-header { flex-direction: column; text-align: center; }
            .company-details { text-align: center; margin-top: 0.5rem; }
            .report-header h1 { font-size: 1.5rem; }
            thead th, tbody td { padding: 0.4rem 0.5rem; font-size: 0.75rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Company Info -->
        <div class="company-header">
          <img src="{{ asset('photos/pnlogo.png') }}" alt="Company Logo" class="logo">
            <div class="company-details">
                <p>ACME Financial Services</p>
                <p>123 Business Blvd, Manila, Philippines</p>
                <p>Tel: +63 2 1234 5678 | Email: passerellesnumeriquesphilippinese.com</p>
            </div>
        </div>
        <!-- Report Title -->
        <section class="report-header">
            <h1>Finance Report</h1>
            <p>{{ ucfirst(str_replace('_',' ',$reportType)) }}</p>
        </section>
        <!-- Intro -->
        <section class="report-intro">
            <p>Overview of requested financial data for concise insights and swift decision-making.</p>
        </section>
        <!-- Data Table -->
        <section class="table-wrapper">
            <table>
                <thead>
                    @switch($reportType)
                        @case('total_paid_per_student')
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Paid (₱)</th>
                            </tr>
                            @break
                        @case('total_paid_per_batch')
                            <tr>
                                <th>Batch Year</th>
                                <th>Total Paid (₱)</th>
                            </tr>
                            @break
                        @case('total_paid_per_year')
                            <tr>
                                <th>Year</th>
                                <th>Total Paid (₱)</th>
                            </tr>
                            @break
                        @case('total_paid_per_batch_year')
                            <tr>
                                <th>Batch Year</th>
                                <th>Year</th>
                                <th>Total Paid (₱)</th>
                            </tr>
                            @break
                        @default
                            <tr><th>No Data</th></tr>
                    @endswitch
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        @switch($reportType)
                            @case('total_paid_per_student')
                                <td>{{ $row->student_id }}</td>
                                <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                                <td>{{ number_format($row->total_paid,2) }}</td>
                                @break
                            @case('total_paid_per_batch')
                                <td>{{ $row->batch_year }}</td>
                                <td>{{ number_format($row->total_paid,2) }}</td>
                                @break
                            @case('total_paid_per_year')
                                <td>{{ $row->year }}</td>
                                <td>{{ number_format($row->total_paid,2) }}</td>
                                @break
                            @case('total_paid_per_batch_year')
                                <td>{{ $row->batch_year }}</td>
                                <td>{{ $row->year }}</td>
                                <td>{{ number_format($row->total_paid,2) }}</td>
                                @break
                            @default
                                <td colspan="3">No records available</td>
                        @endswitch
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <!-- Footer -->
        <section class="report-footer">
            <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        </section>
    </div>
</body>
</html>