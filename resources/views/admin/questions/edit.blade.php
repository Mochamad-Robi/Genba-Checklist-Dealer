@extends('layouts.admin')
@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.questions.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Pertanyaan</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.questions.update', $question) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role_id" id="roleSelect"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" data-type="{{ $role->type }}"
                        {{ $question->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="questionFields" {{ $question->role->type === 'program' ? 'class=hidden' : '' }}>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                    <textarea name="question" rows="3"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">{{ old('question', $question->question) }}</textarea>
                </div>
            </div>

            <div id="programFields" {{ $question->role->type !== 'program' ? 'class=hidden' : '' }}>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Menu Program</label>
                    <input type="text" name="menu_program" value="{{ old('menu_program', $question->menu_program) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proses</label>
                    <input type="text" name="proses" value="{{ old('proses', $question->proses) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prog ID</label>
                    <input type="text" name="prog_id" value="{{ old('prog_id', $question->prog_id) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $question->order) }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium">
                Update Pertanyaan
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const type = this.options[this.selectedIndex].dataset.type;
    document.getElementById('questionFields').classList.toggle('hidden', type === 'program');
    document.getElementById('programFields').classList.toggle('hidden', type !== 'program');
});
</script>
@endsection