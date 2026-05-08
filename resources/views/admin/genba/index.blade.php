@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Genba</h2>
    <a href="{{ route('admin.genba.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        + Isi Atas Nama
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left">Dealer</th>
                <th class="p-3 text-left">Role</th>
                <th class="p-3 text-left">Auditee</th>
                <th class="p-3 text-left">Diisi Oleh</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Score</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $session->dealer->name }}</td>
                <td class="p-3">{{ $session->role->name }}</td>
                <td class="p-3">
                    {{ $session->auditee_name }}
                    @if($session->is_behalf)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-1 py-0.5 rounded ml-1">Dibantu</span>
                    @endif
                </td>
                <td class="p-3 text-gray-500">{{ $session->filledBy->name ?? '-' }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $session->status === 'submitted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $session->status === 'submitted' ? 'Submitted' : 'Draft' }}
                    </span>
                </td>
                <td class="p-3">
                    @if($session->status === 'submitted')
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $session->score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $session->score }}%
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="p-3 flex gap-2">
                    @if($session->status === 'draft')
                        <a href="{{ route('admin.genba.fill', $session) }}"
                           class="text-blue-600 hover:underline text-xs">Lanjutkan</a>
                    @else
                        <a href="{{ route('admin.genba.show', $session) }}"
                           class="text-blue-600 hover:underline text-xs">Detail</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-4 text-center text-gray-400">Belum ada sesi genba</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $sessions->links() }}</div>
</div>
@endsection