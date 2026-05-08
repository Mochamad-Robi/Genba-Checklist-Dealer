@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-500">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <p class="text-sm text-gray-500">Total Dealer</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalDealers }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
        <p class="text-sm text-gray-500">Total Sesi Genba</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalSessions }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Sudah Submit</p>
        <p class="text-3xl font-bold text-gray-800">{{ $submittedSessions }}</p>
    </div>
</div>

{{-- Aktivitas Terbaru - Grouped by Dealer --}}
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

        {{-- Dealer Header - Clickable --}}
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
                {{-- Mini stats --}}
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

        {{-- Detail Sessions - Hidden by default --}}
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

<script>
function toggleDealer(id) {
    const el = document.getElementById(id);
    const dealerId = id.replace('dealer-', '');
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