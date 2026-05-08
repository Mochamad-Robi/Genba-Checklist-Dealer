@extends('layouts.auditor')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- Header --}}
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, d F Y') }} — Selamat datang, <span class="text-red-600 font-medium">{{ auth()->user()->name }}</span></p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Genba</span>
            <i class="bi bi-clipboard-check text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $recentSessions->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua sesi</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Submitted</span>
            <i class="bi bi-check-circle-fill text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-green-600">{{ $recentSessions->where('status','submitted')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Selesai diisi</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Draft</span>
            <i class="bi bi-floppy-fill text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-yellow-500">{{ $recentSessions->where('status','draft')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Belum selesai</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">PICA</span>
            <i class="bi bi-pin-angle text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-red-500">{{ \App\Models\Pica::where('user_id', auth()->id())->where('status','open')->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Masih open</p>
    </div>
</div>

{{-- Filter Grafik --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center gap-2 mb-5">
        <i class="bi bi-search text-lg"></i>
        <h3 class="font-semibold text-gray-700">Filter Grafik</h3>
    </div>
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Dealer</label>
            <select name="dealer_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent bg-gray-50">
                <option value="">Semua Dealer</option>
                @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ $dealerId == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
            <select name="role_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent bg-gray-50">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent bg-gray-50">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                class="bg-red-600 text-white px-6 py-2.5 rounded-xl hover:bg-red-700 text-sm font-medium transition-all shadow-sm">
                Tampilkan
            </button>
            <a href="{{ route('auditor.dashboard') }}"
               class="bg-gray-100 text-gray-500 px-4 py-2.5 rounded-xl hover:bg-gray-200 text-sm transition-all">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Hasil Grafik --}}
@if($hasFilter)
    @if($sessions->count() > 0)
    @php $total = $totalPaham + $totalTidakPaham + $totalTidakDipakai; @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5 text-center">
            <p class="text-xs font-semibold text-green-500 uppercase tracking-wider mb-2">✅ Paham</p>
            <p class="text-4xl font-bold text-green-600">{{ $totalPaham }}</p>
            <p class="text-sm text-green-500 mt-1">{{ $total > 0 ? round(($totalPaham/$total)*100) : 0 }}%</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5 text-center">
            <p class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-2">❌ Tidak Paham</p>
            <p class="text-4xl font-bold text-red-600">{{ $totalTidakPaham }}</p>
            <p class="text-sm text-red-500 mt-1">{{ $total > 0 ? round(($totalTidakPaham/$total)*100) : 0 }}%</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">⬜ Tidak Dipakai</p>
            <p class="text-4xl font-bold text-gray-600">{{ $totalTidakDipakai }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $total > 0 ? round(($totalTidakDipakai/$total)*100) : 0 }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Grafik --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Grafik Indikator</h3>
            <canvas id="summaryChart" height="220"></canvas>
        </div>

        {{-- Progress Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-5">Distribusi Jawaban</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-green-700 font-medium">Paham</span>
                        <span class="font-bold text-green-600">{{ $totalPaham }} jawaban</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="bg-green-500 h-4 rounded-full transition-all"
                             style="width: {{ $total > 0 ? round(($totalPaham/$total)*100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ $total > 0 ? round(($totalPaham/$total)*100) : 0 }}%</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-red-700 font-medium">Tidak Paham</span>
                        <span class="font-bold text-red-600">{{ $totalTidakPaham }} jawaban</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="bg-red-500 h-4 rounded-full transition-all"
                             style="width: {{ $total > 0 ? round(($totalTidakPaham/$total)*100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ $total > 0 ? round(($totalTidakPaham/$total)*100) : 0 }}%</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-700 font-medium">Tidak Dipakai</span>
                        <span class="font-bold text-gray-600">{{ $totalTidakDipakai }} jawaban</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="bg-gray-400 h-4 rounded-full transition-all"
                             style="width: {{ $total > 0 ? round(($totalTidakDipakai/$total)*100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ $total > 0 ? round(($totalTidakDipakai/$total)*100) : 0 }}%</p>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t flex justify-between text-sm text-gray-400">
                <span>Total sesi ditemukan</span>
                <span class="font-bold text-gray-700">{{ $sessions->count() }} sesi</span>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4">Detail Sesi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 rounded-xl">
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dealer</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Staf Dealer</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="p-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($sessions as $session)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 font-medium text-gray-800">{{ $session->dealer->name }}</td>
                        <td class="p-3 text-gray-500">{{ $session->role->name }}</td>
                        <td class="p-3 text-gray-500">{{ $session->auditee_name }}</td>
                        <td class="p-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $session->score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $session->score }}%
                            </span>
                        </td>
                        <td class="p-3 text-gray-400 text-xs">{{ $session->submitted_at?->format('d/m/Y H:i') }}</td>
                        <td class="p-3">
                            <a href="{{ route('auditor.genba.result', $session) }}"
                               class="text-red-600 hover:text-red-700 text-xs font-medium">Lihat →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
    new Chart(document.getElementById('summaryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Paham', 'Tidak Paham', 'Tidak Dipakai'],
            datasets: [{
                data: [{{ $totalPaham }}, {{ $totalTidakPaham }}, {{ $totalTidakDipakai }}],
                backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, font: { size: 12 } } }
            }
        }
    });
    </script>

    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center mb-6">
        <p class="text-5xl mb-4">📭</p>
        <p class="font-semibold text-gray-600">Tidak ada data ditemukan</p>
        <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian</p>
    </div>
    @endif

@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center mb-6">
    <p class="text-5xl mb-4">🔍</p>
    <p class="font-semibold text-gray-600">Pilih filter di atas</p>
    <p class="text-sm text-gray-400 mt-1">Pilih dealer, role, atau tanggal lalu klik Tampilkan</p>
</div>
@endif

{{-- Riwayat Terbaru --}}
@if($recentSessions->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-5">
        <h3 class="font-semibold text-gray-700">Riwayat Terbaru</h3>
        <a href="{{ route('auditor.genba.index') }}" class="text-xs text-red-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="space-y-3">
        @foreach($recentSessions as $session)
        <div class="flex justify-between items-center p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                    {{ $session->status === 'submitted' ? 'bg-green-100' : 'bg-yellow-100' }}">
                    {{ $session->status === 'submitted' ? '✅' : '💾' }}
                </div>
                <div>
                    <p class="font-medium text-gray-800 text-sm">{{ $session->dealer->name }}</p>
                    <p class="text-xs text-gray-400">{{ $session->role->name }} — {{ $session->auditee_name }}</p>
                    <p class="text-xs text-gray-300 mt-0.5">{{ $session->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($session->status === 'submitted')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $session->score >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $session->score }}%
                    </span>
                    <a href="{{ route('auditor.genba.result', $session) }}"
                       class="text-red-600 text-xs font-medium hover:underline">Lihat →</a>
                @else
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-medium">Draft</span>
                    <a href="{{ route('auditor.genba.fill', $session) }}"
                       class="text-red-600 text-xs font-medium hover:underline">Lanjutkan →</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection