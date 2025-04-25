<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title>Payment Receipt</title>
    <style>
        body {
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 18px; /* Set a default font size */
        }

        .receipt-container {
            width: 11in; /* Landscape width for A4 paper */
            height: 8.5in; /* Landscape height for A4 paper */
            margin: 0 auto; /* Center the receipt */
            padding: 20px;
            box-sizing: border-box; /* Ensure padding is included in the dimensions */
        }

        .header {
            display: flex;
            align-items: center; /* Vertically align the logo and text */
            justify-content: center; /* Center the entire header horizontally */
            margin-bottom: 20px;
            text-align: left; /* Align text to the left */
        }

        .logo-container {
            flex-shrink: 0; /* Prevent the logo from shrinking */
            margin-right: 15px; /* Add spacing between the logo and the text */
        }

        .logo {
            width: 80px; /* Adjust the width as needed */
            height: auto; /* Maintain aspect ratio */
        }

        .company-details {
            text-align: left; /* Align the company name and address to the left */
        }

        .company-name {
            font-size: 20px; /* Adjust font size */
            font-weight: bold;
            margin: 0;
        }

        .company-address {
            font-size: 14px; /* Adjust font size */
            color: solid black;
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
            padding-right: 80px; /* Add right padding to the date */
        }

        .receipt-content {
            margin: 20px 0;
            font-size: 14px;
        }

        .details {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }

        .signature-section {
            margin-top: 30px;
            font-weight: bold; /* Make the text bold */
            font-size: 12px; /* Make the text smaller */
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

        <div class="receipt-date" >
            Date:<strong  style="text-decoration:underline;"> {{ date('F j, Y') }}</strong>
        </div>

        <div class="receipt-content">
            <p>To Whom It May Concern:</p>
            
                <p style="text-indent:30px;">This is to acknowledge receipt from <strong style="text-decoration:underline;"> {{ $student->first_name }} {{ $student->last_name }} </strong>  the amount of <strong style="text-decoration:underline;"> PhP {{ number_format($payment->amount, 2) }}</strong> as payment for <strong  style="text-decoration:underline;" >Parents' Counterpart.</strong></p>

        <div class="details">
            <p><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
            <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
            <p><strong>Payment Mode:</strong> {{ ucfirst($payment->payment_mode) }}</p>
            <p><strong>Amount Paid:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
        </div>

        <div class="signature-section">
            <div>
                <p>This information has been duly verified by the finance team.</p>
            </div>
        <div class="footer" style="font-weight:normal;">
            This is an electronically generated receipt. No signature is required.
        </div>
    </div>
</body>
</html>