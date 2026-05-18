<?php
namespace App\Http\Controllers\Kacab;
use App\Http\Controllers\Controller;
use App\Models\Pica;
use App\Models\GenbaSession;
use Illuminate\Http\Request;

class PicaController extends Controller
{
   public function index(Request $request)
{
    $dealerId = auth()->user()->dealer_id;
    $status = $request->status;

    $sessions = GenbaSession::with(['role', 'user', 'picas'])
        ->where('dealer_id', $dealerId)
        ->where('status', 'submitted')
        ->whereHas('picas')
        ->latest()
        ->paginate(20);

    $totalOpen = Pica::whereHas('session', fn($q) => $q->where('dealer_id', $dealerId))
        ->where('status', 'open')->count();
    $totalOnProgress = Pica::whereHas('session', fn($q) => $q->where('dealer_id', $dealerId))
        ->where('status', 'on_progress')->count();
    $totalClosed = Pica::whereHas('session', fn($q) => $q->where('dealer_id', $dealerId))
        ->where('status', 'closed')->count();

    return view('kacab.pica', compact('sessions', 'totalOpen', 'totalOnProgress', 'totalClosed', 'status'));
}

    public function show(GenbaSession $session)
    {
        if ($session->dealer_id !== auth()->user()->dealer_id) abort(403);
        $picas = Pica::where('session_id', $session->id)->get();
        return view('kacab.pica_detail', compact('session', 'picas'));
    }
}