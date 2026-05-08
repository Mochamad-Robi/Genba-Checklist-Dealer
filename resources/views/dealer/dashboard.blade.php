@extends('layouts.dealer')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Halo, {{ auth()->user()->name }}! 👋</h2>
    <p class="text-gray-500">{{ auth()->user()->dealer->name ?? '' }} — {{ auth()->user()->role->name ?? '' }}</p>
</div>

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-semibold mb-2">Mulai Genba</h3>
    <p class="text-gray-500 text-sm mb-4">Jawab checklist sesuai role Anda</p>
    <a href="{{ route('dealer.genba.start') }}"
       class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium">
        📋 Mulai Checklist
    </a>
</div>

@if($sessions->count() > 0)
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Riwayat Genba</h3>
    <div class="space-y-3">
        @foreach($sessions as $session)
        <div class="flex justify-between items-center p-3 border rounded-lg hover:bg-gray-50">
            <div>
                <p class="font-medium">{{ $session->role->name }}</p>
                <p class="text-xs text-gray-400">{{ $session->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($session->status === 'submitted')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $session->score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $session->score }}%
                    </span>
                    <a href="{{ route('dealer.genba.result', $session) }}"
                       class="text-blue-600 text-sm hover:underline">Lihat</a>
                @else
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Draft</span>
                    <a href="{{ route('dealer.genba.fill', $session) }}"
                       class="text-blue-600 text-sm hover:underline">Lanjutkan</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection