@extends('layouts.kacab')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">PICA</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">Temuan genba {{ auth()->user()->dealer->name ?? '' }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#DC2626;">{{ $totalOpen }}</p>
        <p style="font-size:0.72rem;color:#DC2626;font-weight:600;text-transform:uppercase;margin-top:4px;">Open</p>
    </div>
    <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#D97706;">{{ $totalOnProgress }}</p>
        <p style="font-size:0.72rem;color:#D97706;font-weight:600;text-transform:uppercase;margin-top:4px;">On Progress</p>
    </div>
    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:16px;padding:20px;text-align:center;">
        <p style="font-size:2rem;font-weight:800;color:#16A34A;">{{ $totalClosed }}</p>
        <p style="font-size:0.72rem;color:#16A34A;font-weight:600;text-transform:uppercase;margin-top:4px;">Closed</p>
    </div>
</div>

{{-- List PICA --}}
<div class="space-y-3">
    @forelse($sessions as $session)
    <div style="background:white;border:1px solid #F3F4F6;border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->role->name }}</p>
            <p style="font-size:0.72rem;color:#9CA3AF;margin-top:3px;">
                {{ $session->user->name ?? '-' }} · {{ $session->submitted_at?->format('d/m/Y') }}
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="background:#FEE2E2;color:#DC2626;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:100px;">
                📌 {{ $session->picas->count() }} temuan
            </span>
            <a href="{{ route('kacab.pica.show', $session) }}"
               style="font-size:0.75rem;color:#C8102E;font-weight:600;text-decoration:none;">Detail →</a>
        </div>
    </div>
    @empty
    <div style="background:white;border-radius:12px;padding:40px;text-align:center;color:#9CA3AF;">
        <i class="bi bi-clipboard-x" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
        <p>Belum ada PICA</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $sessions->links() }}</div>

@endsection