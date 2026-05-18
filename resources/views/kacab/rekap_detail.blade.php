@extends('layouts.kacab')
@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('kacab.rekap.index') }}"
       style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:white;border:1px solid #E5E7EB;color:#6B7280;text-decoration:none;flex-shrink:0;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail Genba</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:2px;">
            {{ $session->role->name }} · {{ $session->auditee_name }}
        </p>
    </div>
</div>

{{-- Info Card --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;margin-bottom:20px;">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <p style="font-size:0.68rem;font-weight:600;color:#9CA3AF;text-transform:uppercase;margin-bottom:4px;">Auditee</p>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->auditee_name }}</p>
        </div>
        <div>
            <p style="font-size:0.68rem;font-weight:600;color:#9CA3AF;text-transform:uppercase;margin-bottom:4px;">Honda ID</p>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->honda_id ?? '-' }}</p>
        </div>
        <div>
            <p style="font-size:0.68rem;font-weight:600;color:#9CA3AF;text-transform:uppercase;margin-bottom:4px;">Auditor</p>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->user->name ?? '-' }}</p>
        </div>
        <div>
            <p style="font-size:0.68rem;font-weight:600;color:#9CA3AF;text-transform:uppercase;margin-bottom:4px;">Tanggal</p>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->submitted_at?->format('d/m/Y H:i') ?? '-' }}</p>
        </div>
    </div>
</div>

