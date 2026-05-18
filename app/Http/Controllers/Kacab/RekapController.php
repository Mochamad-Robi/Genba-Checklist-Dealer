<?php
namespace App\Http\Controllers\Kacab;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\Role;
use Illuminate\Http\Request;
class RekapController extends Controller
{
    public function index(Request $request)
    {
        $dealerId = auth()->user()->dealer_id;
        $roles = Role::where('is_active', true)->get();
        $roleId = $request->role_id;
        $status = $request->status;
        $sessions = GenbaSession::with(['dealer', 'role', 'user', 'answers'])
            ->where('dealer_id', $dealerId)
            ->when($roleId, fn($q) => $q->where('role_id', $roleId))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);
        return view('kacab.rekap', compact('sessions', 'roles', 'roleId', 'status'));
    }

    public function show(GenbaSession $session)
    {
        // Pastikan session milik dealer kacab
        if ($session->dealer_id !== auth()->user()->dealer_id) abort(403);

        $session->load([
            'answers.question',
            'role',
            'dealer',
            'user',
            'picas',
        ]);

        $paham        = $session->answers->where('indicator', '1')->count();
        $tidakPaham   = $session->answers->where('indicator', '2')->count();
        $tidakDipakai = $session->answers->where('indicator', '3')->count();

        return view('kacab.rekap_detail', compact('session', 'paham', 'tidakPaham', 'tidakDipakai'));
    }
}