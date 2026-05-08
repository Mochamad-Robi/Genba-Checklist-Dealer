@extends('layouts.auditor')
@section('content')
<div class="mb-6">
    <a href="{{ route('auditor.pica.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-1">Detail PICA</h2>
</div>

{{-- Info Sesi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400 mb-1">Dealer</p>
            <p class="font-semibold text-gray-800">{{ $session->dealer->name }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Role</p>
            <p class="font-semibold text-gray-800">{{ $session->role->name }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Tanggal Genba</p>
            <p class="font-semibold text-gray-800">{{ $session->submitted_at?->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Total Temuan</p>
            <p class="font-semibold text-red-600">{{ $picas->count() }} temuan</p>
        </div>
    </div>
</div>

{{-- Daftar Temuan --}}
<div class="space-y-4">
    @forelse($picas as $index => $pica)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center">
                    {{ $index + 1 }}
                </span>
                <div>
                    <p class="font-semibold text-gray-800">{{ $pica->masalah }}</p>
                    @if($pica->indikator)
                    <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block
                        {{ $pica->indikator === 'Tidak Paham' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $pica->indikator }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $pica->status_color }}">
                    {{ $pica->status_label }}
                </span>
                <a href="{{ route('auditor.pica.edit', ['session' => $session->id, 'pica' => $pica->id]) }}"
                   class="text-blue-600 hover:underline text-xs">Edit</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-1">Keterangan</p>
                <p class="text-gray-700">{{ $pica->keterangan ?? '-' }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-1">PIC</p>
                <p class="text-gray-700">{{ $pica->pic ?? '⚠️ Belum diisi' }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-1">Analisa</p>
                <p class="text-gray-700">{{ $pica->analisa ?? '⚠️ Belum diisi' }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-1">Tindakan</p>
                <p class="text-gray-700">{{ $pica->tindakan ?? '⚠️ Belum diisi' }}</p>
            </div>
        </div>

        @if($pica->target_date)
        <div class="mt-3 text-xs text-gray-400">
            Target: <span class="font-medium text-gray-600">{{ $pica->target_date->format('d/m/Y') }}</span>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl p-10 text-center text-gray-400">
        <p class="text-3xl mb-2">📋</p>
        <p>Tidak ada temuan</p>
    </div>
    @endforelse
</div>
@endsection