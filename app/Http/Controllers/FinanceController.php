<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Notifications\PaymentSubmissionNotification;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function profile()
    {
        $finance = Auth::user()->finance;
        return view('finance.profile', compact('finance'));
    }

    public function managePayments()
    {
        $students = Student::with('payments')->get();
        return view('finance.financePayments', compact('students'));
    }

    public function getPaymentHistory($studentId)
    {

        $payments = Payment::where('student_id', $studentId)->get();
        return response()->json($payments);
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
        ]);

        // Exclude `_token` from the request data
        Payment::create($request->except('_token'));

        return redirect()->route('finance.financePayments')->with('success', 'Payment added successfully!');
    }

    public function verifyPayment(Request $request, $notificationId)
    {
        $request->validate([
            'status' => 'required|in:Approved,Declined',
        ]);

        // Find the notification
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $paymentData = $notification->data;

        // Update the payment status using the correct primary key
        $payment = Payment::where('payment_id', $paymentData['payment_id'])->firstOrFail();
        $payment->update(['status' => $request->status]);

        // Notify the student about the status update
        $student = $payment->student;
        $student->user->notify(new PaymentSubmissionNotification($payment));

        // Mark the notification as read
        $notification->markAsRead();

        return redirect()->route('finance.notifications')->with('success', 'Payment status updated successfully!');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return view('finance.notifications', compact('notifications'));
    }
}