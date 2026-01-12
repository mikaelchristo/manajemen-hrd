<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar',
            ])->withInput($request->only('email'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput($request->only('email'));
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah',
            ])->withInput($request->only('email'));
        }

        // Login manually
        Auth::login($user, $request->filled('remember'));

        // Regenerate session
        $request->session()->regenerate();

        // Store additional session data
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'login_time' => now()->toDateTimeString(),
        ]);

        return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil! Selamat datang ' . $user->name);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Get user name before logout
        $userName = Auth::user()->name ?? 'User';

        // Logout user
        Auth::logout();

        // Clear all session data
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Forget specific session keys
        $request->session()->forget(['user_id', 'user_name', 'user_email', 'user_role', 'login_time']);

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout');
    }
}
