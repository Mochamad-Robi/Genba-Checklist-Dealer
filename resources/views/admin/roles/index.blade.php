@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Data Role</h2>
    <a href="{{ route('admin.roles.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        + Tambah Role
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left">Urutan</th>
                <th class="p-3 text-left">Nama Role</th>
                <th class="p-3 text-left">Tipe</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 text-gray-400">{{ $role->order }}</td>
                <td class="p-3 font-medium">{{ $role->name }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $role->type === 'program' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ $role->type === 'program' ? 'Menu Program' : 'Pertanyaan' }}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $role->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $role->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                          onsubmit="return confirm('Hapus role ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-4 text-center text-gray-400">Belum ada role</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection