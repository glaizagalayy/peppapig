<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
</head>
<body>
    <p>Dear {{ $payment->student->first_name }},</p>
    <p>Your payment has been successfully received. Thank you!</p>
    <p>If you have any questions, feel free to contact us.</p>
    <p>Best regards,</p>
    <p>Finance Team</p>
</body>
</html>