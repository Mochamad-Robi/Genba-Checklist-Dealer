@extends('layouts.admin')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Summary Genba</h2>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Dealer</label>
            <select name="dealer_id" id="dealerSelect" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Dealer</option>
                @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ $dealerId == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700">
                Filter
            </button>
            <a href="{{ route('admin.summary') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm">
                Reset
            </a>
        </div>
    </form>
</div>

@if($dealerId || $roleId)
    @if($sessions->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Distribusi Indikator</h3>
            <div class="max-w-xs mx-auto">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Ringkasan</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="font-medium text-green-700">✅ Paham</span>
                    <span class="text-2xl font-bold text-green-700">{{ $totalPaham }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <span class="font-medium text-red-700">❌ Tidak Paham</span>
                    <span class="text-2xl font-bold text-red-700">{{ $totalTidakPaham }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium text-gray-700">⬜ Tidak Dipakai</span>
                    <span class="text-2xl font-bold text-gray-700">{{ $totalTidakDipakai }}</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400 mb-6">
        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
        <p class="font-medium text-gray-600">Tidak ada data untuk filter ini</p>
    </div>
    @endif
@else
    <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400 mb-6">
        <i class="bi bi-funnel" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
        <p class="font-medium text-gray-600">Pilih filter Dealer atau Role dulu</p>
        <p class="text-sm mt-1">Grafik dan detail akan muncul setelah filter dipilih</p>
    </div>
@endif

{{-- Detail Hasil --}}
@if($sessions->count() > 0)
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Detail Hasil ({{ $sessions->count() }} data)</h3>
        <div class="flex items-center gap-2">
            @if($dealerId)
            {{-- Tombol aktif: dealer sudah dipilih --}}
            <a href="{{ route('admin.summary.export-pdf', $dealerId) }}{{ $roleId ? '?role_id='.$roleId : '' }}"
               target="_blank"
               class="inline-flex items-center gap-2 bg-red-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Export PDF
            </a>
            @else
            {{-- Tombol disabled: belum pilih dealer --}}
            <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-400 text-sm px-4 py-2 rounded-lg cursor-not-allowed select-none"
                  title="Pilih dealer terlebih dahulu untuk export PDF">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Export PDF
            </span>
            @endif
        </div>
    </div>

    @php $grouped = $sessions->groupBy('dealer_id'); @endphp

    @foreach($grouped as $dId => $dealerSessions)
    @php
        $dealer = $dealerSessions->first()->dealer;
        $avgScore = round($dealerSessions->avg(fn($s) => $s->score));
        $totalP = $dealerSessions->sum(fn($s) => $s->answers->where('indicator','1')->count());
        $totalTP = $dealerSessions->sum(fn($s) => $s->answers->where('indicator','2')->count());
        $totalTD = $dealerSessions->sum(fn($s) => $s->answers->where('indicator','3')->count());
    @endphp

    <div style="border:1px solid #F3F4F6;border-radius:12px;margin-bottom:10px;overflow:hidden;">
        <div style="padding:13px 16px;background:#F9FAFB;cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.2s;"
             onclick="toggleDealer('sum-{{ $dId }}')"
             onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-shop" style="color:white;font-size:0.9rem;"></i>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $dealer->name }}</p>
                    <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                        {{ $dealerSessions->count() }} sesi
                        &nbsp;·&nbsp;
                        <span style="color:{{ $avgScore >= 70 ? '#16A34A' : '#DC2626' }};font-weight:600;">
                            Avg {{ $avgScore }}%
                        </span>
                    </p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:0.68rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:3px 8px;border-radius:100px;">✅ {{ $totalP }}</span>
                <span style="font-size:0.68rem;background:#FFF5F5;color:#DC2626;font-weight:600;padding:3px 8px;border-radius:100px;">❌ {{ $totalTP }}</span>
                <span style="font-size:0.68rem;background:#F9FAFB;color:#6B7280;font-weight:600;padding:3px 8px;border-radius:100px;">⬜ {{ $totalTD }}</span>

                {{-- Tombol export PDF per dealer (muncul di setiap row dealer saat filter role aktif atau multi dealer) --}}
                <a href="{{ route('admin.summary.export-pdf', $dId) }}{{ $roleId ? '?role_id='.$roleId : '' }}"
                   target="_blank"
                   onclick="event.stopPropagation()"
                   style="font-size:0.68rem;background:#FEE2E2;color:#DC2626;font-weight:600;padding:3px 10px;border-radius:100px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;"
                   title="Export PDF dealer ini">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    PDF
                </a>

                <i class="bi bi-chevron-down" id="icon-sum-{{ $dId }}"
                   style="color:#9CA3AF;font-size:0.75rem;transition:transform 0.2s;margin-left:4px;"></i>
            </div>
        </div>

        <div id="sum-{{ $dId }}" style="display:none;">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#FAFAFA;border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;">
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Role</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Auditee</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Paham</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tidak Paham</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tidak Dipakai</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Score</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Atas Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dealerSessions as $session)
                    <tr style="border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
                        onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                        <td style="padding:12px 16px;color:#374151;">{{ $session->role->name }}</td>
                        <td style="padding:12px 16px;font-weight:500;color:#1F2937;">{{ $session->auditee_name }}</td>
                        <td style="padding:12px 16px;font-weight:700;color:#16A34A;">{{ $session->answers->where('indicator','1')->count() }}</td>
                        <td style="padding:12px 16px;font-weight:700;color:#DC2626;">{{ $session->answers->where('indicator','2')->count() }}</td>
                        <td style="padding:12px 16px;color:#6B7280;">{{ $session->answers->where('indicator','3')->count() }}</td>
                        <td style="padding:12px 16px;">
                            <span style="padding:3px 10px;border-radius:100px;font-size:0.72rem;font-weight:700;
                                {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                                {{ $session->score }}%
                            </span>
                        </td>
                        <td style="padding:12px 16px;">
                            @if($session->is_behalf)
                            <span style="font-size:0.68rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:3px 8px;border-radius:100px;">
                                Dibantu
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
function toggleDealer(id) {
    const el = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (el.style.display === 'none') {
        el.style.display = 'block';
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        el.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}

new TomSelect("#dealerSelect",{
    placeholder: "Cari dealer...",
    allowEmptyOption: true,
    create: false,
    sortField: {
        field: "text",
        direction: "asc"
    }
});

@if(($dealerId || $roleId) && $sessions->count() > 0)
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paham', 'Tidak Paham', 'Tidak Dipakai'],
        datasets: [{
            data: [{{ $totalPaham }}, {{ $totalTidakPaham }}, {{ $totalTidakDipakai }}],
            backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom' } }
    }
});
@endif
</script>
@endsection