@extends('layouts.auditor')
@section('content')

@php
    $picaCount = \App\Models\Pica::where('session_id', $session->id)->count();
@endphp

{{-- Hero Score --}}
<div class="rounded-2xl p-8 mb-6 text-center relative overflow-hidden"
     style="background: linear-gradient(135deg, #0F0F0F, #1A1A1A); border: 1px solid rgba(255,255,255,0.07);">

    {{-- Background glow --}}
    <div style="position:absolute;top:-40px;left:50%;transform:translateX(-50%);width:300px;height:300px;
                background:radial-gradient(circle, {{ $score >= 70 ? 'rgba(34,197,94,0.15)' : 'rgba(200,16,46,0.15)' }} 0%, transparent 70%);
                pointer-events:none;"></div>

    <div class="relative z-10">
        <p class="text-xs font-bold uppercase tracking-widest mb-3"
           style="color:{{ $score >= 70 ? '#4ADE80' : '#F87171' }};">
            {{ $score >= 70 ? '✦ HASIL BAIK' : '✦ PERLU PENINGKATAN' }}
        </p>

        <div class="text-7xl font-black mb-2"
             style="color:{{ $score >= 70 ? '#22C55E' : '#EF4444' }};letter-spacing:-2px;line-height:1;">
            {{ $score }}%
        </div>

        <div style="width:60px;height:3px;background:{{ $score >= 70 ? '#22C55E' : '#EF4444' }};border-radius:2px;margin:16px auto;"></div>

        <p class="text-white font-semibold text-base">{{ $session->auditee_name }}</p>
        <p style="color:#6B7280;font-size:0.8rem;margin-top:4px;">
            {{ $session->role->name }} &nbsp;·&nbsp; {{ $session->dealer->name }}
        </p>
        <p style="color:#374151;font-size:0.72rem;margin-top:6px;">
            {{ $session->submitted_at?->format('d F Y, H:i') }}
        </p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    <div class="rounded-2xl p-4 text-center" style="background:#F0FDF4;border:1px solid #BBF7D0;">
        <p class="text-3xl font-black text-green-600">{{ $paham }}</p>
        <p style="font-size:0.72rem;color:#16A34A;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Paham</p>
    </div>
    <div class="rounded-2xl p-4 text-center" style="background:#FFF5F5;border:1px solid #FED7D7;">
        <p class="text-3xl font-black text-red-600">{{ $tidakPaham }}</p>
        <p style="font-size:0.72rem;color:#DC2626;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Tidak Paham</p>
    </div>
    <div class="rounded-2xl p-4 text-center" style="background:#F9FAFB;border:1px solid #E5E7EB;">
        <p class="text-3xl font-black text-gray-500">{{ $tidakDipakai }}</p>
        <p style="font-size:0.72rem;color:#6B7280;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em;">Tidak Dipakai</p>
    </div>
</div>

