@extends('layouts.auditor')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('auditor.pica.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah PICA</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('auditor.pica.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dealer <span class="text-red-500">*</span></label>
                    <select name="dealer_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                        <option value="">-- Pilih Dealer --</option>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}" {{ old('dealer_id') == $dealer->id ? 'selected' : '' }}>
                            {{ $dealer->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('dealer_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">PIC <span class="text-red-500">*</span></label>
                    <input type="text" name="pic" value="{{ old('pic') }}"
                        placeholder="Nama penanggung jawab"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                    @error('pic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Masalah / Temuan <span class="text-red-500">*</span></label>
                <textarea name="masalah" rows="3"
                    placeholder="Deskripsikan masalah atau temuan saat genba"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>{{ old('masalah') }}</textarea>
                @error('masalah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Analisa</label>
                <textarea name="analisa" rows="2"
                    placeholder="Analisa penyebab masalah"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('analisa') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                <textarea name="tindakan" rows="2"
                    placeholder="Tindakan yang akan/sudah diambil"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('tindakan') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
                    <input type="date" name="target_date" value="{{ old('target_date') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                        <option value="open" {{ old('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="on_progress" {{ old('status') === 'on_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-medium">
                Simpan PICA
            </button>
        </form>
    </div>
</div>
@endsection