@extends('layouts.auditor')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">PICA</h2>
        <p class="text-gray-400 text-sm mt-1">Temuan dari hasil genba kamu</p>
    </div>
    <a href="{{ route('auditor.pica.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 font-medium">
        + Tambah Manual
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Dealer</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Role</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Total Temuan</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="p-3 text-left text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($sessions as $session)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="p-3 font-medium text-gray-800">{{ $session->dealer->name }}</td>
                <td class="p-3 text-gray-500">{{ $session->role->name }}</td>
                <td class="p-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        📌 {{ $session->picas->count() }} temuan
                    </span>
                </td>
                <td class="p-3 text-gray-400 text-xs">{{ $session->submitted_at?->format('d/m/Y H:i') }}</td>
                <td class="p-3">
                    <a href="{{ route('auditor.pica.show', $session) }}"
                       class="text-red-600 hover:underline text-xs font-medium">Detail →</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-400">
                    <p class="text-3xl mb-2">📋</p>
                    <p>Belum ada PICA</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $sessions->links() }}</div>
</div>
@endsection