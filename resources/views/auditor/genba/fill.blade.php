@extends('layouts.auditor')
@section('content')

{{-- Header --}}
<div class="mb-6">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <p style="font-size:0.72rem;font-weight:700;color:#C8102E;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">
                Sesi Genba Aktif
            </p>
            <h2 class="text-xl font-bold text-gray-900">{{ $role->name }}</h2>
            <p style="color:#9CA3AF;font-size:0.8rem;margin-top:3px;">
                <i class="bi bi-person"></i> {{ $session->auditee_name }}
                &nbsp;·&nbsp;
                <i class="bi bi-shop"></i> {{ $session->dealer->name }}
            </p>
        </div>
        {{-- Progress counter --}}
        <div style="background:white;border:1px solid #F3F4F6;border-radius:12px;padding:10px 16px;text-align:center;min-width:80px;">
            <p id="answeredCount" style="font-size:1.3rem;font-weight:800;color:#C8102E;">0</p>
            <p style="font-size:0.65rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">/ {{ count($questions) }} Dijawab</p>
        </div>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-left:4px solid #22C55E;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:#15803D;font-weight:500;">
    <i class="bi bi-check-circle-fill" style="flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#FFF5F5;border:1px solid #FED7D7;border-left:4px solid #C8102E;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:#9B2335;font-weight:500;">
    <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;color:#C8102E;"></i>
    {{ session('error') }}
</div>
@endif

{{-- Alert belum diisi --}}
<div id="alertBelumDiisi" style="display:none;background:#FFF5F5;border:1px solid #FED7D7;border-left:4px solid #C8102E;border-radius:10px;padding:12px 16px;margin-bottom:20px;">
    <p style="font-size:0.85rem;font-weight:700;color:#C8102E;margin-bottom:6px;">
        <i class="bi bi-exclamation-circle-fill"></i> Pertanyaan belum dijawab:
    </p>
    <div id="alertList" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
</div>

