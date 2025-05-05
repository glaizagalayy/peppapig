<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\CustomNotification;

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
        return view('finance.financePayments', compact('students', 'batches'));
    }

    public function paymentHistory(Request $request)
    {
        $batches = Batch::all();
        $students = Student::with(['payments' => function ($query) {
            $query->where('status', 'Approved');
        }, 'batch'])->get();

        return view('finance.payment-history', compact('batches', 'students'));
    }

    public function getPaymentHistory($studentId)
    {
        // Fetch the student's payments (only approved or added by finance)
        $payments = Payment::where('student_id', $studentId)
            ->whereIn('status', ['Approved', 'Added by Finance'])
            ->get();

        // Fetch the student's details
        $student = Student::with('batch')->where('student_id', $studentId)->first();
        if (!$student) {
            abort(404, 'Student not found');
        }

        // Calculate the total paid (only approved or added by finance)
        $totalPaid = $payments->sum('amount');

        // Fetch the student's total due
        $totalDue = $student->batch->total_due ?? 0;

        // Calculate the remaining balance
        $remainingBalance = max(0, $totalDue - $totalPaid);

        // Fetch all students for the edit form dropdown
        $students = Student::all();

        return view('finance.payment-history', compact('payments', 'totalPaid', 'totalDue', 'remainingBalance', 'student', 'students'));
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string'
        ]);

        // Create the payment record
        $payment = new Payment();
        $payment->student_id = $request->student_id;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->payment_mode = $request->payment_mode;
        $payment->status = 'Added by Finance';
        $payment->verified_by = auth()->id();
        $payment->save();

        // Fetch the student associated with the payment
        $student = Student::where('student_id', $payment->student_id)->first();

        // Generate the PDF receipt
        $pdf = Pdf::loadView('pdf.payment_receipt', ['payment' => $payment, 'student' => $student]);
        $filename = 'receipts/receipt_' . $payment->id . '.pdf';
        $pdf->save(storage_path('app/public/' . $filename)); // Save the file in storage/app/public/receipts

        // Store the notification in the custom table
        CustomNotification::create([
            'user_id' => User::where('login_id', $payment->student_id)->first()->id,
            'type' => 'payment_receipt',
            'title' => 'Payment Receipt Generated',
            'message' => 'Finance has added a payment of ₱' . number_format($payment->amount, 2) . '. Click to view the receipt.',
            'receipt_path' => $filename,
        ]);

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function updateBatch(Request $request)
    {
        $request->validate([
            'batch_year' => 'required|exists:batches,batch_year',
            'total_due' => 'required|numeric|min:0',
        ]);

        $batch = Batch::where('batch_year', $request->batch_year)->first();
        $batch->update(['total_due' => $request->total_due]);

        return redirect()->back()->with('success', 'Batch amount updated successfully!');
    }

    public function notifications()
    {
        $students = Student::with(['payments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        return view('student.notifications');
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:Approved,Declined',
        ]);

        $payment->update([
            'status' => $request->status,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        if ($request->status === 'Declined') {
            $payment->remarks = $request->input('remarks', 'No remarks provided.');
            $payment->save();
        } else {
            // Send receipt notification when payment is approved
            $user = User::where('login_id', $payment->student_id)->first();
            if ($user) {
                $user->notify(new \App\Notifications\PaymentReceiptNotification($payment));
            }
        }

        return redirect()->route('finance.notifications')->with('success', 'Payment has been ' . strtolower($request->status) . '.');
    }

    public function dashboard()
    {
        $batches = Batch::all();
        $students = Student::all();
        return view('finance.financeDashboard', compact('batches', 'students'));
    }

    public function getMonthlyPayments(Request $request)
    {
        $query = Payment::query();

        if ($request->has('batch_year')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('batch_year', $request->batch_year);
            });
        }

        if ($request->has('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $payments = $query->selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total')
            ->groupByRaw('YEAR(payment_date), MONTH(payment_date)')
            ->orderByRaw('YEAR(payment_date), MONTH(payment_date)')
            ->get();

        return response()->json($payments);
    }

    public function getYearlyPayments(Request $request)
    {
        $batchYear = $request->input('batch_year');

        $query = Payment::selectRaw('YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'Approved')
            ->groupByRaw('YEAR(payment_date)')
            ->orderByRaw('YEAR(payment_date)');

        if ($batchYear) {
            $query->whereHas('student.batch', function ($q) use ($batchYear) {
                $q->where('batch_year', $batchYear);
            });
        }

        $payments = $query->get();

        return response()->json($payments);
    }

    public function getSummaryData()
    {
        $monthlyCollected = Payment::whereIn('status', ['Approved', 'Added by Finance'])
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $yearlyCollected = Payment::whereIn('status', ['Approved', 'Added by Finance'])
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $overallCollected = Payment::whereIn('status', ['Approved', 'Added by Finance'])
            ->sum('amount');

        return response()->json([
            'monthly_collected' => $monthlyCollected,
            'yearly_collected' => $yearlyCollected,
            'overall_collected' => $overallCollected,
        ]);
    }

    public function editPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'student_id' => 'required|exists:students,student_id'
        ]);

        // Update the payment
        $payment->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_mode' => $request->payment_mode,
            'reference_number' => $request->reference_number,
            'student_id' => $request->student_id
        ]);

        return redirect()->back()->with('success', 'Payment updated successfully.');
    }

    public function deletePayment(Payment $payment)
    {
        // Only allow deletion of payments added by finance or approved payments
        if (!in_array($payment->status, ['Added by Finance', 'Approved'])) {
            return redirect()->back()->with('error', 'Only approved payments or payments added by finance can be deleted.');
        }

        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }

    public function generateReport(Request $request)
    {
        try {
            \Log::info('Generating report with input:', $request->all());

            $reportType = $request->input('report_type');
            $batchYear = $request->input('batch_year');
            $year = $request->input('year');

            $data = [];
            switch ($reportType) {
                case 'total_paid_per_student':
                    $data = Student::with('batch')
                        ->leftJoin('payments', 'students.student_id', '=', 'payments.student_id')
                        ->selectRaw('students.student_id, students.first_name, students.last_name, students.batch_year, COALESCE(SUM(payments.amount), 0) as total_paid')
                        ->groupBy('students.student_id', 'students.first_name', 'students.last_name', 'students.batch_year')
                        ->get();
                    $description = 'This report shows the total payments made by each student, including those who have not made any payments.';
                    break;

                case 'total_paid_per_batch':
                    $data = Batch::withSum(['payments' => function ($query) {
                        $query->whereIn('status', ['Approved', 'Added by Finance']);
                    }], 'amount')->get();
                    $description = 'This report shows the total payments made for each batch.';
                    break;

                case 'total_paid_per_year':
                    $data = Payment::selectRaw('YEAR(payment_date) as year, SUM(amount) as total_paid')
                        ->groupByRaw('YEAR(payment_date)')
                        ->get();
                    $description = 'This report shows the total payments made for each year.';
                    break;

                case 'total_paid_per_batch_year':
                    $data = Payment::with('student.batch')
                        ->when($batchYear, fn($query) => $query->whereHas('student', fn($q) => $q->where('batch_year', $batchYear)))
                        ->selectRaw('batch_year, YEAR(payment_date) as year, SUM(amount) as total_paid')
                        ->groupByRaw('batch_year, YEAR(payment_date)')
                        ->get();
                    $description = 'This report shows the total payments made for each batch by year.';
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Invalid report type.']);
            }

            \Log::info('Report data:', $data->toArray());

            $html = View::make('finance.partials.report', compact('data', 'reportType'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while generating the report.'], 500);
        }
    }

    public function downloadReport(Request $request)
    {
        $reportType = $request->input('report_type');
        $batchYear = $request->input('batch_year');
        $year = $request->input('year');

        $data = [];
        $description = '';
        switch ($reportType) {
            case 'total_paid_per_student':
                $data = Student::with('batch')
                    ->leftJoin('payments', 'students.student_id', '=', 'payments.student_id')
                    ->selectRaw('students.student_id, students.first_name, students.last_name, students.batch_year, COALESCE(SUM(payments.amount), 0) as total_paid')
                    ->groupBy('students.student_id', 'students.first_name', 'students.last_name', 'students.batch_year')
                    ->get();
                $description = 'This report shows the total payments made by each student, including those who have not made any payments.';
                break;

            case 'total_paid_per_batch':
                $data = Batch::withSum(['payments' => function ($query) {
                    $query->whereIn('status', ['Approved', 'Added by Finance']);
                }], 'amount')->get();
                $description = 'This report shows the total payments made for each batch.';
                break;

            case 'total_paid_per_year':
                $data = Payment::selectRaw('YEAR(payment_date) as year, SUM(amount) as total_paid')
                    ->groupByRaw('YEAR(payment_date)')
                    ->get();
                $description = 'This report shows the total payments made for each year.';
                break;

            case 'total_paid_per_batch_year':
                $data = Payment::with('student.batch')
                    ->when($batchYear, fn($query) => $query->whereHas('student', fn($q) => $q->where('batch_year', $batchYear)))
                    ->selectRaw('batch_year, YEAR(payment_date) as year, SUM(amount) as total_paid')
                    ->groupByRaw('batch_year, YEAR(payment_date)')
                    ->get();
                $description = 'This report shows the total payments made for each batch by year.';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }

        // Generate the PDF
        $pdf = Pdf::loadView('finance.partials.report-pdf', compact('data', 'reportType', 'description'));
        return $pdf->download('finance_report.pdf');
    }

    public function reports()
    {
        $batches = Batch::all();
        return view('finance.financeReports', compact('batches'));
    }
}