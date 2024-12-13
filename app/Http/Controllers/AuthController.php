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
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => ['required', 'string'],
        ];

        if (RateLimiter::tooManyAttempts('login:' . $request->ip(), 5)) {
            return back()->withErrors(['email' => 'Too many login attempts. Please try again later.'])->withInput();
        }
        RateLimiter::hit('login:' . $request->ip());

        // Validate input
        $fields = $request->validate($rules);

        // Check if email exists
        $user = \App\Models\User::where('email', $fields['email'])->first();

        if ($user) {
            // Attempt login
            if (Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']], $request->remember)) {
                if ($user->role === 'user') {
                    return redirect()->route('profile');
                }

                return redirect()->intended('/dashboard');
            }

            // Password is incorrect
            return back()->withErrors(['password' => 'The password is incorrect.'])->withInput();
        }

        // Email does not exist
        return back()->withErrors(['email' => 'The email address does not exist in our records.'])->withInput();
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
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,}$/',
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the user's password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Delete the user account
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}
