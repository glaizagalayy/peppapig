<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // Check if the user is deactivated
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['login_id' => 'Your account has been deactivated.']);
        }

        // Check if the user needs to reset their password
        if ($user->password_reset_required) {
            // Verify if the user is logging in with their temporary password
            if (Hash::check($request->password, $user->password)) {
                return redirect()->route('password.change');
            }
        }

        // Redirect based on the user's role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'finance') {
            return redirect()->route('finance.financeDashboard');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.studentDashboard');
        }

        return redirect('/');
    }
}
