@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.questions.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Pertanyaan</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.questions.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role_id" id="roleSelect"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        data-type="{{ $role->type }}"
                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                        ({{ $role->type === 'program' ? 'Menu Program' : 'Pertanyaan' }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Info tipe --}}
            <div id="infoQuestion" class="hidden mb-4 p-3 bg-purple-50 border border-purple-200 rounded-lg text-sm text-purple-700">
                📝 Role ini menggunakan tipe <strong>Pertanyaan Biasa</strong>
            </div>
            <div id="infoProgram" class="hidden mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
                💻 Role ini menggunakan tipe <strong>Menu Program</strong> (dengan Prog ID)
            </div>

            {{-- Tipe Question --}}
            <div id="questionFields" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question" rows="3"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                        placeholder="Contoh: Apakah sudah memiliki aplikasi Sales Tools?">{{ old('question') }}</textarea>
                </div>
            </div>

            {{-- Tipe Program --}}
            <div id="programFields" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Menu Program <span class="text-red-500">*</span></label>
                    <input type="text" name="menu_program" value="{{ old('menu_program') }}"
                        placeholder="Contoh: Validasi SPK dari Sales Tools"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proses</label>
                    <textarea name="proses" rows="2"
                        placeholder="Contoh: Verifikasi Data, Cek Status Pengisian CDB"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('proses') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prog ID</label>
                    <input type="text" name="prog_id" value="{{ old('prog_id') }}"
                        placeholder="Contoh: SSO01_DMS"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <div id="orderField" class="hidden mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium hidden">
                Simpan Pertanyaan
            </button>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('roleSelect');

    roleSelect.addEventListener('change', function() {
        const type = this.options[this.selectedIndex].dataset.type;

        // Reset semua
        document.getElementById('questionFields').classList.add('hidden');
        document.getElementById('programFields').classList.add('hidden');
        document.getElementById('infoQuestion').classList.add('hidden');
        document.getElementById('infoProgram').classList.add('hidden');
        document.getElementById('orderField').classList.add('hidden');
        document.getElementById('submitBtn').classList.add('hidden');

        if (!type) return;

        // Tampilkan sesuai tipe
        if (type === 'program') {
            document.getElementById('programFields').classList.remove('hidden');
            document.getElementById('infoProgram').classList.remove('hidden');
        } else {
            document.getElementById('questionFields').classList.remove('hidden');
            document.getElementById('infoQuestion').classList.remove('hidden');
        }

        document.getElementById('orderField').classList.remove('hidden');
        document.getElementById('submitBtn').classList.remove('hidden');
    });

    // Trigger saat page load kalau ada old value
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection