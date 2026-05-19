<?php
namespace App\Http\Controllers\Kacab;
use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\Pica;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $dealerId = $user->dealer_id;

        $month = request('month', now()->month);
        $year  = request('year',  now()->year);

        // ── Stats ──────────────────────────────────────────
        $baseQuery = GenbaSession::where('dealer_id', $dealerId)
            ->where('status', 'submitted')
            ->whereMonth('submitted_at', $month)
            ->whereYear('submitted_at',  $year);

        $totalSessions = (clone $baseQuery)->count();

        $totalDraft = GenbaSession::where('dealer_id', $dealerId)
            ->where('status', 'draft')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at',  $year)
            ->count();

        $totalPicaOpen = Pica::whereHas('session', fn($q) =>
            $q->where('dealer_id', $dealerId)
              ->whereMonth('submitted_at', $month)
              ->whereYear('submitted_at',  $year)
        )->where('status', 'open')->count();

        // ── Aktivitas terbaru ──────────────────────────────
        $recentSessions = (clone $baseQuery)
            ->with(['role', 'user', 'answers'])
            ->latest('submitted_at')
            ->take(30)
            ->get();

        $sessionsByDate = $recentSessions->groupBy(fn($s) =>
            $s->submitted_at?->format('d/m/Y')
        );

        // ── Grafik tren per minggu ─────────────────────────
        $trendData = (clone $baseQuery)
            ->selectRaw('WEEK(submitted_at, 1) as week_num, COUNT(*) as total')
            ->groupByRaw('WEEK(submitted_at, 1)')
            ->orderByRaw('WEEK(submitted_at, 1)')
            ->get();

        $startOfMonth = now()->setYear($year)->setMonth($month)->startOfMonth();
        $trendLabels  = [];
        $trendValues  = [];
        $weekMap      = $trendData->keyBy('week_num');

        for ($w = 0; $w < 5; $w++) {
            $weekStart = $startOfMonth->copy()->addWeeks($w);
            if ($weekStart->month != $month) break;
            $weekKey       = $weekStart->weekOfYear;
            $trendLabels[] = 'Minggu ' . ($w + 1);
            $trendValues[] = $weekMap[$weekKey]->total ?? 0;
        }

        // ── Ranking role berdasarkan skor rata-rata ────────
        $rankingRoles = Role::where('is_active', true)
            ->whereHas('questions')
            ->get()
            ->map(function ($role) use ($dealerId, $month, $year) {
                $sessions = GenbaSession::where('dealer_id', $dealerId)
                    ->where('role_id', $role->id)
                    ->where('status', 'submitted')
                    ->whereMonth('submitted_at', $month)
                    ->whereYear('submitted_at',  $year)
                    ->with('answers')
                    ->get();

                if ($sessions->isEmpty()) return null;

                $scores = $sessions->map(function ($s) {
                    $total = $s->answers->whereNotNull('indicator')->count();
                    $paham = $s->answers->where('indicator', '1')->count();
                    return $total > 0 ? round(($paham / $total) * 100) : 0;
                });

                return [
                    'name'  => $role->name,
                    'score' => round($scores->avg()),
                    'count' => $sessions->count(),
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->values();

        $availableYears = range(now()->year, now()->year - 3);

        return view('kacab.dashboard', compact(
            'totalSessions', 'totalDraft', 'totalPicaOpen',
            'sessionsByDate',
            'trendLabels', 'trendValues',
            'rankingRoles',
            'month', 'year', 'availableYears'
        ));
    }
}