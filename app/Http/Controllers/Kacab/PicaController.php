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
        $month  = $request->month ?? now()->month;
        $year   = $request->year  ?? now()->year;

        $sessions = GenbaSession::with(['role', 'user', 'picas'])
            ->where('dealer_id', $dealerId)
            ->where('status', 'submitted')
            ->whereHas('picas', function($q) use ($status) {
                if ($status) $q->where('status', $status);
            })
            ->whereMonth('submitted_at', $month)
            ->whereYear('submitted_at', $year)
            ->latest('submitted_at')
            ->paginate(20);

        $sessionsByDate = $sessions->getCollection()
            ->groupBy(fn($s) => $s->submitted_at?->format('d/m/Y'));

        // Stats ikut filter bulan/tahun
        $picaBase = Pica::whereHas('session', fn($q) =>
            $q->where('dealer_id', $dealerId)
              ->whereMonth('submitted_at', $month)
              ->whereYear('submitted_at', $year)
        );

        $totalOpen       = (clone $picaBase)->where('status', 'open')->count();
        $totalOnProgress = (clone $picaBase)->where('status', 'on_progress')->count();
        $totalClosed     = (clone $picaBase)->where('status', 'closed')->count();

        $availableYears = range(now()->year, now()->year - 3);

        return view('kacab.pica', compact(
            'sessions', 'sessionsByDate',
            'totalOpen', 'totalOnProgress', 'totalClosed',
            'status', 'month', 'year', 'availableYears'
        ));
    }

    public function show(GenbaSession $session)
    {
        if ($session->dealer_id !== auth()->user()->dealer_id) abort(403);
        $picas = Pica::where('session_id', $session->id)->get();
        return view('kacab.pica_detail', compact('session', 'picas'));
    }
}