{{-- Grafik + Progress --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

    {{-- Doughnut --}}
    <div class="bg-white rounded-2xl p-6" style="border:1px solid #F3F4F6;">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Distribusi Jawaban</p>
        <div class="max-w-[200px] mx-auto">
            <canvas id="resultChart"></canvas>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="bg-white rounded-2xl p-6" style="border:1px solid #F3F4F6;">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-5">Persentase</p>
        @php $total = $paham + $tidakPaham + $tidakDipakai; @endphp

        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span style="font-size:0.8rem;font-weight:600;color:#16A34A;">Paham</span>
                    <span style="font-size:0.8rem;font-weight:700;color:#16A34A;">{{ $total > 0 ? round(($paham/$total)*100) : 0 }}%</span>
                </div>
                <div style="height:8px;background:#F0FDF4;border-radius:100px;overflow:hidden;">
                    <div style="height:100%;width:{{ $total > 0 ? round(($paham/$total)*100) : 0 }}%;background:linear-gradient(90deg,#22C55E,#16A34A);border-radius:100px;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span style="font-size:0.8rem;font-weight:600;color:#DC2626;">Tidak Paham</span>
                    <span style="font-size:0.8rem;font-weight:700;color:#DC2626;">{{ $total > 0 ? round(($tidakPaham/$total)*100) : 0 }}%</span>
                </div>
                <div style="height:8px;background:#FFF5F5;border-radius:100px;overflow:hidden;">
                    <div style="height:100%;width:{{ $total > 0 ? round(($tidakPaham/$total)*100) : 0 }}%;background:linear-gradient(90deg,#EF4444,#DC2626);border-radius:100px;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span style="font-size:0.8rem;font-weight:600;color:#6B7280;">Tidak Dipakai</span>
                    <span style="font-size:0.8rem;font-weight:700;color:#6B7280;">{{ $total > 0 ? round(($tidakDipakai/$total)*100) : 0 }}%</span>
                </div>
                <div style="height:8px;background:#F9FAFB;border-radius:100px;overflow:hidden;">
                    <div style="height:100%;width:{{ $total > 0 ? round(($tidakDipakai/$total)*100) : 0 }}%;background:linear-gradient(90deg,#9CA3AF,#6B7280);border-radius:100px;"></div>
                </div>
            </div>
        </div>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:0.75rem;color:#9CA3AF;">Total pertanyaan</span>
            <span style="font-size:0.85rem;font-weight:700;color:#1F2937;">{{ $total }}</span>
        </div>
    </div>
</div>

{{-- PICA Info --}}
@if($picaCount > 0)
<div class="rounded-2xl p-5 mb-6" style="background:linear-gradient(135deg,#FFF7ED,#FEF3C7);border:1px solid #FED7AA;">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div style="width:40px;height:40px;background:#F97316;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-tools text-white" style="font-size:1rem;"></i>
            </div>
            <div>
                <p style="font-size:0.85rem;font-weight:700;color:#C2410C;">{{ $picaCount }} Temuan Masuk ke PICA</p>
                <p style="font-size:0.75rem;color:#9A3412;margin-top:2px;">Lengkapi PIC, analisa & tindakan</p>
            </div>
        </div>
        <a href="{{ route('auditor.pica.index') }}"
           style="background:#F97316;color:white;padding:8px 16px;border-radius:10px;font-size:0.78rem;font-weight:600;text-decoration:none;white-space:nowrap;">
            Lihat →
        </a>
    </div>
</div>
@endif

{{-- Detail Jawaban --}}
<div class="bg-white rounded-2xl mb-6" style="border:1px solid #F3F4F6;overflow:hidden;">
    <div class="px-6 py-4" style="border-bottom:1px solid #F9FAFB;">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Detail Jawaban</p>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($session->answers as $index => $answer)
        <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
            <span style="width:24px;height:24px;border-radius:6px;background:#F3F4F6;color:#6B7280;font-size:0.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                {{ $index + 1 }}
            </span>
            <div class="flex-1 min-w-0">
                <p style="font-size:0.85rem;font-weight:500;color:#1F2937;line-height:1.4;">
                    {{ $answer->question->question ?? $answer->question->menu_program }}
                </p>
                @if($answer->keterangan)
                <p style="font-size:0.75rem;color:#9CA3AF;margin-top:4px;">{{ $answer->keterangan }}</p>
                @endif
            </div>
            <span style="flex-shrink:0;padding:4px 10px;border-radius:100px;font-size:0.72rem;font-weight:600;
                {{ $answer->indicator === '1' ? 'background:#F0FDF4;color:#16A34A;' :
                   ($answer->indicator === '2' ? 'background:#FFF5F5;color:#DC2626;' : 'background:#F9FAFB;color:#6B7280;') }}">
                {{ $answer->indicator === '1' ? 'Paham' : ($answer->indicator === '2' ? 'Tidak Paham' : 'Tidak Dipakai') }}
            </span>
        </div>
        @endforeach
    </div>
</div>

{{-- Tombol --}}
<div class="flex gap-3">
    <a href="{{ route('auditor.genba.create') }}"
       style="flex:1;text-align:center;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:14px;border-radius:12px;font-weight:700;font-size:0.875rem;text-decoration:none;box-shadow:0 4px 15px rgba(200,16,46,0.3);">
        + Genba Baru
    </a>
    <a href="{{ route('auditor.dashboard') }}"
       style="flex:1;text-align:center;background:#F3F4F6;color:#374151;padding:14px;border-radius:12px;font-weight:600;font-size:0.875rem;text-decoration:none;">
        Dashboard
    </a>
</div>

<script>
new Chart(document.getElementById('resultChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paham', 'Tidak Paham', 'Tidak Dipakai'],
        datasets: [{
            data: [{{ $paham }}, {{ $tidakPaham }}, {{ $tidakDipakai }}],
            backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 16,
                    font: { size: 11, weight: '600' },
                    usePointStyle: true,
                    pointStyleWidth: 8
                }
            }
        }
    }
});
</script>

@endsection