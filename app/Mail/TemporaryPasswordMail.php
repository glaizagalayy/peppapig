<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class TemporaryPasswordMail extends Mailable
{
    public $tempPassword;
    public $loginId;

    public function __construct($tempPassword, $loginId)
    {
        $this->tempPassword = $tempPassword;
        $this->loginId = $loginId;
    }

    public function build()
    {
        return $this->subject('Your Temporary Password')
                    ->view('emails.temporaryPassword')
                    ->with([
                        'tempPassword' => $this->tempPassword,
                        'loginId' => $this->loginId,
                    ]);
    }
}