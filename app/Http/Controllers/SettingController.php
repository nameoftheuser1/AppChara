<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request without the current password field
        $request->validate([
            'new_password' => ['required', 'string', 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            // Update the user's password
            Auth::user()->update([
                'password' => Hash::make($request->new_password),
            ]);

            // Flash success message to session
            return redirect()->route('settings.index')->with('success', 'Password changed successfully!');
        }

        // Flash error message to session
        return redirect()->route('login')->withErrors(['error' => 'You must be logged in to change your password.']);
    }
}
