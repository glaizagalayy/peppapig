<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PaymentSubmissionNotification;
use App\Notifications\PaymentVerificationNotification;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CustomNotification;

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
            'reference_number' => 'required|string|max:255',
            'payment_mode' => 'required|string|in:GCash,Bank Transfer,Cash', // Add validation
        ]);

        $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $payment = Payment::create([
            'student_id' => Auth::user()->student->student_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_proof' => $filePath,
            'reference_number' => $request->reference_number,
            'payment_mode' => $request->payment_mode, // Add this
            'status' => 'Pending',
        ]);

        // Notify finance users
        $financeUsers = User::where('role', 'finance')->get();
        foreach ($financeUsers as $financeUser) {
            $financeUser->notify(new PaymentVerificationNotification($payment));
        }

        return redirect()->route('student.paymentForm')->with('success', 'Payment proof submitted successfully!');
    }

    public function notifications()
    {
        $notifications = CustomNotification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.notifications', compact('notifications'));
    }

    public function paymentHistory()
    {
        $payments = Auth::user()->student->payments()->orderBy('payment_date', 'desc')->get();
        return view('student.studentPayments', compact('payments'));
    }

    public function dashboard()
    {
        $student = Auth::user()->student; // Assuming the logged-in user has a 'student' relationship
        return view('student.studentDashboard', compact('student'));
    }

    public function getDashboardPayments()
    {
        $payments = Auth::user()->student->payments()
            ->whereIn('status', ['Approved', 'Added by Finance'])
            ->orderBy('payment_date')
            ->get()
            ->groupBy(function($payment) {
                return $payment->payment_date->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'year' => $group->first()->payment_date->format('Y'),
                    'month' => $group->first()->payment_date->format('m'),
                    'total' => $group->sum('amount')
                ];
            })
            ->values();

        return response()->json($payments);
    }
}