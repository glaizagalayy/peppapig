<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerificationNotification extends Notification
{
    use Queueable;

    private $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Payment Submission')
            ->line('A new payment has been submitted by a student.')
            ->line('Student ID: ' . $this->payment->student_id)
            ->line('Amount: ₱' . number_format($this->payment->amount, 2))
            ->line('Date: ' . $this->payment->payment_date)
            ->line('Reference Number: ' . ($this->payment->reference_number ?? 'N/A'))
            ->action('Verify Payment', url('/finance/payments'))
            ->line('Please review and verify the payment.');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_id' => $this->payment->payment_id, // Ensure this field exists in the $payment object
            'student_id' => $this->payment->student_id,
            'amount' => $this->payment->amount,
            'payment_date' => $this->payment->payment_date,
            'reference_number' => $this->payment->reference_number,
        ];
    }
}
