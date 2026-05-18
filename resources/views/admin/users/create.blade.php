@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah User MD</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tipe User --}}
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;">Tipe User</label>
                <select name="user_type" id="userTypeSelect" onchange="toggleUserType(this.value)"
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;">
                    <option value="auditor" {{ old('user_type') === 'auditor' ? 'selected' : '' }}>Auditor MD</option>
                    <option value="kacab" {{ old('user_type') === 'kacab' ? 'selected' : '' }}>Kepala Cabang</option>
                </select>
            </div>

            {{-- Dealer (hanya untuk kacab) --}}
            <div id="dealerSection" style="display:none;margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;">Dealer</label>
                <select name="dealer_id" id="dealerSelect"
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;background:white;">
                    <option value="">-- Pilih Dealer --</option>
                    @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ old('dealer_id') == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Role (hanya untuk auditor) --}}
            <div class="mb-6" id="roleSection">
                <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-400 mb-2">Bisa pilih lebih dari satu role</p>
                <div class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                            class="rounded">
                        <span class="text-sm text-gray-700">{{ $role->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $role->type === 'program' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                            {{ $role->type === 'program' ? 'Program' : 'Pertanyaan' }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('roles')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Simpan User
            </button>
        </form>
    </div>
</div>

<script>
function toggleUserType(type) {
    const dealerSection = document.getElementById('dealerSection');
    const roleSection = document.getElementById('roleSection');
    const dealerSelect = document.getElementById('dealerSelect');

    if (type === 'kacab') {
        dealerSection.style.display = 'block';
        dealerSelect.setAttribute('required', 'required');
        roleSection.style.display = 'none';
    } else {
        dealerSection.style.display = 'none';
        dealerSelect.removeAttribute('required');
        dealerSelect.value = '';
        roleSection.style.display = 'block';
    }
}

// Jalankan saat halaman load
toggleUserType(document.getElementById('userTypeSelect').value);
</script>

@endsection