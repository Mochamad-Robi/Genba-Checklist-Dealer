@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.rekap.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Kembali ke Rekap</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-1">Detail Genba</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold mb-3 text-gray-700">Informasi Sesi</h3>
        <table class="text-sm w-full">
            <tr><td class="text-gray-400 py-1 w-32">Dealer</td><td class="font-medium">{{ $session->dealer->name }}</td></tr>
            <tr><td class="text-gray-400 py-1">Role</td><td>{{ $session->role->name }}</td></tr>
            <tr><td class="text-gray-400 py-1">Staf Dealer</td><td>{{ $session->auditee_name }}</td></tr>
            <tr><td class="text-gray-400 py-1">Honda ID</td><td>{{ $session->honda_id ?? '-' }}</td></tr>
            <tr><td class="text-gray-400 py-1">Auditor MD</td><td>{{ $session->user->name ?? '-' }}</td></tr>
            <tr><td class="text-gray-400 py-1">Tanggal</td><td>{{ $session->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td></tr>
        </table>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-gray-400 mb-2 text-sm">Score</p>
        <p class="text-5xl font-bold {{ $session->score >= 70 ? 'text-green-600' : 'text-red-600' }}">
            {{ $session->score }}%
        </p>
        <div class="grid grid-cols-3 gap-3 mt-4 text-sm">
            <div class="bg-green-50 p-2 rounded">
                <p class="font-bold text-green-600">{{ $paham }}</p>
                <p class="text-green-700 text-xs">Paham</p>
            </div>
            <div class="bg-red-50 p-2 rounded">
                <p class="font-bold text-red-600">{{ $tidakPaham }}</p>
                <p class="text-red-700 text-xs">Tidak Paham</p>
            </div>
            <div class="bg-gray-50 p-2 rounded">
                <p class="font-bold text-gray-600">{{ $tidakDipakai }}</p>
                <p class="text-gray-700 text-xs">Tidak Dipakai</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-4">Detail Jawaban</h3>
    <div class="space-y-3">
        @foreach($session->answers as $answer)
        <div class="flex justify-between items-start p-3 border rounded-lg">
            <div class="flex-1 pr-3">
                <p class="text-sm font-medium">
                    {{ $answer->question->question ?? $answer->question->menu_program }}
                </p>
                @if($answer->keterangan)
                    <p class="text-xs text-gray-400 mt-1">{{ $answer->keterangan }}</p>
                @endif
            </div>
            <span class="shrink-0 px-2 py-1 rounded-full text-xs font-semibold
                {{ $answer->indicator === '1' ? 'bg-green-100 text-green-700' :
                   ($answer->indicator === '2' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                {{ $answer->indicator === '1' ? 'Paham' : ($answer->indicator === '2' ? 'Tidak Paham' : 'Tidak Dipakai') }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endsection