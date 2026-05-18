<?php
namespace App\Http\Controllers\Kacab;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\Pica;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dealerId = $user->dealer_id;

        $totalSessions = GenbaSession::where('dealer_id', $dealerId)->where('status', 'submitted')->count();
        $totalDraft = GenbaSession::where('dealer_id', $dealerId)->where('status', 'draft')->count();
        $totalPicaOpen = Pica::whereHas('session', fn($q) => $q->where('dealer_id', $dealerId))
            ->where('status', 'open')->count();

        $recentSessions = GenbaSession::with(['role', 'user', 'answers'])
            ->where('dealer_id', $dealerId)
            ->where('status', 'submitted')
            ->latest()
            ->take(10)
            ->get();

        return view('kacab.dashboard', compact(
            'totalSessions', 'totalDraft', 'totalPicaOpen', 'recentSessions'
        ));
    }
}