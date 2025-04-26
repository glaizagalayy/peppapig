<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PaymentSubmissionNotification;
use App\Notifications\PaymentVerificationNotification;

use App\Models\User;

class StudentController extends Controller
{
    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'));
    }

    public function paymentForm()
    {
        return view('student.paymentForm');
    }

    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'reference_number' => 'nullable|string|max:255',
        ]);

        $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $payment = Payment::create([
            'student_id' => Auth::user()->login_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_mode' => $request->payment_mode ?? 'N/A',
            'reference_number' => $request->reference_number,
            'payment_proof' => $filePath,
            'status' => 'Pending',
        ]);

        // Notify the finance team
        $financeUsers = User::where('role', 'finance')->get();
        foreach ($financeUsers as $financeUser) {
            $financeUser->notify(new PaymentVerificationNotification($payment));
        }

        return redirect()->route('student.paymentForm')->with('success', 'Payment proof submitted successfully!');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return view('student.notifications', compact('notifications'));
    }

    public function dashboard()
    {
        $student = Auth::user()->student()->with('payments', 'batch')->first();
        return view('student.studentDashboard', compact('student'));
    }
}