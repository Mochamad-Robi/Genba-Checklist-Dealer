<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\Dealer;
use App\Models\Role;

class RekapController extends Controller
{
    public function index()
    {
        $dealers = Dealer::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->get();

        $dealerId = request('dealer_id');
        $roleId = request('role_id');
        $status = request('status');

        $sessions = GenbaSession::with(['dealer', 'role', 'user', 'answers'])
        ->when($dealerId, fn($q) => $q->where('dealer_id', $dealerId))
        ->when($roleId, fn($q) => $q->where('role_id', $roleId))
        ->when($status, fn($q) => $q->where('status', $status))
        ->latest()
        ->paginate(100);

        return view('admin.rekap', compact('sessions', 'dealers', 'roles', 'dealerId', 'roleId', 'status'));
    }

    public function show(GenbaSession $session)
{
    $session->load(['answers.question', 'dealer', 'role', 'user']);
    $paham = $session->answers->where('indicator', '1')->count();
    $tidakPaham = $session->answers->where('indicator', '2')->count();
    $tidakDipakai = $session->answers->where('indicator', '3')->count();

    return view('admin.rekap_detail', compact('session', 'paham', 'tidakPaham', 'tidakDipakai'));
}
}