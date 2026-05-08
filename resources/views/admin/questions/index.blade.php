@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Pertanyaan</h2>
    <a href="{{ route('admin.questions.create') }}"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
        + Tambah Pertanyaan
    </a>
</div>

<div class="flex gap-6">
    {{-- Sidebar Role --}}
    <div class="w-64 shrink-0">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-3 bg-gray-50 border-b text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Pilih Role
            </div>
            <nav id="roleNav" class="divide-y">
                @foreach($roles as $role)
                <button onclick="showRole({{ $role->id }})"
                    id="btn-{{ $role->id }}"
                    class="role-btn w-full text-left px-4 py-3 hover:bg-red-50 hover:text-red-700 transition-all text-sm">
                    <p class="font-medium">{{ $role->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $role->questions->count() }} pertanyaan
                        · {{ $role->type === 'program' ? 'Menu Program' : 'Pertanyaan' }}
                    </p>
                </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Konten Pertanyaan --}}
    <div class="flex-1">
        {{-- Default state --}}
        <div id="defaultState" class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
            <p class="text-4xl mb-3">👈</p>
            <p class="font-medium">Pilih role di sebelah kiri</p>
            <p class="text-sm mt-1">untuk melihat daftar pertanyaan</p>
        </div>

        {{-- Per role content --}}
        @foreach($roles as $role)
        <div id="role-{{ $role->id }}" class="role-content hidden">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $role->name }}</h3>
                        <span class="text-xs text-gray-400">
                            {{ $role->questions->count() }} pertanyaan —
                            {{ $role->type === 'program' ? 'Menu Program' : 'Pertanyaan Biasa' }}
                        </span>
                    </div>
                    <a href="{{ route('admin.questions.create') }}?role_id={{ $role->id }}"
                       class="text-sm bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700">
                        + Tambah
                    </a>
                </div>

                @if($role->questions->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-3 text-left w-12">No</th>
                            @if($role->type === 'program')
                                <th class="p-3 text-left">Menu Program</th>
                                <th class="p-3 text-left">Proses</th>
                                <th class="p-3 text-left w-28">Prog ID</th>
                            @else
                                <th class="p-3 text-left">Pertanyaan</th>
                            @endif
                            <th class="p-3 text-left w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->questions as $q)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 text-gray-400">{{ $q->order }}</td>
                            @if($role->type === 'program')
                                <td class="p-3 font-medium">{{ $q->menu_program }}</td>
                                <td class="p-3 text-gray-600 text-xs">{{ $q->proses }}</td>
                                <td class="p-3">
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                        {{ $q->prog_id }}
                                    </span>
                                </td>
                            @else
                                <td class="p-3">{{ $q->question }}</td>
                            @endif
                            <td class="p-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.questions.edit', $q) }}"
                                       class="text-blue-600 hover:underline text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.questions.destroy', $q) }}"
                                          onsubmit="return confirm('Hapus pertanyaan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center text-gray-400">
                    <p class="text-2xl mb-2">📝</p>
                    <p class="text-sm">Belum ada pertanyaan untuk role ini</p>
                    <a href="{{ route('admin.questions.create') }}?role_id={{ $role->id }}"
                       class="inline-block mt-3 text-sm text-red-600 hover:underline">
                        + Tambah pertanyaan pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function showRole(id) {
    // Sembunyikan semua
    document.querySelectorAll('.role-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.role-btn').forEach(el => {
        el.classList.remove('bg-red-600', 'text-white');
        el.classList.add('hover:bg-red-50');
    });
    document.getElementById('defaultState').classList.add('hidden');

    // Tampilkan yang dipilih
    document.getElementById('role-' + id).classList.remove('hidden');
    const btn = document.getElementById('btn-' + id);
    btn.classList.add('bg-red-600', 'text-white');
    btn.classList.remove('hover:bg-red-50', 'hover:text-red-700');
}

// Auto pilih role pertama
@if($roles->count() > 0)
    showRole({{ $roles->first()->id }});
@endif
</script>
@endsection