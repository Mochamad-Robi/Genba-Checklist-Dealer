<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Role;
use App\Models\GenbaSession;
use Barryvdh\DomPDF\Facade\Pdf;

class SummaryController extends Controller
{
    public function index()
    {
        $dealers = Dealer::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();

        $dealerId = request('dealer_id');
        $roleId = request('role_id');

        $sessions = GenbaSession::with(['answers', 'dealer', 'role', 'user'])
            ->where('status', 'submitted')
            ->when($dealerId, fn($q) => $q->where('dealer_id', $dealerId))
            ->when($roleId, fn($q) => $q->where('role_id', $roleId))
            ->get();

        $totalPaham = 0;
        $totalTidakPaham = 0;
        $totalTidakDipakai = 0;

        foreach ($sessions as $session) {
            $totalPaham        += $session->answers->where('indicator', '1')->count();
            $totalTidakPaham   += $session->answers->where('indicator', '2')->count();
            $totalTidakDipakai += $session->answers->where('indicator', '3')->count();
        }

        return view('admin.summary', compact(
            'dealers', 'roles', 'sessions',
            'totalPaham', 'totalTidakPaham', 'totalTidakDipakai',
            'dealerId', 'roleId'
        ));
    }

    public function exportPdf($dealerId)
{
    $dealer = Dealer::findOrFail($dealerId);
    $sessions = GenbaSession::with(['answers', 'dealer', 'role', 'user'])
        ->where('status', 'submitted')
        ->where('dealer_id', $dealerId)
        ->get();
 
    $totalPaham = 0;
    $totalTidakPaham = 0;
    $totalTidakDipakai = 0;
    foreach ($sessions as $session) {
        $totalPaham        += $session->answers->where('indicator', '1')->count();
        $totalTidakPaham   += $session->answers->where('indicator', '2')->count();
        $totalTidakDipakai += $session->answers->where('indicator', '3')->count();
    }
 
    $avgScore = $sessions->count() > 0 ? round($sessions->avg('score'), 1) : 0;
 
    // FIX: gunakan array biasa (values()), bukan keyed collection
    $roleStats = $sessions->groupBy(fn($s) => optional($s->role)->name ?? 'Unknown')
        ->map(function ($roleSessions, $roleName) {
            return [
                'roleName'     => (string) $roleName,
                'paham'        => $roleSessions->sum(fn($s) => $s->answers->where('indicator', '1')->count()),
                'tidakPaham'   => $roleSessions->sum(fn($s) => $s->answers->where('indicator', '2')->count()),
                'tidakDipakai' => $roleSessions->sum(fn($s) => $s->answers->where('indicator', '3')->count()),
                'avgScore'     => round($roleSessions->avg('score') ?? 0, 1),
                'roleSessions' => $roleSessions,
            ];
        })->values(); // <-- INI KUNCINYA, jadi array index 0,1,2,...
 
    return view('admin.pdf.summary-pdf', compact(
        'dealer', 'sessions', 'totalPaham', 'totalTidakPaham',
        'totalTidakDipakai', 'avgScore', 'roleStats'
    ));
}
}