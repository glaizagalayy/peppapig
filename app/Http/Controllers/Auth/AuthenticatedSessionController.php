<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Call the authenticated method for role-based redirection
        return $this->authenticated($request, Auth::user());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle post-authentication redirection based on user role.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('student.studentDashboard');
        } elseif ($user->role === 'finance') {
            return redirect()->route('finance.financeDashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Optional for admin
        }

        return redirect('/'); // Default fallback
    }
}
