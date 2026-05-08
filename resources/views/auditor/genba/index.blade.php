@extends('layouts.auditor')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Genba</h2>
        <p class="text-gray-400 text-sm mt-1">Semua sesi genba kamu</p>
    </div>
    <a href="{{ route('auditor.genba.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 font-medium">
        + Genba Baru
    </a>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Sesi</p>
            <span class="text-xl">📋</span>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalSesi }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua genba</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-green-500 uppercase tracking-wider">Submitted</p>
            <span class="text-xl">✅</span>
        </div>
        <p class="text-3xl font-bold text-green-600">{{ $totalSubmitted }}</p>
        <p class="text-xs text-gray-400 mt-1">Selesai diisi</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider">Draft</p>
            <span class="text-xl">💾</span>
        </div>
        <p class="text-3xl font-bold text-yellow-500">{{ $totalDraft }}</p>
        <p class="text-xs text-gray-400 mt-1">Belum selesai</p>
    </div>
</div>

{{-- Tabel Riwayat --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Dealer</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Role</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Staf Dealer</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Status</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Score</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($sessions as $session)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="p-3 font-medium text-gray-800">{{ $session->dealer->name }}</td>
                <td class="p-3 text-gray-500">{{ $session->role->name }}</td>
                <td class="p-3 text-gray-500">{{ $session->auditee_name }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $session->status === 'submitted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $session->status === 'submitted' ? '✅ Submitted' : '💾 Draft' }}
                    </span>
                </td>
                <td class="p-3">
                    @if($session->status === 'submitted')
                        <span class="font-semibold {{ $session->score >= 70 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $session->score }}%
                        </span>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="p-3 text-gray-400 text-xs">{{ $session->created_at->format('d/m/Y H:i') }}</td>
                <td class="p-3">
                    @if($session->status === 'submitted')
                        <a href="{{ route('auditor.genba.result', $session) }}"
                           class="text-red-600 hover:underline text-xs font-medium">Lihat →</a>
                    @else
                        <a href="{{ route('auditor.genba.fill', $session) }}"
                           class="text-blue-600 hover:underline text-xs font-medium">Lanjutkan →</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-400">
                    <p class="text-3xl mb-2">📋</p>
                    <p>Belum ada riwayat genba</p>
                    <a href="{{ route('auditor.genba.create') }}"
                       class="inline-block mt-3 text-sm text-red-600 hover:underline">
                        + Mulai genba pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $sessions->links() }}</div>
</div>
@endsection