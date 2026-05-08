@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Role</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Checklist <span class="text-red-500">*</span></label>
                <select name="type" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                    <option value="question" {{ old('type') === 'question' ? 'selected' : '' }}>Pertanyaan</option>
                    <option value="program" {{ old('type') === 'program' ? 'selected' : '' }}>Menu Program</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Simpan Role
            </button>
        </form>
    </div>
</div>
@endsection