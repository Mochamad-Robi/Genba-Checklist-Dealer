<?php
namespace App\Http\Controllers\Auditor;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\GenbaAnswer;
use App\Models\Dealer;
use App\Models\Role;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dealers = Dealer::where('is_active', true)->get();
        $roles = $user->roles()->where('is_active', true)->orderBy('order')->get();

        $dealerId = request('dealer_id');
        $roleId = request('role_id');
        $tanggal = request('tanggal');

        $sessions = collect();
        $totalPaham = 0;
        $totalTidakPaham = 0;
        $totalTidakDipakai = 0;
        $hasFilter = $dealerId || $roleId || $tanggal;

        if ($hasFilter) {
            $sessions = GenbaSession::with(['dealer', 'role', 'answers'])
                ->where('user_id', $user->id)
                ->where('status', 'submitted')
                ->when($dealerId, fn($q) => $q->where('dealer_id', $dealerId))
                ->when($roleId, fn($q) => $q->where('role_id', $roleId))
                ->when($tanggal, fn($q) => $q->whereDate('submitted_at', $tanggal))
                ->latest()
                ->get();

            foreach ($sessions as $session) {
                $totalPaham += $session->answers->where('indicator', '1')->count();
                $totalTidakPaham += $session->answers->where('indicator', '2')->count();
                $totalTidakDipakai += $session->answers->where('indicator', '3')->count();
            }
        }

        $recentSessions = GenbaSession::with(['dealer', 'role'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('auditor.dashboard', compact(
            'dealers',
            'roles',
            'sessions',
            'totalPaham',
            'totalTidakPaham',
            'totalTidakDipakai',
            'hasFilter',
            'dealerId',
            'roleId',
            'tanggal',
            'recentSessions'
        ));
    }
}