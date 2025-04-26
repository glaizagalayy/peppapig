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
        $students = Student::with('payments', 'batch')->get();
        $batches = Batch::all();
        $batchYear = request('batch_year'); // Get the batch year from the request
        
        return view('finance.financePayments', compact('students', 'batches', 'batchYear'));
    }

    public function index(Request $request)
    {
        $batchYear = $request->query('batch_year');
        
        // Debug the incoming batch year
        \Log::info('Batch Year from request: ' . $batchYear);
        
        $query = Student::with('payments', 'batch');
        
        if ($batchYear) {
            \Log::info('Applying batch filter for year: ' . $batchYear);
            $query->where('batch_year', $batchYear);
        }
        
        $students = $query->orderBy('last_name')
                         ->orderBy('first_name')
                         ->get();
                         
        // Debug the results
        \Log::info('Number of students found: ' . $students->count());
        \Log::info('First student batch year: ' . ($students->first() ? $students->first()->batch_year : 'No students'));
        
        $batches = Batch::all();
        \Log::info('Available batches: ' . $batches->pluck('batch_year')->implode(', '));

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
            'batch_year' => 'required|string',
            'total_due' => 'required|numeric|min:0',
        ]);

        $batch = Batch::where('batch_year', $request->batch_year)->first();
        
        if ($batch) {
            $batch->update(['total_due' => $request->total_due]);
        } else {
            Batch::create([
                'batch_year' => $request->batch_year,
                'total_due' => $request->total_due
            ]);
        }

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

    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $student = $payment->student;

        $pdf = Pdf::loadView('receipts.receipts', compact('payment', 'student'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('acknowledgement_receipt.pdf');
    }

    public function filterStudentsByBatch(Request $request)
    {
        $batchYear = $request->input('batch_year');
        
        // Get all available batches for the dropdown
        $batches = Batch::all();
        
        // Query students with their payments and batch information
        $query = Student::with('payments', 'batch');
        
        // Apply batch filter if a specific batch is selected
        if ($batchYear) {
            $query->where('batch_year', $batchYear);
        }
        
        // Get the filtered students ordered by last name and first name
        $students = $query->orderBy('last_name')
                         ->orderBy('first_name')
                         ->get();
        
        // Return the view with the filtered data
        return view('finance.financePayments', compact('students', 'batches', 'batchYear'));
    }
}