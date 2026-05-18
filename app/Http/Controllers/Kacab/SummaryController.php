<?php
namespace App\Http\Controllers\Kacab;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\Role;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $dealerId = auth()->user()->dealer_id;
        $roles = Role::where('is_active', true)->get();
        $roleId = $request->role_id;

        $sessions = GenbaSession::with(['answers', 'role'])
            ->where('dealer_id', $dealerId)
            ->where('status', 'submitted')
            ->when($roleId, fn($q) => $q->where('role_id', $roleId))
            ->get();

        $totalPaham = 0;
        $totalTidakPaham = 0;
        $totalTidakDipakai = 0;

        foreach ($sessions as $session) {
            $totalPaham += $session->answers->where('indicator', '1')->count();
            $totalTidakPaham += $session->answers->where('indicator', '2')->count();
            $totalTidakDipakai += $session->answers->where('indicator', '3')->count();
        }

        return view('kacab.summary', compact(
            'sessions', 'roles', 'roleId',
            'totalPaham', 'totalTidakPaham', 'totalTidakDipakai'
        ));
    }
}