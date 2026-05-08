@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.genba.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Isi Atas Nama</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
            ⚠️ Fitur ini digunakan untuk mengisi checklist atas nama staf yang tidak dapat hadir.
        </div>
        <form method="POST" action="{{ route('admin.genba.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dealer <span class="text-red-500">*</span></label>
                <select name="dealer_id" id="dealerSelect"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                    <option value="">-- Pilih Dealer --</option>
                    @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role_id" id="roleSelect"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">User (Auditee) <span class="text-red-500">*</span></label>
                <select name="user_id"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
                    <option value="">-- Pilih User --</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Auditee <span class="text-red-500">*</span></label>
                <input type="text" name="auditee_name"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Honda ID</label>
                <input type="text" name="honda_id"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Mulai Isi Checklist
            </button>
        </form>
    </div>
</div>

<script>
// Load users by dealer & role
function loadUsers() {
    const dealerId = document.getElementById('dealerSelect').value;
    const roleId = document.getElementById('roleSelect').value;
    if (!dealerId || !roleId) return;

    fetch(`/admin/users/by-dealer-role?dealer_id=${dealerId}&role_id=${roleId}`)
        .then(r => r.json())
        .then(users => {
            const select = document.querySelector('select[name="user_id"]');
            select.innerHTML = '<option value="">-- Pilih User --</option>';
            users.forEach(u => {
                select.innerHTML += `<option value="${u.id}">${u.name}</option>`;
            });
        });
}

document.getElementById('dealerSelect').addEventListener('change', loadUsers);
document.getElementById('roleSelect').addEventListener('change', loadUsers);
</script>
@endsection