@extends('layouts.kacab')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">
        Selamat datang, {{ auth()->user()->name }} —
        {{ auth()->user()->dealer->name ?? '' }}
    </p>
</div>

{{-- Filter Bulan & Tahun --}}
<form method="GET" action="{{ route('kacab.dashboard') }}"
      style="display:flex;gap:10px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:6px;">
        <label style="font-size:0.78rem;color:#6B7280;font-weight:600;">Bulan</label>
        <select name="month" onchange="this.form.submit()"
            style="border:1px solid #E5E7EB;border-radius:8px;padding:6px 10px;font-size:0.82rem;color:#1F2937;background:white;cursor:pointer;">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                </option>
            @endforeach
        </select>
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
        <label style="font-size:0.78rem;color:#6B7280;font-weight:600;">Tahun</label>
        <select name="year" onchange="this.form.submit()"
            style="border:1px solid #E5E7EB;border-radius:8px;padding:6px 10px;font-size:0.82rem;color:#1F2937;background:white;cursor:pointer;">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <span style="font-size:0.75rem;color:#9CA3AF;">
        — Menampilkan data
        {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
    </span>
</form>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;border-left:4px solid #C8102E;">
        <p style="font-size:0.75rem;color:#9CA3AF;">Total Genba</p>
        <p style="font-size:2rem;font-weight:800;color:#1F2937;">{{ $totalSessions }}</p>
    </div>
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;border-left:4px solid #D97706;">
        <p style="font-size:0.75rem;color:#9CA3AF;">Draft</p>
        <p style="font-size:2rem;font-weight:800;color:#1F2937;">{{ $totalDraft }}</p>
    </div>
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;border-left:4px solid #DC2626;">
        <p style="font-size:0.75rem;color:#9CA3AF;">PICA Open</p>
        <p style="font-size:2rem;font-weight:800;color:#DC2626;">{{ $totalPicaOpen }}</p>
    </div>
</div>

{{-- Grafik & Ranking --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">

    {{-- Grafik Tren --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
        <h3 style="font-size:0.9rem;font-weight:700;color:#1F2937;margin-bottom:4px;">📈 Tren Genba</h3>
        <p style="font-size:0.7rem;color:#9CA3AF;margin-bottom:16px;">
            Per minggu — {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
        </p>
        <canvas id="trendChart" height="200"></canvas>
    </div>

    {{-- Ranking Role --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
        <h3 style="font-size:0.9rem;font-weight:700;color:#1F2937;margin-bottom:4px;">🏆 Ranking Role</h3>
        <p style="font-size:0.7rem;color:#9CA3AF;margin-bottom:16px;">
            Skor rata-rata — {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
        </p>

        @forelse($rankingRoles as $i => $role)
        <div style="margin-bottom:14px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:0.72rem;font-weight:800;min-width:20px;
                        color:{{ $i === 0 ? '#D97706' : ($i === 1 ? '#6B7280' : ($i === 2 ? '#92400E' : '#9CA3AF')) }};">
                        #{{ $i + 1 }}
                    </span>
                    <span style="font-size:0.8rem;font-weight:600;color:#1F2937;">{{ $role['name'] }}</span>
                    <span style="font-size:0.65rem;color:#9CA3AF;">({{ $role['count'] }}x)</span>
                </div>
                <span style="font-size:0.8rem;font-weight:700;
                    color:{{ $role['score'] >= 70 ? '#16A34A' : '#DC2626' }};">
                    {{ $role['score'] }}%
                </span>
            </div>
            <div style="background:#F3F4F6;border-radius:100px;height:6px;">
                <div style="height:6px;border-radius:100px;width:{{ $role['score'] }}%;
                    background:{{ $role['score'] >= 70 ? '#16A34A' : '#DC2626' }};
                    transition:width 0.6s ease;"></div>
            </div>
        </div>
        @empty
        <p style="text-align:center;color:#9CA3AF;padding:20px;font-size:0.8rem;">
            Belum ada data di bulan ini
        </p>
        @endforelse
    </div>

</div>

{{-- Aktivitas Terbaru (Dropdown per Tanggal) --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
    <h3 style="font-size:1rem;font-weight:700;color:#1F2937;margin-bottom:16px;">Aktivitas Terbaru</h3>

    @forelse($sessionsByDate as $tanggal => $sessions)
    <div style="margin-bottom:10px;border:1px solid #F3F4F6;border-radius:12px;overflow:hidden;">

        <button type="button"
            onclick="toggleDropdown('date-{{ $loop->index }}')"
            style="width:100%;display:flex;justify-content:space-between;align-items:center;
                   padding:12px 16px;background:#FAFAFA;border:none;cursor:pointer;text-align:left;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:0.82rem;font-weight:700;color:#1F2937;">📅 {{ $tanggal }}</span>
                <span style="background:#FEE2E2;color:#C8102E;font-size:0.7rem;font-weight:700;
                             padding:2px 8px;border-radius:100px;">
                    {{ $sessions->count() }} Sesi
                </span>
            </div>
            <span id="arrow-{{ $loop->index }}"
                  style="font-size:0.75rem;color:#9CA3AF;transition:transform 0.2s;">▼</span>
        </button>

        <div id="date-{{ $loop->index }}" style="display:none;">
            @foreach($sessions as $session)
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:12px 16px;border-top:1px solid #F3F4F6;">
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:#1F2937;">
                        {{ $session->role->name }}
                    </p>
                    <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                        {{ $session->auditee_name }} · {{ $session->submitted_at?->format('H:i') }}
                    </p>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="padding:4px 10px;border-radius:100px;font-size:0.72rem;font-weight:700;
                        {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                        {{ $session->score }}%
                    </span>
                    <a href="{{ route('kacab.rekap.show', $session) }}"
                       style="font-size:0.75rem;color:#C8102E;font-weight:600;text-decoration:none;">
                        Detail →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    @empty
    <p style="text-align:center;color:#9CA3AF;padding:20px;">
        Belum ada aktivitas genba di bulan ini
    </p>
    @endforelse
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
function toggleDropdown(id) {
    const el    = document.getElementById(id);
    const idx   = id.split('-')[1];
    const arrow = document.getElementById('arrow-' + idx);
    const isOpen = el.style.display !== 'none';
    el.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
}

document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [{
                label: 'Jumlah Genba',
                data: {!! json_encode($trendValues) !!},
                backgroundColor: 'rgba(200,16,46,0.10)',
                borderColor: '#C8102E',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.parsed.y} genba` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: '#F3F4F6' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>

@endsection