<form method="POST" action="{{ route('auditor.genba.submit', $session) }}" id="genbaForm">
    @csrf
    <input type="hidden" name="action" id="formAction" value="submit">

    <div class="space-y-2">
        @foreach($questions as $index => $question)
        @php
            $isAnswered = isset($answers[$question->id]) && $answers[$question->id]->indicator;
            $currentIndicator = $answers[$question->id]->indicator ?? null;
        @endphp

        <div style="background:white;border-radius:14px;border:1.5px solid {{ $isAnswered ? '#E5E7EB' : '#F3F4F6' }};overflow:hidden;transition:all 0.2s;"
             id="card-{{ $question->id }}">

            {{-- Accordion Header --}}
            <div onclick="toggleAccordion({{ $question->id }})"
                 style="display:flex;align-items:center;gap:12px;padding:16px;cursor:pointer;transition:background 0.15s;"
                 id="header-{{ $question->id }}"
                 onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='white'">

                {{-- Nomor --}}
                <span id="badge-{{ $question->id }}"
                    style="width:28px;height:28px;border-radius:8px;font-size:0.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;
                    {{ $isAnswered ? 'background:#C8102E;color:white;' : 'background:#F3F4F6;color:#9CA3AF;' }}">
                    @if($isAnswered)
                        <i class="bi bi-check-lg" style="font-size:0.8rem;"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </span>

                {{-- Pertanyaan --}}
                <div class="flex-1" style="min-width:0;">
                    @if($role->type === 'program')
                        <p style="font-weight:600;color:#1F2937;font-size:0.875rem;line-height:1.4;">
                            {{ $question->menu_program }}
                        </p>
                    @else
                        <p style="font-weight:500;color:#1F2937;font-size:0.875rem;line-height:1.4;">
                            {{ $question->question }}
                        </p>
                    @endif

                    {{-- Status indicator --}}
                    @if($isAnswered)
                    <span style="font-size:0.65rem;font-weight:600;padding:2px 8px;border-radius:100px;margin-top:3px;display:inline-block;
                        {{ $currentIndicator == '1' ? 'background:#F0FDF4;color:#16A34A;' : ($currentIndicator == '2' ? 'background:#FFF5F5;color:#DC2626;' : 'background:#F9FAFB;color:#6B7280;') }}">
                        {{ $currentIndicator == '1' ? '✅ Paham' : ($currentIndicator == '2' ? '❌ Tidak Paham' : '⬜ Tidak Dipakai') }}
                    </span>
                    @else
                    <span style="font-size:0.65rem;color:#D1D5DB;margin-top:2px;display:inline-block;">Belum dijawab</span>
                    @endif
                </div>

                {{-- Chevron --}}
                <i class="bi bi-chevron-down" id="chevron-{{ $question->id }}"
                   style="color:#9CA3AF;font-size:0.75rem;transition:transform 0.2s;flex-shrink:0;
                   {{ $index === 0 && !$isAnswered ? '' : '' }}"></i>
            </div>

            {{-- Accordion Content --}}
            <div id="content-{{ $question->id }}"
                 style="display:{{ $index === 0 ? 'block' : 'none' }};border-top:1px solid #F3F4F6;">

                <div style="padding:16px;">

                    {{-- Info program --}}
                    @if($role->type === 'program')
                    <div style="background:#F9FAFB;border-radius:10px;padding:12px;margin-bottom:14px;">
                        <p style="font-weight:600;color:#1F2937;font-size:0.875rem;">{{ $question->menu_program }}</p>
                        @if($question->proses)
                        <p style="color:#6B7280;font-size:0.8rem;margin-top:4px;">{{ $question->proses }}</p>
                        @endif
                        @if($question->prog_id)
                        <span style="display:inline-block;background:#EFF6FF;color:#2563EB;font-size:0.68rem;font-weight:600;padding:3px 8px;border-radius:6px;margin-top:6px;font-family:'DM Mono',monospace;">
                            {{ $question->prog_id }}
                        </span>
                        @endif
                    </div>
                    @endif

                    {{-- Indikator VERTIKAL --}}
                    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
                        @foreach(['1' => ['Paham','#22C55E','#F0FDF4','#16A34A','bi-check-circle-fill'], '2' => ['Tidak Paham','#EF4444','#FFF5F5','#DC2626','bi-x-circle-fill'], '3' => ['Tidak Dipakai','#9CA3AF','#F9FAFB','#6B7280','bi-dash-circle-fill']] as $val => $opt)
                        <label style="cursor:pointer;display:block;">
                            <input type="radio"
                                name="answers[{{ $question->id }}][indicator]"
                                value="{{ $val }}"
                                class="hidden peer indicator-radio"
                                data-question="{{ $question->id }}"
                                data-index="{{ $index + 1 }}"
                                @if(isset($answers[$question->id]) && $answers[$question->id]->indicator == $val) checked @endif>
                            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:10px;font-size:0.85rem;font-weight:600;transition:all 0.15s;cursor:pointer;
                                @if(isset($answers[$question->id]) && $answers[$question->id]->indicator == $val)
                                    border:2px solid {{ $opt[1] }};background:{{ $opt[2] }};color:{{ $opt[3] }};
                                @else
                                    border:2px solid #E5E7EB;background:white;color:#6B7280;
                                @endif"
                                class="indicator-display-{{ $question->id }}-{{ $val }}">
                                <i class="bi {{ $opt[4] }}" style="font-size:1rem;flex-shrink:0;"></i>
                                {{ $opt[0] }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <input type="text"
                            name="answers[{{ $question->id }}][keterangan]"
                            id="ket-{{ $question->id }}"
                            value="{{ $answers[$question->id]->keterangan ?? '' }}"
                            placeholder="Keterangan (opsional)"
                            style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:0.8rem;color:#374151;outline:none;transition:all 0.2s;background:#FAFAFA;"
                            onfocus="this.style.borderColor='#C8102E';this.style.background='white'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.background='#FAFAFA'">
                        <p id="ket-error-{{ $question->id }}" style="display:none;color:#EF4444;font-size:0.72rem;margin-top:5px;">
                            ⚠️ Keterangan wajib diisi untuk jawaban Tidak Paham / Tidak Dipakai
                        </p>
                    </div>

                    {{-- Tombol Next --}}
                    @if($index < count($questions) - 1)
                    @php $nextId = $questions[$index + 1]->id; @endphp
                    <div style="margin-top:14px;text-align:right;">
                        <button type="button" onclick="goToNext({{ $question->id }}, {{ $nextId }})"
                            style="background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:9px 20px;border-radius:10px;font-size:0.8rem;font-weight:600;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                            Pertanyaan Berikutnya <i class="bi bi-arrow-down"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Sticky Bottom Buttons --}}
    <div style="position:sticky;bottom:16px;margin-top:20px;z-index:10;">
        <div style="background:white;border:1px solid #F3F4F6;border-radius:16px;padding:12px;box-shadow:0 8px 32px rgba(0,0,0,0.12);display:flex;gap:10px;">
            <button type="button" onclick="saveDraft()"
                style="flex:1;background:#F3F4F6;color:#374151;padding:13px;border-radius:10px;font-weight:700;font-size:0.85rem;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s;"
                onmouseover="this.style.background='#E5E7EB'"
                onmouseout="this.style.background='#F3F4F6'">
                <i class="bi bi-floppy2"></i> Simpan Draft
            </button>
            <button type="button" onclick="submitForm()"
                style="flex:1;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:13px;border-radius:10px;font-weight:700;font-size:0.85rem;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 4px 15px rgba(200,16,46,0.35);transition:all 0.2s;">
                <i class="bi bi-check2-circle"></i> Submit Checklist
            </button>
        </div>
    </div>
</form>

<script>
const questionIds = @json($questions->pluck('id'));
const indicatorColors = {
    '1': { border: '#22C55E', bg: '#F0FDF4', color: '#16A34A' },
    '2': { border: '#EF4444', bg: '#FFF5F5', color: '#DC2626' },
    '3': { border: '#9CA3AF', bg: '#F9FAFB', color: '#6B7280' },
};

// Toggle accordion
function toggleAccordion(id) {
    const content = document.getElementById('content-' + id);
    const chevron = document.getElementById('chevron-' + id);
    const isOpen = content.style.display !== 'none';

    // Tutup semua
    questionIds.forEach(qId => {
        document.getElementById('content-' + qId).style.display = 'none';
        document.getElementById('chevron-' + qId).style.transform = 'rotate(0deg)';
    });

    // Buka yang diklik (kalau belum terbuka)
    if (!isOpen) {
        content.style.display = 'block';
        chevron.style.transform = 'rotate(180deg)';
        document.getElementById('card-' + id).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// Buka pertanyaan berikutnya
function goToNext(currentId, nextId) {
    // Tutup semua
    questionIds.forEach(qId => {
        document.getElementById('content-' + qId).style.display = 'none';
        document.getElementById('chevron-' + qId).style.transform = 'rotate(0deg)';
    });
    // Buka next
    document.getElementById('content-' + nextId).style.display = 'block';
    document.getElementById('chevron-' + nextId).style.transform = 'rotate(180deg)';
    document.getElementById('card-' + nextId).scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Update style indikator
function updateIndicatorStyle(questionId, selectedVal) {
    ['1', '2', '3'].forEach(val => {
        const div = document.querySelector(`.indicator-display-${questionId}-${val}`);
        if (!div) return;
        if (val === selectedVal) {
            const c = indicatorColors[val];
            div.style.border = `2px solid ${c.border}`;
            div.style.background = c.bg;
            div.style.color = c.color;
        } else {
            div.style.border = '2px solid #E5E7EB';
            div.style.background = 'white';
            div.style.color = '#6B7280';
        }
    });
}

// Update badge nomor jadi centang
function updateBadge(questionId, index, answered) {
    const badge = document.getElementById('badge-' + questionId);
    if (answered) {
        badge.style.background = '#C8102E';
        badge.style.color = 'white';
        badge.innerHTML = '<i class="bi bi-check-lg" style="font-size:0.8rem;"></i>';
    } else {
        badge.style.background = '#F3F4F6';
        badge.style.color = '#9CA3AF';
        badge.innerHTML = index;
    }
}

// Update status label di header
function updateHeaderStatus(questionId, val) {
    const header = document.getElementById('header-' + questionId);
    const statusEl = header.querySelector('.status-label');
    const labels = {
        '1': { text: '✅ Paham', bg: '#F0FDF4', color: '#16A34A' },
        '2': { text: '❌ Tidak Paham', bg: '#FFF5F5', color: '#DC2626' },
        '3': { text: '⬜ Tidak Dipakai', bg: '#F9FAFB', color: '#6B7280' },
    };

    const existing = header.querySelector('.status-label');
    if (existing) existing.remove();

    const span = document.createElement('span');
    span.className = 'status-label';
    span.style.cssText = `font-size:0.65rem;font-weight:600;padding:2px 8px;border-radius:100px;margin-top:3px;display:inline-block;background:${labels[val].bg};color:${labels[val].color};`;
    span.textContent = labels[val].text;

    const textDiv = header.querySelector('.flex-1');
    const oldStatus = textDiv.querySelector('span');
    if (oldStatus) oldStatus.remove();
    textDiv.appendChild(span);
}

// Update counter dijawab
function updateAnsweredCount() {
    const answered = document.querySelectorAll('.indicator-radio:checked').length;
    document.getElementById('answeredCount').textContent = answered;
}

// Event listener indikator
document.querySelectorAll('.indicator-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const questionId = this.dataset.question;
        const index = this.dataset.index;
        const ketInput = document.getElementById('ket-' + questionId);
        const ketError = document.getElementById('ket-error-' + questionId);

        updateIndicatorStyle(questionId, this.value);
        updateBadge(questionId, index, true);
        updateHeaderStatus(questionId, this.value);
        updateAnsweredCount();

        if (this.value === '2' || this.value === '3') {
            ketInput.placeholder = '⚠️ Wajib isi keterangan...';
            ketInput.style.borderColor = '#FCA5A5';
            ketInput.style.background = '#FFF5F5';
        } else {
            ketInput.placeholder = 'Keterangan (opsional)';
            ketInput.style.borderColor = '#E5E7EB';
            ketInput.style.background = '#FAFAFA';
            ketError.style.display = 'none';
        }
    });
});

// Trigger untuk jawaban yang sudah ada
document.querySelectorAll('.indicator-radio:checked').forEach(radio => {
    radio.dispatchEvent(new Event('change'));
});

// Buka pertama yang belum dijawab
window.addEventListener('load', function() {
    // Buka accordion pertama by default
    const firstId = questionIds[0];
    document.getElementById('content-' + firstId).style.display = 'block';
    document.getElementById('chevron-' + firstId).style.transform = 'rotate(180deg)';
});

function saveDraft() {
    document.getElementById('formAction').value = 'draft';
    document.getElementById('genbaForm').submit();
}

function submitForm() {
    let valid = true;
    let belumDijawab = [];

    // Cek semua pertanyaan sudah dijawab
    questionIds.forEach((qId, idx) => {
        const radio = document.querySelector(`input[name="answers[${qId}][indicator]"]:checked`);
        if (!radio) {
            valid = false;
            belumDijawab.push({ id: qId, no: idx + 1 });
        } else if (radio.value === '2' || radio.value === '3') {
            const ketInput = document.getElementById('ket-' + qId);
            if (!ketInput.value.trim()) {
                valid = false;
                belumDijawab.push({ id: qId, no: idx + 1 });
                document.getElementById('ket-error-' + qId).style.display = 'block';
                ketInput.style.borderColor = '#EF4444';
            }
        }
    });

    if (!valid) {
        // Tampilkan alert nomor yang belum diisi
        const alertEl = document.getElementById('alertBelumDiisi');
        const alertList = document.getElementById('alertList');
        alertList.innerHTML = '';

        belumDijawab.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = 'No. ' + item.no;
            btn.style.cssText = 'background:#C8102E;color:white;padding:4px 12px;border-radius:100px;font-size:0.75rem;font-weight:700;border:none;cursor:pointer;';
            btn.onclick = () => {
                // Buka accordion yang belum diisi
                questionIds.forEach(qId => {
                    document.getElementById('content-' + qId).style.display = 'none';
                    document.getElementById('chevron-' + qId).style.transform = 'rotate(0deg)';
                });
                document.getElementById('content-' + item.id).style.display = 'block';
                document.getElementById('chevron-' + item.id).style.transform = 'rotate(180deg)';
                document.getElementById('card-' + item.id).scrollIntoView({ behavior: 'smooth', block: 'center' });
                alertEl.style.display = 'none';
            };
            alertList.appendChild(btn);
        });

        alertEl.style.display = 'block';
        alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    document.getElementById('formAction').value = 'submit';
    document.getElementById('genbaForm').submit();
}
</script>

@endsection