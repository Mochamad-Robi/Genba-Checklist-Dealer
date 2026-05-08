@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dealers.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Dealer</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.dealers.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Dealer <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 @error('code') border-red-500 @enderror"
                    placeholder="Contoh: DLR001" required>
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dealer <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                    required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('address') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>
            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Simpan Dealer
            </button>
        </form>
    </div>
</div>
@endsection