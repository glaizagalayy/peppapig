<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Payment Receipt</title>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 20mm; /* Adjust as needed */
            }

            body {
                margin: 0;
            }

            .receipt-container {
                width: 100%;
                height: 100%;
                box-sizing: border-box;
                page-break-after: avoid;
            }
        }

        body {
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
        }

        .receipt-container {
            width: 100%;
            height: 100%;
            padding: 20px 40px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            text-align: left;
        }

        .logo-container {
            flex-shrink: 0;
            margin-right: 15px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .company-details {
            text-align: left;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .company-address {
            font-size: 14px;
            margin: 5px 0 0;
        }

        .receipt-title {
            color: #0066cc;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .receipt-date {
            text-align: right;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .receipt-content {
            margin: 20px 0;
            font-size: 14px;
        }

        .details {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .signature-section {
            margin-top: 30px;
            font-weight: bold;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('photos/pnlogo.png') }}" alt="PN Logo" class="logo">
            </div>
            <div class="company-details">
                <div class="company-name">PASSERELLES NUMERIQUES PHILIPPINES FOUNDATION, INC.</div>
                <div class="company-address">The Bird Building, New Era St., Barangay Luz, Cebu City, Philippines</div>
            </div>
        </div>

        <div class="receipt-title">ACKNOWLEDGEMENT RECEIPT</div>

        <div class="receipt-date">
            Date: <strong style="text-decoration:underline;">{{ date('F j, Y') }}</strong>
        </div>

        <div class="receipt-content">
            <p>To Whom It May Concern:</p>
            <p style="text-indent:30px;">
                This is to acknowledge receipt from <strong style="text-decoration:underline;">{{ $student->first_name }} {{ $student->last_name }}</strong> the amount of <strong style="text-decoration:underline;">PhP {{ number_format($payment->amount, 2) }}</strong> as payment for <strong style="text-decoration:underline;">Parents' Counterpart.</strong>
            </p>
        </div>

        <div class="details">
            <p><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
            <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
            <p><strong>Payment Mode:</strong> {{ ucfirst($payment->payment_mode) }}</p>
            <p><strong>Amount Paid:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
        </div>

        <div class="signature-section">
            <p>This information has been duly verified by the finance team.</p>
        </div>

        <div class="footer">
            This is an electronically generated receipt. No signature is required.
        </div>
    </div>
</body>
</html>
