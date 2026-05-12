<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Password is required',
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            
            // Check if user is admin
            if ($user->is_admin ?? false) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }

            // Not an admin, logout
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Unauthorized access. Admin privileges required.']);
        }

        return redirect()->route('admin.login')
            ->withErrors(['email' => 'Invalid credentials. Please try again.'])
            ->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
