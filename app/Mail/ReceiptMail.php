<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $pdf;

    public function __construct($payment, $pdf)
    {
        $this->payment = $payment;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Your Payment Receipt')
                    ->view('emails.payment-receipt') // Email content view
                    ->attachData($this->pdf, 'payment_receipt.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}