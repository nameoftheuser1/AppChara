<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ];

        if (RateLimiter::tooManyAttempts('login:' . $request->ip(), 5)) {
            return back()->withErrors(['email' => 'Too many login attempts. Please try again later.']);
        }
        RateLimiter::hit('login:' . $request->ip());

        // Validate input
        $fields = $request->validate($rules);

        // Attempt to log in
        if (Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']], $request->remember)) {
            $user = Auth::user();

            // Check the user role
            if ($user->role === 'user') {
                return redirect()->route('profile');
            }

            // Redirect to dashboard for other roles
            return redirect()->intended('/dashboard');
        }

        // If credentials don't match, return error
        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ]);
    }


    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('auth.login')->with('success', 'Registration successful. Please verify your email before logging in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
