@extends('layouts.admin')
@section('content')
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-500">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    {{-- Total Dealer --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">

            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">
                    Total Dealer
                </p>

                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ number_format($totalDealers) }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="bi bi-shop text-red-600 text-xl"></i>
            </div>

        </div>

        <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-1 bg-red-500 rounded-full w-[85%]"></div>
        </div>
    </div>

    {{-- Total Sesi --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">

            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">
                    Total Sesi Genba
                </p>

                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ number_format($totalSessions) }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="bi bi-clipboard-data text-blue-600 text-xl"></i>
            </div>

        </div>

        <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-1 bg-blue-500 rounded-full w-[70%]"></div>
        </div>
    </div>

    {{-- Submitted --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">

            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">
                    Sudah Submit
                </p>

                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ number_format($submittedSessions) }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-green-600 text-xl"></i>
            </div>

        </div>

        <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-1 bg-green-500 rounded-full w-[92%]"></div>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{--  RANKING & CHART SECTION (NEW)                               --}}
{{-- ============================================================ --}}

{{-- Filter Tahun --}}
<div class="bg-white rounded-xl shadow p-4 mb-6 flex flex-wrap items-center gap-3">
    <span class="text-sm font-semibold text-gray-600">Filter Tahun:</span>
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
        <select name="tahun"
            class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-red-400 focus:border-red-400">
            @foreach($daftarTahun as $y)
                <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
            @endforeach
        </select>

        <button type="submit"
            class="text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg transition font-medium">
            Tampilkan
        </button>
    </form>

    <span class="text-xs text-gray-400 ml-auto">Menampilkan data tahun {{ $tahun }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- RANKING / LEADERBOARD --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
            <h3 class="text-lg font-semibold text-gray-800">Ranking Dealer</h3>
            <span class="text-xs text-gray-400">{{ $ranking->count() }} dealer</span>
        </div>
        <p class="text-xs text-gray-400 mb-3">Berdasarkan jumlah genba submitted — tahun {{ $tahun }}</p>

        @if($ranking->where('total_genba', '>', 0)->count() === 0)
            <div class="text-center py-10 text-gray-400">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p class="text-sm">Belum ada genba di periode ini</p>
            </div>
        @else
            {{-- Tab switcher --}}
            <div style="display:flex; gap:4px; background:#F3F4F6; border-radius:8px; padding:3px; margin-bottom:14px;">
                <button id="tab-btn-top10" onclick="switchRankTab('top10')"
                    style="flex:1; font-size:0.75rem; font-weight:600; padding:6px 0; border-radius:6px; border:none; cursor:pointer; background:#C8102E; color:white; transition:all 0.2s;">
                    Top 10
                </button>
                <button id="tab-btn-semua" onclick="switchRankTab('semua')"
                    style="flex:1; font-size:0.75rem; font-weight:600; padding:6px 0; border-radius:6px; border:none; cursor:pointer; background:transparent; color:#6B7280; transition:all 0.2s;">
                    Semua dealer
                </button>
            </div>

            @php $maxGenba = $ranking->first()['total_genba']; @endphp

            {{-- TAB TOP 10 --}}
            <div id="tab-top10" style="height:340px; overflow-y:auto; display:flex; flex-direction:column; gap:5px; padding-right:2px; scrollbar-width:thin;">
                @foreach($ranking->take(10) as $index => $dealer)
                    @php
                        $rank       = $index + 1;
                       $medals = [
                            '<i class="bi bi-trophy-fill text-warning"></i>',
                            '<i class="bi bi-award-fill text-secondary"></i>',
                            '<i class="bi bi-award text-amber-600"></i>',
                        ];
                        $isTop3     = $rank <= 3;
                        $pct        = $maxGenba > 0 ? round(($dealer['total_genba'] / $maxGenba) * 100) : 0;
                        $scoreColor = $dealer['avg_score'] >= 70 ? '#16A34A' : '#DC2626';
                        $scoreBg    = $dealer['avg_score'] >= 70 ? '#F0FDF4' : '#FFF5F5';
                        $barColor   = $rank === 1 ? '#C8102E' : ($rank === 2 ? '#6B7280' : ($rank === 3 ? '#B45309' : '#BFDBFE'));
                    @endphp
                    <div style="display:flex; align-items:center; gap:8px; padding:{{ $isTop3 ? '8px 10px' : '6px 8px' }}; border-radius:8px; background:{{ $isTop3 ? '#FFF7F7' : 'transparent' }}; border:{{ $isTop3 ? '1px solid #FEE2E2' : '1px solid transparent' }}; flex-shrink:0;">
                        <span style="font-size:{{ $isTop3 ? '1.15rem' : '0.75rem' }}; min-width:22px; text-align:center; font-weight:600; color:#9CA3AF;">
                            {!! $isTop3 ? $medals[$index] : $rank !!}
                        </span>
                        <div style="flex:1; min-width:0;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px;">
                                <span style="font-size:0.8rem; font-weight:{{ $isTop3 ? '700' : '500' }}; color:#1F2937; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:130px;">
                                    {{ $dealer['name'] }}
                                </span>
                                <div style="display:flex; gap:5px; align-items:center; flex-shrink:0;">
                                    <span style="font-size:0.68rem; font-weight:700; padding:2px 6px; border-radius:100px; background:{{ $scoreBg }}; color:{{ $scoreColor }};">
                                        {{ $dealer['avg_score'] > 0 ? $dealer['avg_score'].'%' : '-' }}
                                    </span>
                                    <span style="font-size:0.78rem; font-weight:{{ $isTop3 ? '700' : '400' }}; color:{{ $isTop3 ? '#1F2937' : '#9CA3AF' }};">
                                        {{ $dealer['total_genba'] }}x
                                    </span>
                                </div>
                            </div>
                            <div style="height:3px; background:#F3F4F6; border-radius:2px; overflow:hidden;">
                                <div style="height:3px; width:{{ $pct }}%; background:{{ $barColor }}; border-radius:2px;"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TAB SEMUA DEALER --}}
            <div id="tab-semua" style="height:340px; overflow-y:auto; display:none; flex-direction:column; gap:3px; padding-right:2px; scrollbar-width:thin;">
                @foreach($ranking as $index => $dealer)
                    @php
                        $rank       = $index + 1;
                        $medals = [
                            '<i class="bi bi-trophy-fill text-warning"></i>',
                            '<i class="bi bi-award-fill text-secondary"></i>',
                            '<i class="bi bi-award" style="color:#cd7f32"></i>',
                        ];
                        $isTop3     = $rank <= 3;
                        $scoreColor = $dealer['avg_score'] >= 70 ? '#16A34A' : ($dealer['avg_score'] === 0 ? '#9CA3AF' : '#DC2626');
                        $scoreBg    = $dealer['avg_score'] >= 70 ? '#F0FDF4' : ($dealer['avg_score'] === 0 ? '#F9FAFB' : '#FFF5F5');
                    @endphp
                    <div style="display:flex; align-items:center; gap:8px; padding:5px 8px; border-radius:6px; flex-shrink:0; {{ $isTop3 ? 'background:#FFF7F7;' : '' }}">
                        <span style="font-size:{{ $isTop3 ? '1rem' : '0.7rem' }}; min-width:22px; text-align:center; font-weight:500; color:#9CA3AF;">
                            {!! $isTop3 ? $medals[$index] : $rank !!}
                        </span>
                        <span style="flex:1; font-size:0.75rem; color:#1F2937; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $dealer['name'] }}
                        </span>
                        <div style="display:flex; gap:5px; align-items:center; flex-shrink:0;">
                            <span style="font-size:0.65rem; font-weight:700; padding:1px 5px; border-radius:100px; background:{{ $scoreBg }}; color:{{ $scoreColor }};">
                                {{ $dealer['avg_score'] > 0 ? $dealer['avg_score'].'%' : '-' }}
                            </span>
                            <span style="font-size:0.75rem; color:#9CA3AF; min-width:24px; text-align:right;">
                                {{ $dealer['total_genba'] }}x
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-gray-400 mt-2 text-right">% = avg score · angka = jumlah genba</p>
        @endif
    </div>

    {{-- CHART PERBANDINGAN --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-1 flex items-center gap-2">
            Perbandingan Dealer
        </h3>
        <p class="text-xs text-gray-400 mb-1">Top 10 dealer — tahun {{ $tahun }}</p>

        {{-- Toggle chart mode --}}
        <div class="flex gap-2 mb-4">
            <button onclick="switchChart('genba')" id="btn-genba"
                style="font-size:0.72rem; padding:4px 12px; border-radius:100px; border:1px solid #C8102E; background:#C8102E; color:white; cursor:pointer; transition:all 0.2s;">
                Jumlah Genba
            </button>
            <button onclick="switchChart('score')" id="btn-score"
                style="font-size:0.72rem; padding:4px 12px; border-radius:100px; border:1px solid #D1D5DB; background:white; color:#6B7280; cursor:pointer; transition:all 0.2s;">
                Avg Score
            </button>
        </div>

        {{-- Legend --}}
        <div id="legend-genba" class="flex gap-3 mb-3">
            <span style="display:flex; align-items:center; gap:4px; font-size:11px; color:#6B7280;">
                <span style="width:10px; height:10px; border-radius:2px; background:#C8102E; display:inline-block;"></span>
                Jumlah Genba
            </span>
        </div>
        <div id="legend-score" class="flex gap-3 mb-3" style="display:none !important;">
            <span style="display:flex; align-items:center; gap:4px; font-size:11px; color:#6B7280;">
                <span style="width:10px; height:10px; border-radius:2px; background:#2563EB; display:inline-block;"></span>
                Avg Score (%)
            </span>
        </div>

        <div style="position:relative; width:100%; height:260px;">
            <canvas id="dealerChart" role="img"
                aria-label="Grafik batang perbandingan dealer tahun {{ $tahun }}">
                Data perbandingan dealer.
            </canvas>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- AKTIVITAS TERBARU (existing, tidak diubah)                   --}}
{{-- ============================================================ --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>

    @forelse($recentSessions as $dealerId => $sessions)
    @php
        $dealer = $sessions->first()->dealer;
        $avgScore = round($sessions->avg(fn($s) => $s->score));
        $totalPaham = $sessions->sum(fn($s) => $s->answers->where('indicator','1')->count());
        $totalTidakPaham = $sessions->sum(fn($s) => $s->answers->where('indicator','2')->count());
    @endphp

    <div style="border:1px solid #F3F4F6;border-radius:12px;margin-bottom:12px;overflow:hidden;">
        <div style="padding:14px 18px;background:#F9FAFB;cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.2s;"
             onclick="toggleDealer('dealer-{{ $dealerId }}')"
             onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">

            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-shop" style="color:white;font-size:0.9rem;"></i>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $dealer->name }}</p>
                    <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                        {{ $sessions->count() }} sesi genba
                        &nbsp;·&nbsp;
                        <span style="color:{{ $avgScore >= 70 ? '#16A34A' : '#DC2626' }};font-weight:600;">
                            Avg {{ $avgScore }}%
                        </span>
                    </p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <div style="display:flex;gap:8px;">
                    <span style="font-size:0.68rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:3px 8px;border-radius:100px;">
                        ✅ {{ $totalPaham }} Paham
                    </span>
                    <span style="font-size:0.68rem;background:#FFF5F5;color:#DC2626;font-weight:600;padding:3px 8px;border-radius:100px;">
                        ❌ {{ $totalTidakPaham }} Tidak Paham
                    </span>
                </div>
                <i class="bi bi-chevron-down" id="icon-dealer-{{ $dealerId }}"
                   style="color:#9CA3AF;font-size:0.75rem;transition:transform 0.2s;"></i>
            </div>
        </div>

        <div id="dealer-{{ $dealerId }}" style="display:none;">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#FAFAFA;border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;">
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Role</th>
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Staf Dealer</th>
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Auditor MD</th>
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Score</th>
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tanggal</th>
                        <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    <tr style="border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
                        onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                        <td style="padding:12px 18px;color:#374151;">{{ $session->role->name }}</td>
                        <td style="padding:12px 18px;font-weight:500;color:#1F2937;">{{ $session->auditee_name }}</td>
                        <td style="padding:12px 18px;color:#9CA3AF;font-size:0.8rem;">{{ $session->user->name ?? '-' }}</td>
                        <td style="padding:12px 18px;">
                            <span style="padding:3px 10px;border-radius:100px;font-size:0.72rem;font-weight:700;
                                {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                                {{ $session->score }}%
                            </span>
                        </td>
                        <td style="padding:12px 18px;color:#9CA3AF;font-size:0.78rem;">
                            {{ $session->submitted_at?->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:12px 18px;">
                            <a href="{{ route('admin.rekap.show', $session) }}"
                               style="font-size:0.78rem;color:#C8102E;font-weight:600;text-decoration:none;">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div style="padding:40px;text-align:center;color:#9CA3AF;">
        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
        <p>Belum ada aktivitas</p>
    </div>
    @endforelse
</div>

{{-- ============================================================ --}}
{{-- SCRIPTS                                                       --}}
{{-- ============================================================ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
// Data dari Laravel (di-encode JSON supaya aman)
const chartLabels = {!! $chartLabels !!};
const chartGenba  = {!! $chartGenba !!};
const chartScore  = {!! $chartScore !!};

let activeMode = 'genba';

const ctx = document.getElementById('dealerChart').getContext('2d');
const dealerChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Jumlah Genba',
            data: chartGenba,
            backgroundColor: chartGenba.map((_, i) => {
                if (i === 0) return '#C8102E';
                if (i === 1) return '#E54B4B';
                if (i === 2) return '#F07070';
                return '#FBBFBF';
            }),
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => activeMode === 'genba'
                        ? ' ' + ctx.parsed.y + ' genba'
                        : ' Avg Score: ' + ctx.parsed.y + '%'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    font: { size: 11 },
                    autoSkip: false,
                    maxRotation: 35,
                    callback: function(val, index) {
                        // Potong nama panjang supaya tidak overlap
                        const label = this.getLabelForValue(val);
                        return label.length > 12 ? label.substring(0, 12) + '…' : label;
                    }
                },
                grid: { display: false }
            },
            y: {
                ticks: { font: { size: 11 } },
                grid: { color: 'rgba(0,0,0,0.05)' },
                beginAtZero: true
            }
        }
    }
});

function switchChart(mode) {
    activeMode = mode;

    if (mode === 'genba') {
        dealerChart.data.datasets[0].label = 'Jumlah Genba';
        dealerChart.data.datasets[0].data  = chartGenba;
        dealerChart.data.datasets[0].backgroundColor = chartGenba.map((_, i) => {
            if (i === 0) return '#C8102E';
            if (i === 1) return '#E54B4B';
            if (i === 2) return '#F07070';
            return '#FBBFBF';
        });

        document.getElementById('btn-genba').style.background = '#C8102E';
        document.getElementById('btn-genba').style.color = 'white';
        document.getElementById('btn-genba').style.borderColor = '#C8102E';
        document.getElementById('btn-score').style.background = 'white';
        document.getElementById('btn-score').style.color = '#6B7280';
        document.getElementById('btn-score').style.borderColor = '#D1D5DB';

        document.getElementById('legend-genba').style.display = 'flex';
        document.getElementById('legend-score').style.display = 'none';
    } else {
        dealerChart.data.datasets[0].label = 'Avg Score (%)';
        dealerChart.data.datasets[0].data  = chartScore;
        dealerChart.data.datasets[0].backgroundColor = chartScore.map((_, i) => {
            if (i === 0) return '#1D4ED8';
            if (i === 1) return '#2563EB';
            if (i === 2) return '#3B82F6';
            return '#93C5FD';
        });

        document.getElementById('btn-score').style.background = '#2563EB';
        document.getElementById('btn-score').style.color = 'white';
        document.getElementById('btn-score').style.borderColor = '#2563EB';
        document.getElementById('btn-genba').style.background = 'white';
        document.getElementById('btn-genba').style.color = '#6B7280';
        document.getElementById('btn-genba').style.borderColor = '#D1D5DB';

        document.getElementById('legend-score').style.display = 'flex';
        document.getElementById('legend-genba').style.display = 'none';
    }

    dealerChart.update();
}

// Tab ranking dealer
function switchRankTab(tab) {
    const isTop10 = tab === 'top10';
    document.getElementById('tab-top10').style.display = isTop10 ? 'flex' : 'none';
    document.getElementById('tab-semua').style.display  = isTop10 ? 'none' : 'flex';

    const btnTop10 = document.getElementById('tab-btn-top10');
    const btnSemua = document.getElementById('tab-btn-semua');
    btnTop10.style.background = isTop10 ? '#C8102E' : 'transparent';
    btnTop10.style.color      = isTop10 ? 'white' : '#6B7280';
    btnSemua.style.background = isTop10 ? 'transparent' : '#C8102E';
    btnSemua.style.color      = isTop10 ? '#6B7280' : 'white';
}

// Toggle aktivitas terbaru (existing)
function toggleDealer(id) {
    const el   = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (el.style.display === 'none') {
        el.style.display = 'block';
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        el.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}
</script>

@endsection