{{-- Score Summary --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    {{-- Score --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;text-align:center;border-top:4px solid {{ $session->score >= 70 ? '#16A34A' : '#DC2626' }};">
        <p style="font-size:0.72rem;color:#9CA3AF;margin-bottom:6px;">Score</p>
        <p style="font-size:2.2rem;font-weight:800;color:{{ $session->score >= 70 ? '#16A34A' : '#DC2626' }};">
            {{ $session->score }}%
        </p>
        <p style="font-size:0.68rem;color:#9CA3AF;margin-top:4px;">
            {{ $session->score >= 70 ? 'Kompeten' : 'Perlu Perbaikan' }}
        </p>
    </div>
    {{-- Paham --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;text-align:center;border-top:4px solid #16A34A;">
        <p style="font-size:0.72rem;color:#9CA3AF;margin-bottom:6px;">Paham</p>
        <p style="font-size:2.2rem;font-weight:800;color:#16A34A;">{{ $paham }}</p>
        <p style="font-size:0.68rem;color:#9CA3AF;margin-top:4px;">Pertanyaan</p>
    </div>
    {{-- Tidak Paham --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;text-align:center;border-top:4px solid #DC2626;">
        <p style="font-size:0.72rem;color:#9CA3AF;margin-bottom:6px;">Tidak Paham</p>
        <p style="font-size:2.2rem;font-weight:800;color:#DC2626;">{{ $tidakPaham }}</p>
        <p style="font-size:0.68rem;color:#9CA3AF;margin-top:4px;">Pertanyaan</p>
    </div>
</div>

{{-- Progress Bar --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px;margin-bottom:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <p style="font-size:0.8rem;font-weight:600;color:#1F2937;">Progress Pemahaman</p>
        <p style="font-size:0.8rem;font-weight:700;color:#1F2937;">{{ $session->score }}%</p>
    </div>
    <div style="background:#F3F4F6;border-radius:100px;height:10px;overflow:hidden;">
        <div style="height:100%;border-radius:100px;background:{{ $session->score >= 70 ? 'linear-gradient(90deg,#16A34A,#22C55E)' : 'linear-gradient(90deg,#C8102E,#EF4444)' }};width:{{ $session->score }}%;transition:width 0.5s ease;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:8px;">
        <span style="font-size:0.68rem;color:#9CA3AF;">Paham: {{ $paham }}</span>
        <span style="font-size:0.68rem;color:#9CA3AF;">Tidak Paham: {{ $tidakPaham }}</span>
        @if($tidakDipakai > 0)
        <span style="font-size:0.68rem;color:#9CA3AF;">Tidak Dipakai: {{ $tidakDipakai }}</span>
        @endif
    </div>
</div>

{{-- Daftar Pertanyaan --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:700;color:#1F2937;margin-bottom:16px;">
        <i class="bi bi-list-check" style="color:#C8102E;margin-right:6px;"></i>
        Hasil Pertanyaan
    </h3>

    {{-- Group by menu_program --}}
    @php
        $grouped = $session->answers->groupBy(fn($a) => $a->question->menu_program ?? 'Umum');
    @endphp

    @foreach($grouped as $menu => $answers)
    <div style="margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #F3F4F6;">
            <span style="background:#FEF2F2;color:#C8102E;font-size:0.68rem;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:uppercase;">
                {{ $menu }}
            </span>
            @php
                $pahamCount = $answers->where('indicator','1')->count();
                $totalCount = $answers->whereNotNull('indicator')->count();
                $pct = $totalCount > 0 ? round(($pahamCount/$totalCount)*100) : 0;
            @endphp
            <span style="font-size:0.68rem;color:#9CA3AF;">{{ $pahamCount }}/{{ $totalCount }} paham ({{ $pct }}%)</span>
        </div>

        <div class="space-y-2">
            @foreach($answers as $answer)
            <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;border-radius:10px;
                {{ $answer->indicator === '1' ? 'background:#F0FDF4;border:1px solid #BBF7D0;' : ($answer->indicator === '2' ? 'background:#FFF5F5;border:1px solid #FECACA;' : 'background:#F9FAFB;border:1px solid #F3F4F6;') }}">
                <div style="flex-shrink:0;margin-top:2px;">
                    @if($answer->indicator === '1')
                        <i class="bi bi-check-circle-fill" style="color:#16A34A;font-size:1rem;"></i>
                    @elseif($answer->indicator === '2')
                        <i class="bi bi-x-circle-fill" style="color:#DC2626;font-size:1rem;"></i>
                    @else
                        <i class="bi bi-dash-circle" style="color:#9CA3AF;font-size:1rem;"></i>
                    @endif
                </div>
                <div style="flex:1;">
                    <p style="font-size:0.8rem;color:#1F2937;font-weight:500;line-height:1.4;">
                        {{ $answer->question->question ?? '-' }}
                    </p>
                    @if($answer->keterangan)
                    <p style="font-size:0.72rem;color:#6B7280;margin-top:4px;font-style:italic;">
                        <i class="bi bi-chat-left-text" style="margin-right:4px;"></i>{{ $answer->keterangan }}
                    </p>
                    @endif
                </div>
                <div style="flex-shrink:0;">
                    <span style="font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:100px;
                        {{ $answer->indicator === '1' ? 'background:#DCFCE7;color:#16A34A;' : ($answer->indicator === '2' ? 'background:#FEE2E2;color:#DC2626;' : 'background:#F3F4F6;color:#9CA3AF;') }}">
                        {{ $answer->indicator === '1' ? 'Paham' : ($answer->indicator === '2' ? 'Tidak Paham' : 'N/A') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

{{-- PICA Section --}}
@php
    $picas = $session->picas ?? collect();
@endphp
@if($picas->count() > 0)
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;margin-bottom:20px;">
    <h3 style="font-size:1rem;font-weight:700;color:#1F2937;margin-bottom:16px;">
        <i class="bi bi-tools" style="color:#C8102E;margin-right:6px;"></i>
        PICA ({{ $picas->count() }} item)
    </h3>
    <div class="space-y-3">
        @foreach($picas as $pica)
        <div style="border:1px solid #F3F4F6;border-radius:10px;padding:14px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                <p style="font-size:0.8rem;font-weight:600;color:#1F2937;">{{ $pica->masalah }}</p>
                <span style="font-size:0.65rem;font-weight:700;padding:2px 10px;border-radius:100px;flex-shrink:0;margin-left:8px;
                    {{ $pica->status === 'closed' ? 'background:#DCFCE7;color:#16A34A;' : ($pica->status === 'on_progress' ? 'background:#FEF3C7;color:#D97706;' : 'background:#FEE2E2;color:#DC2626;') }}">
                    {{ $pica->status_label }}
                </span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:2px;">PIC</p>
                    <p style="font-size:0.75rem;font-weight:600;color:#374151;">{{ $pica->pic ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:2px;">Target</p>
                    <p style="font-size:0.75rem;font-weight:600;color:#374151;">{{ $pica->target_date?->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:2px;">Tindakan</p>
                    <p style="font-size:0.75rem;font-weight:600;color:#374151;">{{ $pica->tindakan ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Action untuk Kacab --}}
<div style="background:linear-gradient(135deg,#FFF5F5,#FEF2F2);border:1px solid #FECACA;border-radius:16px;padding:20px;margin-bottom:20px;">
    <h3 style="font-size:0.875rem;font-weight:700;color:#991B1B;margin-bottom:4px;">
        <i class="bi bi-exclamation-triangle-fill" style="margin-right:6px;"></i>
        Catatan untuk Kepala Cabang
    </h3>
    <p style="font-size:0.78rem;color:#B91C1C;margin-bottom:12px;">
        Terdapat <strong>{{ $tidakPaham }}</strong> pertanyaan yang belum dikuasai. Lakukan coaching dan monitoring tindak lanjut.
    </p>
    @if($picas->where('status','open')->count() > 0)
    <div style="background:white;border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-clock-history" style="color:#DC2626;"></i>
        <p style="font-size:0.78rem;color:#374151;">
            <strong style="color:#DC2626;">{{ $picas->where('status','open')->count() }} PICA</strong> masih berstatus Open — segera tindaklanjuti!
        </p>
    </div>
    @endif
</div>

@endsection