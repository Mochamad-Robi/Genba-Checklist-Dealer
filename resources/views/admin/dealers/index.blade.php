@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Data Dealer</h2>
    <a href="{{ route('admin.dealers.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        + Tambah Dealer
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left">Kode</th>
                <th class="p-3 text-left">Nama Dealer</th>
                <th class="p-3 text-left">Alamat</th>
                <th class="p-3 text-left">Telepon</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dealers as $dealer)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-mono text-xs">{{ $dealer->code }}</td>
                <td class="p-3 font-medium">{{ $dealer->name }}</td>
                <td class="p-3 text-gray-500">{{ $dealer->address ?? '-' }}</td>
                <td class="p-3 text-gray-500">{{ $dealer->phone ?? '-' }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $dealer->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $dealer->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.dealers.edit', $dealer) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.dealers.destroy', $dealer) }}"
                          onsubmit="return confirm('Hapus dealer ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-gray-400">Belum ada dealer</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $dealers->links() }}</div>
</div>
@endsection