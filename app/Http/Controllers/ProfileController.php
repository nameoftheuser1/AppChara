<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function indexProfile(Request $request)
    {
        $userId = Auth::id();

        // Retrieve the month filter from the request, defaulting to the current month
        $month = $request->query('month', now()->month);

        $reservations = Reservation::where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->paginate(10);

        // Pass the reservations and current month to the view
        return view('customer.profile', compact('reservations', 'month'));
    }
}
