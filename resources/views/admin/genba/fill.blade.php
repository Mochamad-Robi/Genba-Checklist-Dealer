@extends('layouts.admin')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.genba.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-1">Isi Checklist Atas Nama</h2>
    <p class="text-gray-500">{{ $session->auditee_name }} — {{ $session->role->name }} — {{ $session->dealer->name }}</p>
    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Diisi oleh: {{ auth()->user()->name }}</span>
</div>

<form method="POST" action="{{ route('admin.genba.submit', $session) }}">
    @csrf
    <div class="space-y-4">
        @foreach($questions as $index => $question)
        <div class="bg-white rounded-xl shadow p-5">
            <div class="mb-3">
                <span class="text-xs text-gray-400">No. {{ $index + 1 }}</span>
                @if($session->role->type === 'program')
                    <p class="font-semibold text-gray-800 mt-1">{{ $question->menu_program }}</p>
                    <p class="text-sm text-gray-600">{{ $question->proses }}</p>
                    @if($question->prog_id)
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded mt-1 inline-block">
                            {{ $question->prog_id }}
                        </span>
                    @endif
                @else
                    <p class="font-medium text-gray-800 mt-1">{{ $question->question }}</p>
                @endif
            </div>
            <div class="flex gap-3 mb-3">
                @foreach(['1' => ['Paham','green'], '2' => ['Tidak Paham','red'], '3' => ['Tidak Dipakai','gray']] as $val => $opt)
                <label class="flex-1 cursor-pointer">
                    <input type="radio"
                        name="answers[{{ $question->id }}][indicator]"
                        value="{{ $val }}"
                        class="hidden peer"
                        {{ isset($answers[$question->id]) && $answers[$question->id]->indicator === $val ? 'checked' : '' }}>
                    <div class="text-center py-2 px-3 rounded-lg border-2 border-gray-200 text-sm font-medium
                        peer-checked:border-{{ $opt[1] }}-500 peer-checked:bg-{{ $opt[1] }}-50 peer-checked:text-{{ $opt[1] }}-700
                        hover:border-{{ $opt[1] }}-300 transition-all">
                        {{ $opt[0] }}
                    </div>
                </label>
                @endforeach
            </div>
            <input type="text"
                name="answers[{{ $question->id }}][keterangan]"
                value="{{ $answers[$question->id]->keterangan ?? '' }}"
                placeholder="Keterangan (opsional)"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500">
        </div>
        @endforeach
    </div>
    <div class="mt-6">
        <button type="submit"
            class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-medium text-lg">
            Submit Checklist ✓
        </button>
    </div>
</form>
@endsection