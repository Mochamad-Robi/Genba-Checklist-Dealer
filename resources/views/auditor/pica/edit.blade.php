@extends('layouts.auditor')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('auditor.pica.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Edit PICA</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('auditor.pica.update', $pica) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dealer <span class="text-red-500">*</span></label>
                    <select name="dealer_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}" {{ $pica->dealer_id == $dealer->id ? 'selected' : '' }}>
                            {{ $dealer->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">PIC <span class="text-red-500">*</span></label>
                    <input type="text" name="pic" value="{{ old('pic', $pica->pic) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Masalah / Temuan <span class="text-red-500">*</span></label>
                <textarea name="masalah" rows="3"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>{{ old('masalah', $pica->masalah) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Analisa</label>
                <textarea name="analisa" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('analisa', $pica->analisa) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
                <textarea name="tindakan" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('tindakan', $pica->tindakan) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
                    <input type="date" name="target_date"
                        value="{{ old('target_date', $pica->target_date?->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                        <option value="open" {{ $pica->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="on_progress" {{ $pica->status === 'on_progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="closed" {{ $pica->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('keterangan', $pica->keterangan) }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-medium">
                Update PICA
            </button>
        </form>
    </div>
</div>
@endsection