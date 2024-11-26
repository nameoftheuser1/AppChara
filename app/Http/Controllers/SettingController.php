<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $currentEmail = DB::table('settings')->where('key', 'email')->value('value');
        return view('settings.index', compact('currentEmail'));
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

    public function changeEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:settings,value',
        ]);

        // Find the 'email' setting and update it
        $emailSetting = Setting::where('key', 'email')->first();
        if ($emailSetting) {
            $emailSetting->value = $request->email;
            $emailSetting->save();
        } else {
            // If the email setting does not exist, create a new one
            Setting::create([
                'key' => 'email',
                'value' => $request->email,
            ]);
        }

        return redirect()->back()->with('success', 'Email updated successfully.');
    }
}
