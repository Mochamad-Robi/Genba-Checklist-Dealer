@extends('layouts.kacab')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">
        Selamat datang, {{ auth()->user()->name }} — {{ auth()->user()->dealer->name ?? '' }}
    </p>
</div>

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

{{-- Aktivitas Terbaru --}}
<div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
    <h3 style="font-size:1rem;font-weight:700;color:#1F2937;margin-bottom:16px;">Aktivitas Terbaru</h3>
    <div class="space-y-3">
        @forelse($recentSessions as $session)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;border:1px solid #F3F4F6;border-radius:10px;">
            <div>
                <p style="font-size:0.875rem;font-weight:600;color:#1F2937;">{{ $session->role->name }}</p>
                <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">{{ $session->auditee_name }} · {{ $session->submitted_at?->format('d/m/Y H:i') }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="padding:4px 10px;border-radius:100px;font-size:0.72rem;font-weight:700;
                    {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                    {{ $session->score }}%
                </span>
                <a href="{{ route('kacab.rekap.show', $session) }}"
                   style="font-size:0.75rem;color:#C8102E;font-weight:600;text-decoration:none;">Detail →</a>
            </div>
        </div>
        @empty
        <p style="text-align:center;color:#9CA3AF;padding:20px;">Belum ada aktivitas genba</p>
        @endforelse
    </div>
</div>

@endsection