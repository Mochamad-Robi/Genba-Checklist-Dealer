@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">PICA</h2>
    <p class="text-gray-500">Temuan dari hasil genba seluruh auditor MD</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-red-50 border border-red-100 rounded-2xl p-5 text-center">
        <p class="text-3xl font-bold text-red-600">{{ $totalOpen }}</p>
        <p class="text-xs text-red-500 mt-1 font-medium uppercase tracking-wider">Open</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5 text-center">
        <p class="text-3xl font-bold text-yellow-600">{{ $totalOnProgress }}</p>
        <p class="text-xs text-yellow-500 mt-1 font-medium uppercase tracking-wider">On Progress</p>
    </div>
    <div class="bg-green-50 border border-green-100 rounded-2xl p-5 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $totalClosed }}</p>
        <p class="text-xs text-green-500 mt-1 font-medium uppercase tracking-wider">Closed</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Dealer</label>
            <select name="dealer_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                <option value="">Semua Dealer</option>
                @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ $dealerId == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Auditor MD</label>
            <select name="user_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                <option value="">Semua Auditor</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 text-sm">Filter</button>
            <a href="{{ route('admin.pica.index') }}" class="bg-gray-100 text-gray-500 px-4 py-2 rounded-xl hover:bg-gray-200 text-sm">Reset</a>
        </div>
    </form>
</div>

{{-- Tombol Export --}}
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.pica.export', request()->query()) }}"
       style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#16A34A,#15803D);color:white;padding:10px 20px;border-radius:10px;font-size:0.85rem;font-weight:600;text-decoration:none;box-shadow:0 4px 12px rgba(22,163,74,0.3);transition:all 0.2s;"
       onmouseover="this.style.transform='translateY(-1px)'"
       onmouseout="this.style.transform='translateY(0)'">
        <i class="bi bi-file-earmark-excel-fill"></i>
        Export Excel
    </a>
</div>

{{-- PICA Grouped by Dealer --}}
@php
    $groupedSessions = $sessions->getCollection()->groupBy('dealer_id');
@endphp

@if($groupedSessions->count() > 0)
<div style="space-y:12px;">
    @foreach($groupedSessions as $dId => $dealerSessions)
    @php
        $dealer = $dealerSessions->first()->dealer;
        $totalTemuan = $dealerSessions->sum(fn($s) => $s->picas->count());
        $totalOpen = $dealerSessions->sum(fn($s) => $s->picas->where('status','open')->count());
        $totalProgress = $dealerSessions->sum(fn($s) => $s->picas->where('status','on_progress')->count());
        $totalClosed2 = $dealerSessions->sum(fn($s) => $s->picas->where('status','closed')->count());
    @endphp

    <div style="background:white;border:1px solid #F3F4F6;border-radius:12px;margin-bottom:10px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.04);">

        {{-- Header --}}
        <div style="padding:13px 16px;background:#F9FAFB;cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.2s;"
             onclick="togglePica('pica-{{ $dId }}')"
             onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">

            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-shop" style="color:white;font-size:0.9rem;"></i>
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $dealer->name }}</p>
                    <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                        {{ $dealerSessions->count() }} sesi &nbsp;·&nbsp;
                        <span style="color:#C8102E;font-weight:600;">{{ $totalTemuan }} temuan</span>
                    </p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:8px;">
                @if($totalOpen > 0)
                <span style="font-size:0.68rem;background:#FEE2E2;color:#DC2626;font-weight:600;padding:3px 8px;border-radius:100px;">
                    🔴 {{ $totalOpen }} Open
                </span>
                @endif
                @if($totalProgress > 0)
                <span style="font-size:0.68rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:3px 8px;border-radius:100px;">
                    🟡 {{ $totalProgress }} Progress
                </span>
                @endif
                @if($totalClosed2 > 0)
                <span style="font-size:0.68rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:3px 8px;border-radius:100px;">
                    🟢 {{ $totalClosed2 }} Closed
                </span>
                @endif
                <i class="bi bi-chevron-down" id="icon-pica-{{ $dId }}"
                   style="color:#9CA3AF;font-size:0.75rem;transition:transform 0.2s;margin-left:4px;"></i>
            </div>
        </div>

        {{-- Detail Sessions --}}
        <div id="pica-{{ $dId }}" style="display:none;">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#FAFAFA;border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;">
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Role</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Auditor MD</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Total Temuan</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tanggal</th>
                        <th style="padding:10px 16px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dealerSessions as $session)
                    <tr style="border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
                        onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                        <td style="padding:12px 16px;color:#374151;">{{ $session->role->name }}</td>
                        <td style="padding:12px 16px;color:#9CA3AF;font-size:0.8rem;">{{ $session->user->name }}</td>
                        <td style="padding:12px 16px;">
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:0.72rem;font-weight:600;background:#FEE2E2;color:#DC2626;">
                                📌 {{ $session->picas->count() }} temuan
                            </span>
                        </td>
                        <td style="padding:12px 16px;color:#9CA3AF;font-size:0.78rem;">
                            {{ $session->submitted_at?->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:12px 16px;">
                            <a href="{{ route('admin.pica.show', $session) }}"
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
    @endforeach
</div>

<div class="mt-4">{{ $sessions->links() }}</div>

@else
<div style="background:white;border-radius:12px;padding:60px;text-align:center;color:#9CA3AF;border:1px solid #F3F4F6;">
    <i class="bi bi-clipboard-x" style="font-size:3rem;display:block;margin-bottom:12px;color:#E5E7EB;"></i>
    <p style="font-weight:600;color:#374151;">Belum ada PICA</p>
</div>
@endif

<script>
function togglePica(id) {
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
</script>
@endsection