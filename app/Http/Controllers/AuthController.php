<?php

namespace App\Http\Controllers;

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

        // Check for admin login first (using manual hardcoded check)
        if (Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']], $request->remember)) {
            return redirect()->intended('/');
        }


        // Now attempt regular user login using Laravel's Auth::attempt
        if (Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']], $request->remember)) {
            // If login is successful, redirect to intended page
            return redirect()->intended('/'); // You can adjust the redirect URL here
        }

        // If credentials don't match, return error
        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ]);
    }
}
