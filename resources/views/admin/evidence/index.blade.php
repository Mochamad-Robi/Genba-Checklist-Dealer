@extends('layouts.admin')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Evidence Foto</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">Foto bukti kunjungan genba per dealer</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-5 mb-6">
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
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 text-sm">Filter</button>
            <a href="{{ route('admin.evidence.index') }}" class="bg-gray-100 text-gray-500 px-4 py-2 rounded-xl text-sm">Reset</a>
        </div>
    </form>
</div>

{{-- List Kunjungan --}}
<div class="space-y-3">
    @forelse($kunjungan as $item)
    @php
        $evidenceCount = \App\Models\GenbaEvidence::where('dealer_id', $item->dealer_id)
            ->where('tanggal_kunjungan', $item->tanggal)
            ->count();
        $maxFoto = $item->total_sesi * 2;
        $isLengkap = $evidenceCount >= $maxFoto;
    @endphp
    <div style="background:white;border:1px solid #F3F4F6;border-radius:12px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-camera-fill" style="color:white;font-size:1rem;"></i>
            </div>
            <div>
                <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $item->dealer->name }}</p>
                <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">
                    <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                    &nbsp;·&nbsp; {{ $item->total_sesi }} sesi genba
                </p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            @if($isLengkap)
            <span style="font-size:0.72rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:4px 10px;border-radius:100px;border:1px solid #BBF7D0;">
                <i class="bi bi-check-circle-fill"></i> {{ $evidenceCount }}/{{ $maxFoto }} lengkap
            </span>
            @elseif($evidenceCount > 0)
            <span style="font-size:0.72rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:4px 10px;border-radius:100px;border:1px solid #FDE68A;">
                <i class="bi bi-images"></i> {{ $evidenceCount }}/{{ $maxFoto }} foto
            </span>
            @else
            <span style="font-size:0.72rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:4px 10px;border-radius:100px;border:1px solid #FDE68A;">
                <i class="bi bi-exclamation-circle"></i> Belum ada foto
            </span>
            @endif
            <a href="{{ route('admin.evidence.show', [$item->dealer_id, $item->tanggal]) }}"
               style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:8px 16px;border-radius:10px;font-size:0.78rem;font-weight:600;text-decoration:none;">
                <i class="bi bi-camera"></i> Kelola Foto
            </a>
        </div>
    </div>
    @empty
    <div style="background:white;border-radius:12px;padding:60px;text-align:center;color:#9CA3AF;border:1px solid #F3F4F6;">
        <i class="bi bi-camera-fill" style="font-size:3rem;display:block;margin-bottom:12px;color:#E5E7EB;"></i>
        <p style="font-weight:600;color:#374151;">Belum ada kunjungan</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $kunjungan->links() }}</div>

@endsection