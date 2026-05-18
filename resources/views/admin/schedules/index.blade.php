@extends('layouts.admin')

@section('content')

{{-- Pass Laravel schedule data to JS --}}
<script>
const SCHEDULES_DATA = {!! json_encode($schedulesJson) !!};
</script>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Jadwal Genba</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">Atur jadwal kunjungan auditor ke dealer</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Form Tambah Jadwal --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.04);height:fit-content;">
        <p style="font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;">
            + Tambah Jadwal
        </p>

        @if(session('success'))
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-left:4px solid #22C55E;border-radius:8px;padding:10px 14px;margin-bottom:16px;color:#15803D;font-size:0.8rem;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @csrf

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;color:#1F2937;outline:none;transition:all 0.2s;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                    onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'" required>
                @error('tanggal')<p style="color:#EF4444;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">User MD</label>
                <div style="max-height:200px;overflow-y:auto;border:1.5px solid #E5E7EB;border-radius:8px;padding:10px;background:white;">
                    @foreach($users as $user)
                    <label style="display:flex;align-items:center;gap:8px;padding:6px 4px;cursor:pointer;border-radius:6px;transition:background 0.15s;"
                           onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                        <input type="checkbox" name="user_id[]" value="{{ $user->id }}"
                            {{ is_array(old('user_id')) && in_array($user->id, old('user_id')) ? 'checked' : '' }}
                            style="accent-color:#C8102E;width:14px;height:14px;">
                        <span style="font-size:0.85rem;color:#1F2937;">{{ $user->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('user_id')<p style="color:#EF4444;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">Dealer</label>
                <select name="dealer_id" required
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;background:white;color:#1F2937;transition:all 0.2s;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                    onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    <option value="">-- Pilih Dealer --</option>
                    @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ old('dealer_id') == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                    @endforeach
                </select>
                @error('dealer_id')<p style="color:#EF4444;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">Catatan</label>
                <textarea name="catatan" rows="2"
                    placeholder="Opsional..."
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;resize:none;color:#1F2937;transition:all 0.2s;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                    onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">{{ old('catatan') }}</textarea>
            </div>

            <button type="submit"
                style="width:100%;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:11px;border-radius:10px;font-weight:700;font-size:0.875rem;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(200,16,46,0.3);transition:all 0.2s;"
                onmouseover="this.style.transform='translateY(-1px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="bi bi-calendar-plus"></i> Simpan Jadwal
            </button>
        </form>
    </div>

    {{-- Calendar View --}}
    <div class="md:col-span-2">
        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.04);">

            {{-- Calendar Header --}}
            <div style="padding:16px 20px;border-bottom:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <i class="bi bi-calendar3" style="color:#C8102E;font-size:1rem;"></i>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button id="cal-prev" onclick="changeMonth(-1)"
                            style="width:28px;height:28px;border-radius:7px;border:1px solid #E5E7EB;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.8rem;color:#6B7280;transition:all 0.15s;"
                            onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <span id="cal-month-label" style="font-size:0.85rem;font-weight:700;color:#1F2937;min-width:130px;text-align:center;"></span>
                        <button id="cal-next" onclick="changeMonth(1)"
                            style="width:28px;height:28px;border-radius:7px;border:1px solid #E5E7EB;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.8rem;color:#6B7280;transition:all 0.15s;"
                            onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="display:flex;align-items:center;gap:5px;font-size:0.68rem;color:#6B7280;">
                        <div style="width:8px;height:8px;border-radius:50%;background:#C8102E;"></div> Pending
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;font-size:0.68rem;color:#6B7280;">
                        <div style="width:8px;height:8px;border-radius:50%;background:#16A34A;"></div> Selesai
                    </div>
                    <span id="cal-total-badge" style="font-size:0.68rem;background:#FEE2E2;color:#C8102E;font-weight:700;padding:3px 10px;border-radius:100px;"></span>
                </div>
            </div>

            {{-- Day of Week Headers --}}
            <div style="display:grid;grid-template-columns:repeat(7,1fr);background:#F9FAFB;border-bottom:1px solid #F3F4F6;">
                @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $day)
                <div style="text-align:center;padding:8px 4px;font-size:0.65rem;font-weight:700;color:#9CA3AF;letter-spacing:0.05em;text-transform:uppercase;">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            {{-- Calendar Grid --}}
            <div id="cal-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:0;"></div>

            {{-- Detail Panel --}}
            <div id="detail-panel" style="border-top:1px solid #F3F4F6;">
                {{-- Header --}}
                <div style="padding:12px 20px;background:#F9FAFB;border-bottom:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-list-ul" style="color:#C8102E;font-size:0.85rem;"></i>
                        <span id="detail-title" style="font-size:0.8rem;font-weight:700;color:#374151;">Pilih tanggal untuk melihat jadwal</span>
                    </div>
                    <span id="detail-count" style="font-size:0.68rem;color:#9CA3AF;"></span>
                </div>
                {{-- Items --}}
                <div id="detail-body" style="max-height:280px;overflow-y:auto;">
                    <div style="padding:40px 20px;text-align:center;color:#9CA3AF;">
                        <i class="bi bi-calendar2-week" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#E5E7EB;"></i>
                        <p style="font-size:0.82rem;font-weight:600;color:#374151;">Klik tanggal di kalender</p>
                        <p style="font-size:0.72rem;margin-top:4px;">Detail jadwal akan muncul di sini</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.cal-cell {
    min-height: 72px;
    padding: 6px;
    border-right: 1px solid #F3F4F6;
    border-bottom: 1px solid #F3F4F6;
    cursor: pointer;
    transition: background 0.15s;
    position: relative;
    box-sizing: border-box;
}
.cal-cell:hover { background: #FFF5F5; }
.cal-cell.today { background: #FFF5F5; }
.cal-cell.selected { background: #FEE2E2 !important; }
.cal-cell.empty { cursor: default; background: #FAFAFA; }
.cal-cell.empty:hover { background: #FAFAFA; }
.cal-cell:nth-child(7n) { border-right: none; }
.cal-day-num {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 50%;
}
.cal-cell.today .cal-day-num {
    background: #C8102E;
    color: white;
    font-weight: 700;
}
.cal-cell.other-month .cal-day-num { color: #D1D5DB; }
.cal-dots { display: flex; flex-wrap: wrap; gap: 2px; margin-top: 2px; }
.cal-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.cal-dot.pending { background: #C8102E; }
.cal-dot.done { background: #16A34A; }
.cal-count-label {
    font-size: 0.58rem;
    color: #9CA3AF;
    margin-top: 2px;
    line-height: 1;
}
.sched-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    border-bottom: 1px solid #F9FAFB;
    transition: background 0.15s;
}
.sched-row:hover { background: #FAFAFA; }
.sched-row:last-child { border-bottom: none; }
.sched-av {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg,#C8102E,#9B0B22);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    flex-shrink: 0;
}
.sched-av.done { background: linear-gradient(135deg,#16A34A,#15803D); }
</style>

<script>
const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const DAYS_ID = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

// Schedules from Laravel — includes id for delete action
const SCHEDULES = SCHEDULES_DATA;

let currentYear, currentMonth, selectedDate = null;

(function init() {
    const now = new Date();
    currentYear = now.getFullYear();
    currentMonth = now.getMonth();
    renderCalendar();
})();

function changeMonth(dir) {
    currentMonth += dir;
    if (currentMonth > 11) { currentMonth = 0; currentYear++; }
    if (currentMonth < 0)  { currentMonth = 11; currentYear--; }
    selectedDate = null;
    renderCalendar();
    resetDetail();
}

function getSchedulesForDate(dateStr) {
    return SCHEDULES.filter(s => s.tanggal === dateStr);
}

function renderCalendar() {
    const grid = document.getElementById('cal-grid');
    const label = document.getElementById('cal-month-label');
    const badge = document.getElementById('cal-total-badge');

    label.textContent = MONTHS_ID[currentMonth] + ' ' + currentYear;

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const prevDays = new Date(currentYear, currentMonth, 0).getDate();

    const today = new Date();
    const todayStr = toDateStr(today.getFullYear(), today.getMonth(), today.getDate());

    // Count total for this month
    const monthKey = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}`;
    const monthTotal = SCHEDULES.filter(s => s.tanggal.startsWith(monthKey)).length;
    badge.textContent = monthTotal + ' jadwal bulan ini';

    let html = '';

    // Prev month filler
    for (let i = 0; i < firstDay; i++) {
        const d = prevDays - firstDay + 1 + i;
        html += `<div class="cal-cell empty other-month">
            <div class="cal-day-num" style="color:#D1D5DB;">${d}</div>
        </div>`;
    }

    // Current month days
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = toDateStr(currentYear, currentMonth, d);
        const items = getSchedulesForDate(dateStr);
        const isToday = dateStr === todayStr;
        const isSel = dateStr === selectedDate;
        const pending = items.filter(x => !x.is_done);
        const done = items.filter(x => x.is_done);

        const dots = [
            ...pending.slice(0, 4).map(() => `<div class="cal-dot pending"></div>`),
            ...done.slice(0, 4).map(() => `<div class="cal-dot done"></div>`),
        ].join('');

        html += `<div class="cal-cell${isToday?' today':''}${isSel?' selected':''}" onclick="selectDate('${dateStr}')">
            <div class="cal-day-num">${d}</div>
            ${items.length ? `
                <div class="cal-dots">${dots}</div>
                <div class="cal-count-label">${items.length} jadwal</div>
            ` : ''}
        </div>`;
    }

    // Fill remaining cells to complete grid row
    const totalCells = firstDay + daysInMonth;
    const remainder = totalCells % 7;
    if (remainder !== 0) {
        for (let d = 1; d <= 7 - remainder; d++) {
            html += `<div class="cal-cell empty other-month">
                <div class="cal-day-num" style="color:#D1D5DB;">${d}</div>
            </div>`;
        }
    }

    grid.innerHTML = html;
}

function selectDate(dateStr) {
    selectedDate = dateStr;
    renderCalendar();

    const items = getSchedulesForDate(dateStr);
    const [y, m, d] = dateStr.split('-').map(Number);
    const dayName = DAYS_ID[new Date(y, m-1, d).getDay()];
    const dateFormatted = `${dayName}, ${d} ${MONTHS_ID[m-1]} ${y}`;

    document.getElementById('detail-title').textContent = dateFormatted;
    document.getElementById('detail-count').textContent = items.length ? items.length + ' jadwal' : 'Tidak ada jadwal';

    const body = document.getElementById('detail-body');

    if (!items.length) {
        body.innerHTML = `
            <div style="padding:40px 20px;text-align:center;color:#9CA3AF;">
                <i class="bi bi-calendar-x" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#E5E7EB;"></i>
                <p style="font-size:0.82rem;font-weight:600;color:#374151;">Tidak ada jadwal</p>
                <p style="font-size:0.72rem;margin-top:4px;">Tambahkan jadwal melalui form di sebelah kiri</p>
            </div>`;
        return;
    }

    body.innerHTML = items.map(s => `
        <div class="sched-row">
            <div class="sched-av${s.is_done?' done':''}">
                ${s.user.slice(0,2).toUpperCase()}
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:0.82rem;font-weight:600;color:#1F2937;margin:0;">${s.user}</p>
                <p style="font-size:0.72rem;color:#9CA3AF;margin:2px 0 0;">
                    <i class="bi bi-shop" style="font-size:0.7rem;"></i> ${s.dealer}
                </p>
                ${s.catatan ? `<p style="font-size:0.68rem;color:#D97706;margin:2px 0 0;"><i class="bi bi-chat-left-text" style="font-size:0.65rem;"></i> ${s.catatan}</p>` : ''}
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                ${s.is_done
                    ? `<span style="font-size:0.65rem;background:#F0FDF4;color:#16A34A;font-weight:700;padding:3px 10px;border-radius:100px;border:1px solid #BBF7D0;">
                            <i class="bi bi-check-circle-fill"></i> Selesai
                       </span>`
                    : `<span style="font-size:0.65rem;background:#FEF3C7;color:#D97706;font-weight:700;padding:3px 10px;border-radius:100px;border:1px solid #FDE68A;">
                            <i class="bi bi-clock"></i> Pending
                       </span>`
                }
                <form method="POST" action="/admin/schedules/${s.id}" onsubmit="return confirm('Hapus jadwal ini?')" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="width:28px;height:28px;border-radius:7px;background:#FEE2E2;border:1px solid #FECACA;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;"
                        onmouseover="this.style.background='#FCA5A5'" onmouseout="this.style.background='#FEE2E2'">
                        <i class="bi bi-trash" style="color:#C8102E;font-size:0.7rem;"></i>
                    </button>
                </form>
            </div>
        </div>
    `).join('');
}

function resetDetail() {
    document.getElementById('detail-title').textContent = 'Pilih tanggal untuk melihat jadwal';
    document.getElementById('detail-count').textContent = '';
    document.getElementById('detail-body').innerHTML = `
        <div style="padding:40px 20px;text-align:center;color:#9CA3AF;">
            <i class="bi bi-calendar2-week" style="font-size:2.5rem;display:block;margin-bottom:10px;color:#E5E7EB;"></i>
            <p style="font-size:0.82rem;font-weight:600;color:#374151;">Klik tanggal di kalender</p>
            <p style="font-size:0.72rem;margin-top:4px;">Detail jadwal akan muncul di sini</p>
        </div>`;
}

function toDateStr(y, m, d) {
    return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
}
</script>

@endsection