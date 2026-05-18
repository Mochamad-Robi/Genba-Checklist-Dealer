@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">User Main Dealer</h2>
    <a href="{{ route('admin.users.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        + Tambah User MD
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Role</th>
                <th class="p-3 text-left">Tipe</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-medium">{{ $user->name }}</td>
                <td class="p-3 text-gray-500">{{ $user->email }}</td>
                <td class="p-3">
                    <div class="flex flex-wrap gap-1">
                        @forelse($user->roles as $role)
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-gray-400 text-xs">-</span>
                        @endforelse
                    </div>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $user->user_type === 'admin' ? 'bg-red-100 text-red-700' : ($user->user_type === 'kacab' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}
                        {{ $user->user_type === 'admin' ? 'Admin' : ($user->user_type === 'kacab' ? 'Kepala Cabang' : 'Auditor MD') }}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs
                    @if($user->user_type === 'admin') bg-red-100 text-red-700
                    @elseif($user->user_type === 'kacab') bg-green-100 text-green-700
                    @else bg-blue-100 text-blue-700
                    @endif">
                    @if($user->user_type === 'admin') Admin
                    @elseif($user->user_type === 'kacab') Kepala Cabang
                    @else Auditor MD
                    @endif
                </span>
                </td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Hapus user ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center text-gray-400">Belum ada user</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection