<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSubmissionNotification extends Notification
{
    use Queueable;

    private $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Submission Confirmation')
            ->line('Your payment has been submitted successfully.')
            ->line('Amount: ₱' . number_format($this->payment->amount, 2))
            ->line('Date: ' . $this->payment->payment_date)
            ->line('Reference Number: ' . ($this->payment->reference_number ?? 'N/A'))
            ->line('Status: ' . $this->payment->status)
            ->action('View Payment History', url('/student/payments'))
            ->line('Thank you for your payment!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'amount' => $this->payment->amount,
            'payment_date' => $this->payment->payment_date,
            'reference_number' => $this->payment->reference_number,
            'status' => $this->payment->status,
        ];
    }
}
