<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\GenbaSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sessions = GenbaSession::with(['role', 'answers'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dealer.dashboard', compact('sessions', 'user'));
    }
}