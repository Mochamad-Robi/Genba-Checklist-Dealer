@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Edit User MD</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 text-xs">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <p class="text-xs text-gray-400 mb-2">Bisa pilih lebih dari satu role</p>
                <div class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                            class="rounded">
                        <span class="text-sm text-gray-700">{{ $role->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $role->type === 'program' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                            {{ $role->type === 'program' ? 'Program' : 'Pertanyaan' }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        {{ $user->is_active ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>
            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Update User
            </button>
        </form>
    </div>
</div>
@endsection