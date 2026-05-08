<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\GenbaSession;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);

        // --- Stats cards (existing) ---
        $totalDealers     = Dealer::count();
        $totalSessions    = GenbaSession::count();
        $submittedSessions = GenbaSession::where('status', 'submitted')->count();

        // --- Aktivitas terbaru (existing) ---
        $recentSessions = GenbaSession::with(['dealer', 'role', 'user', 'answers'])
            ->where('status', 'submitted')
            ->latest()
            ->get()
            ->groupBy('dealer_id');

        // --- Ranking dealer (NEW) ---
        // Ambil semua dealer dengan jumlah genba submitted di periode tsb
        $rankingRaw = GenbaSession::with('answers')
            ->where('status', 'submitted')
            ->whereYear('submitted_at', $tahun)
            ->get()
            ->groupBy('dealer_id');

        // Hitung score per dealer
        $ranking = Dealer::where('is_active', true)
            ->get()
            ->map(function ($dealer) use ($rankingRaw) {
                $sessions = $rankingRaw->get($dealer->id, collect());
                $totalGenba = $sessions->count();

                // Avg score dari accessor getScoreAttribute di model
                $avgScore = $totalGenba > 0
                    ? round($sessions->avg(fn($s) => $s->score))
                    : 0;

                return [
                    'id'         => $dealer->id,
                    'name'       => $dealer->name,
                    'code'       => $dealer->code,
                    'total_genba'=> $totalGenba,
                    'avg_score'  => $avgScore,
                ];
            })
            ->sortByDesc('total_genba')
            ->values(); // reset index biar ranking dimulai dari 0

        // --- Data untuk chart (top 10) ---
        $chartLabels    = $ranking->take(10)->pluck('name')->toJson();
        $chartGenba     = $ranking->take(10)->pluck('total_genba')->toJson();
        $chartScore     = $ranking->take(10)->pluck('avg_score')->toJson();

        // --- Daftar tahun untuk dropdown ---
        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('admin.dashboard', compact(
            'totalDealers', 'totalSessions', 'submittedSessions',
            'recentSessions',
            'ranking', 'chartLabels', 'chartGenba', 'chartScore',
            'tahun', 'daftarTahun'
        ));
    }
}