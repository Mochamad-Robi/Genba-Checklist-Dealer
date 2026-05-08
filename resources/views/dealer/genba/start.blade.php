@extends('layouts.dealer')
@section('content')
<div class="bg-white rounded-xl shadow p-8 max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Mulai Genba</h2>
    <p class="text-gray-500 mb-6">Isi data diri sebelum memulai checklist</p>

    <form method="POST" action="{{ route('dealer.genba.createSession') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="auditee_name" value="{{ $user->name }}"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Honda ID</label>
            <input type="text" name="honda_id"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                placeholder="Opsional">
        </div>
        <button type="submit"
            class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-medium">
            Mulai Checklist →
        </button>
    </form>
</div>
@endsection