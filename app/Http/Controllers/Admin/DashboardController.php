<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\GenbaSession;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $totalDealers = Dealer::count();
    $totalSessions = GenbaSession::count();
    $submittedSessions = GenbaSession::where('status', 'submitted')->count();

    // Group by dealer
    $recentSessions = GenbaSession::with(['dealer', 'role', 'user', 'answers'])
        ->where('status', 'submitted')
        ->latest()
        ->get()
        ->groupBy('dealer_id');

    return view('admin.dashboard', compact(
        'totalDealers', 'totalSessions',
        'submittedSessions', 'recentSessions'
    ));
}
}