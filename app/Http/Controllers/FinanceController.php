<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Notifications\PaymentSubmissionNotification;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReceiptMail;

class FinanceController extends Controller
{
    public function profile()
    {
        $finance = Auth::user()->finance;
        return view('finance.profile', compact('finance'));
    }

    public function managePayments()
    {
        $students = Student::with('payments', 'batch')->get(); // Include the 'batch' relationship
        $batches = Batch::all(); // Fetch all available batches
        return view('finance.financePayments', compact('students', 'batches'));
    }

    public function index(Request $request)
    {
        $batchYear = $request->query('batch_year');
        $students = Student::with('payments', 'batch')
            ->when($batchYear, function ($query, $batchYear) {
                $query->where('batch_year', '=', $batchYear);
            })
            ->get();

        dd($batchYear, $students); // Debug the batch year and filtered students

        $batches = Batch::all();

        return view('finance.financePayments', compact('students', 'batches'));
    }

    public function getPaymentHistory($studentId)
    {
        $student = \App\Models\Student::with('payments')->where('student_id', $studentId)->firstOrFail();

        return view('finance.student-payment-history', [
            'student' => $student,
            'payments' => $student->payments,
        ]);
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
        ]);

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'payment_date' => now(),
            'payment_mode' => $request->payment_mode,
        ]);

        $this->sendReceipt($payment);

        return redirect()->back()->with('success', 'Payment added and receipt sent!');
    }

    public function verifyPayment(Payment $payment)
    {
        $payment->update(['verified' => true]);

        $this->sendReceipt($payment);

        return redirect()->back()->with('success', 'Payment verified and receipt sent!');
    }

    public function updateBatch(Request $request)
    {
        $request->validate([
            'batch_year' => 'required|string|unique:batches,batch_year',
            'total_due' => 'required|numeric|min:0',
        ]);

        \App\Models\Batch::updateOrCreate(
            ['batch_year' => $request->batch_year],
            ['total_due' => $request->total_due]
        );

        return redirect()->back()->with('success', 'Batch payment amount updated successfully.');
    }

    private function sendReceipt($payment)
    {
        $student = $payment->student;

        // Generate PDF
        $pdf = Pdf::loadView('receipts.receipts', compact('payment', 'student'))
                  ->setPaper('a4', 'landscape');

        // Send email with receipt
        Mail::to($student->email)->send(new ReceiptMail($payment, $pdf->output()));
    }
}