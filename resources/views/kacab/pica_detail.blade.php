@extends('layouts.kacab')
@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('kacab.pica.index') }}"
       style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:white;border:1px solid #E5E7EB;color:#6B7280;text-decoration:none;flex-shrink:0;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail PICA</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:2px;">
            {{ $session->role->name ?? '-' }} · {{ $session->auditee_name }} · {{ $session->submitted_at?->format('d/m/Y') }}
        </p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#DC2626;">{{ $picas->where('status','open')->count() }}</p>
        <p style="font-size:0.72rem;color:#DC2626;font-weight:600;text-transform:uppercase;margin-top:4px;">Open</p>
    </div>
    <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#D97706;">{{ $picas->where('status','on_progress')->count() }}</p>
        <p style="font-size:0.72rem;color:#D97706;font-weight:600;text-transform:uppercase;margin-top:4px;">On Progress</p>
    </div>
    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#16A34A;">{{ $picas->where('status','closed')->count() }}</p>
        <p style="font-size:0.72rem;color:#16A34A;font-weight:600;text-transform:uppercase;margin-top:4px;">Closed</p>
    </div>
</div>

{{-- List PICA --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
    <h3 style="font-size:1rem;font-weight:700;color:#1F2937;margin-bottom:16px;">
        <i class="bi bi-tools" style="color:#C8102E;margin-right:6px;"></i>
        Daftar Temuan ({{ $picas->count() }})
    </h3>

    <div class="space-y-4">
        @forelse($picas as $pica)
        <div style="border:1px solid #F3F4F6;border-radius:12px;padding:16px;
            {{ $pica->status === 'open' ? 'border-left:4px solid #DC2626;' : ($pica->status === 'on_progress' ? 'border-left:4px solid #D97706;' : 'border-left:4px solid #16A34A;') }}">

            {{-- Status & Masalah --}}
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                <p style="font-size:0.875rem;font-weight:700;color:#1F2937;flex:1;margin-right:12px;">
                    {{ $pica->masalah }}
                </p>
                <span style="font-size:0.65rem;font-weight:700;padding:3px 10px;border-radius:100px;flex-shrink:0;
                    {{ $pica->status === 'closed' ? 'background:#DCFCE7;color:#16A34A;' : ($pica->status === 'on_progress' ? 'background:#FEF3C7;color:#D97706;' : 'background:#FEE2E2;color:#DC2626;') }}">
                    {{ $pica->status_label }}
                </span>
            </div>

            {{-- Detail --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:3px;text-transform:uppercase;font-weight:600;">Analisa</p>
                    <p style="font-size:0.78rem;color:#374151;">{{ $pica->analisa ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:3px;text-transform:uppercase;font-weight:600;">Tindakan</p>
                    <p style="font-size:0.78rem;color:#374151;">{{ $pica->tindakan ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:3px;text-transform:uppercase;font-weight:600;">PIC</p>
                    <p style="font-size:0.78rem;color:#374151;">{{ $pica->pic ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:3px;text-transform:uppercase;font-weight:600;">Target</p>
                    <p style="font-size:0.78rem;color:#374151;
                        {{ $pica->target_date && $pica->target_date->isPast() && $pica->status !== 'closed' ? 'color:#DC2626;font-weight:700;' : '' }}">
                        {{ $pica->target_date?->format('d/m/Y') ?? '-' }}
                        @if($pica->target_date && $pica->target_date->isPast() && $pica->status !== 'closed')
                            <span style="font-size:0.65rem;"> ⚠ Overdue</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($pica->keterangan)
            <div style="margin-top:10px;padding:8px 12px;background:#F9FAFB;border-radius:8px;">
                <p style="font-size:0.65rem;color:#9CA3AF;margin-bottom:2px;text-transform:uppercase;font-weight:600;">Keterangan</p>
                <p style="font-size:0.78rem;color:#374151;font-style:italic;">{{ $pica->keterangan }}</p>
            </div>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:#9CA3AF;">
            <i class="bi bi-clipboard-x" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
            <p>Belum ada PICA untuk sesi ini</p>
        </div>
        @endforelse
    </div>
</div>

@endsection