@extends('layouts.dealer')
@section('content')
<div class="text-center mb-8">
    <div class="text-6xl mb-3">{{ $score >= 70 ? '🎉' : '📝' }}</div>
    <h2 class="text-3xl font-bold text-gray-800">{{ $score }}%</h2>
    <p class="text-gray-500 mt-1">{{ $session->role->name }} — {{ $session->auditee_name }}</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-green-50 rounded-xl p-4 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $paham }}</p>
        <p class="text-sm text-green-700 mt-1">Paham</p>
    </div>
    <div class="bg-red-50 rounded-xl p-4 text-center">
        <p class="text-3xl font-bold text-red-600">{{ $tidakPaham }}</p>
        <p class="text-sm text-red-700 mt-1">Tidak Paham</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-4 text-center">
        <p class="text-3xl font-bold text-gray-600">{{ $tidakDipakai }}</p>
        <p class="text-sm text-gray-700 mt-1">Tidak Dipakai</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <canvas id="resultChart" height="200"></canvas>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-4">Detail Jawaban</h3>
    <div class="space-y-3">
        @foreach($session->answers as $answer)
        <div class="flex justify-between items-start p-3 border rounded-lg">
            <div class="flex-1">
                <p class="text-sm font-medium">{{ $answer->question->question ?? $answer->question->menu_program }}</p>
                @if($answer->keterangan)
                    <p class="text-xs text-gray-400 mt-1">{{ $answer->keterangan }}</p>
                @endif
            </div>
            <span class="ml-3 px-2 py-1 rounded-full text-xs font-semibold
                {{ $answer->indicator === '1' ? 'bg-green-100 text-green-700' :
                   ($answer->indicator === '2' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                {{ $answer->indicator === '1' ? 'Paham' : ($answer->indicator === '2' ? 'Tidak Paham' : 'Tidak Dipakai') }}
            </span>
        </div>
        @endforeach
    </div>
</div>

<div class="mt-6 text-center">
    <a href="{{ route('dealer.dashboard') }}"
       class="inline-block bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700">
        Kembali ke Dashboard
    </a>
</div>

<script>
new Chart(document.getElementById('resultChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paham', 'Tidak Paham', 'Tidak Dipakai'],
        datasets: [{ data: [{{ $paham }}, {{ $tidakPaham }}, {{ $tidakDipakai }}],
            backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endsection