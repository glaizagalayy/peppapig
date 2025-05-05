<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class NotificationController extends Controller
{
    public function downloadReceipt($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);

        if (!isset($notification->data['receipt_path'])) {
            abort(404, 'Receipt not found.');
        }

        $receiptPath = storage_path('app/public/' . $notification->data['receipt_path']);

        if (!file_exists($receiptPath)) {
            abort(404, 'Receipt file not found.');
        }

        return response()->download($receiptPath, 'receipt.pdf');
    }

    public function generateReceipt($payment)
    {
        $pdf = Pdf::loadView('pdf.payment_receipt', ['payment' => $payment]);
        $filename = 'receipts/receipt_' . $payment->id . '.pdf';
        $pdf->save(storage_path('app/public/' . $filename));

        // Notify the student with the file path
        $user->notify(new \App\Notifications\PaymentReceiptNotification($payment, $filename));
    }
}
