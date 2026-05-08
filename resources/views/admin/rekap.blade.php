@extends('layouts.admin')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Rekap Genba</h2>
    <p class="text-gray-500">Semua sesi genba yang telah dilakukan</p>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-600 mb-1">Dealer</label>
            <select name="dealer_id" id="dealerSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Dealer</option>
                @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ $dealerId == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
            <select name="role_id" class="tomselect w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="tomselect w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 text-sm">Filter</button>
            <a href="{{ route('admin.rekap.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm">Reset</a>
        </div>
    </form>
</div>

{{-- Grouped by Dealer --}}
@php
    $grouped = $sessions->getCollection()->groupBy('dealer_id');
@endphp

@forelse($grouped as $dealerId => $dealerSessions)
@php
    $dealer = $dealerSessions->first()->dealer;
    $avgScore = round($dealerSessions->where('status','submitted')->avg(fn($s) => $s->score));
    $totalPaham = $dealerSessions->sum(fn($s) => $s->answers->where('indicator','1')->count());
    $totalTidakPaham = $dealerSessions->sum(fn($s) => $s->answers->where('indicator','2')->count());
    $submittedCount = $dealerSessions->where('status','submitted')->count();
    $draftCount = $dealerSessions->where('status','draft')->count();
@endphp

<div style="background:white;border:1px solid #F3F4F6;border-radius:12px;margin-bottom:12px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">

    {{-- Dealer Header --}}
    <div style="padding:14px 18px;background:#F9FAFB;cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.2s;"
         onclick="toggleDealer('rekap-{{ $dealerId }}')"
         onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">

        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-shop" style="color:white;font-size:0.9rem;"></i>
            </div>
            <div>
                <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $dealer->name }}</p>
                <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                    {{ $dealerSessions->count() }} sesi
                    @if($submittedCount > 0)
                    &nbsp;·&nbsp;
                    <span style="color:{{ $avgScore >= 70 ? '#16A34A' : '#DC2626' }};font-weight:600;">
                        Avg {{ $avgScore }}%
                    </span>
                    @endif
                    @if($draftCount > 0)
                    &nbsp;·&nbsp;
                    <span style="color:#D97706;font-weight:600;">{{ $draftCount }} Draft</span>
                    @endif
                </p>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:8px;">
            @if($submittedCount > 0)
            <span style="font-size:0.68rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:3px 8px;border-radius:100px;">
                ✅ {{ $totalPaham }} Paham
            </span>
            <span style="font-size:0.68rem;background:#FFF5F5;color:#DC2626;font-weight:600;padding:3px 8px;border-radius:100px;">
                ❌ {{ $totalTidakPaham }} Tidak Paham
            </span>
            @endif
            <i class="bi bi-chevron-down" id="icon-rekap-{{ $dealerId }}"
               style="color:#9CA3AF;font-size:0.75rem;transition:transform 0.2s;margin-left:4px;"></i>
        </div>
    </div>

    {{-- Detail Sessions --}}
    <div id="rekap-{{ $dealerId }}" style="display:none;">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#FAFAFA;border-top:1px solid #F3F4F6;border-bottom:1px solid #F3F4F6;">
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Role</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Staf Dealer</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Auditor MD</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Status</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Score</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tanggal</th>
                    <th style="padding:10px 18px;text-align:left;font-size:0.72rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dealerSessions as $session)
                <tr style="border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
                    onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                    <td style="padding:12px 18px;color:#374151;">{{ $session->role->name }}</td>
                    <td style="padding:12px 18px;font-weight:500;color:#1F2937;">{{ $session->auditee_name }}</td>
                    <td style="padding:12px 18px;color:#9CA3AF;font-size:0.8rem;">{{ $session->user->name ?? '-' }}</td>
                    <td style="padding:12px 18px;">
                        <span style="padding:3px 10px;border-radius:100px;font-size:0.72rem;font-weight:600;
                            {{ $session->status === 'submitted' ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FEF3C7;color:#D97706;' }}">
                            {{ $session->status === 'submitted' ? 'Submitted' : 'Draft' }}
                        </span>
                    </td>
                    <td style="padding:12px 18px;">
                        @if($session->status === 'submitted')
                        <span style="font-weight:700;font-size:0.85rem;color:{{ $session->score >= 70 ? '#16A34A' : '#DC2626' }};">
                            {{ $session->score }}%
                        </span>
                        @else
                        <span style="color:#D1D5DB;">—</span>
                        @endif
                    </td>
                    <td style="padding:12px 18px;color:#9CA3AF;font-size:0.78rem;">
                        {{ $session->created_at->format('d/m/Y H:i') }}
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
<div style="background:white;border-radius:12px;padding:60px;text-align:center;color:#9CA3AF;border:1px solid #F3F4F6;">
    <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:12px;color:#E5E7EB;"></i>
    <p style="font-weight:600;color:#374151;">Belum ada data genba</p>
</div>
@endforelse

<div class="mt-4">{{ $sessions->links() }}</div>

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
    create: false
});

document.querySelectorAll('.tomselect').forEach(el => {
    new TomSelect(el,{
        placeholder: "Pilih / Cari...",
        allowEmptyOption: true
    });
});
</script>

@endsection