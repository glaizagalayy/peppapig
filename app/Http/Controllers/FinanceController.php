<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

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
}