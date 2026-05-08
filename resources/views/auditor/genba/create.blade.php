@extends('layouts.auditor')
@section('content')

<div class="max-w-lg mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('auditor.dashboard') }}"
           style="display:inline-flex;align-items:center;gap:6px;color:#9CA3AF;font-size:0.8rem;text-decoration:none;margin-bottom:12px;transition:color 0.2s;"
           onmouseover="this.style.color='#C8102E'" onmouseout="this.style.color='#9CA3AF'">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Mulai Genba</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">Isi data kunjungan sebelum memulai checklist</p>
    </div>

    {{-- Card --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;box-shadow:0 4px 24px rgba(0,0,0,0.06);overflow:hidden;">

        {{-- Card Header --}}
        <div style="background:linear-gradient(135deg,#0F0F0F,#1A1A1A);padding:20px 24px;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-20px;right:-20px;width:120px;height:120px;background:radial-gradient(circle,rgba(200,16,46,0.25) 0%,transparent 70%);pointer-events:none;"></div>
            <div style="position:relative;z-index:1;display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,#C8102E,#9B0B22);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-clipboard2-check text-white" style="font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Sesi Genba Baru</p>
                    <p style="color:#6B7280;font-size:0.72rem;">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div style="padding:24px;">
            <form method="POST" action="{{ route('auditor.genba.store') }}">
                @csrf

                {{-- Dealer --}}
<div style="margin-bottom:18px;">
    <label style="display:block;font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">
        Dealer yang Dikunjungi <span style="color:#C8102E;">*</span>
    </label>

    @if($todaySchedules->isNotEmpty())
        {{-- Ada jadwal hari ini --}}
        <div style="background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:12px;padding:14px;margin-bottom:12px;">

            @if($todaySchedules->count() === 1)
                {{-- 1 jadwal → otomatis --}}
                @php $schedule = $todaySchedules->first(); @endphp
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:white;border-radius:8px;border:1px solid #BBF7D0;">
                    <i class="bi bi-shop" style="color:#16A34A;"></i>
                    <p style="font-size:0.875rem;font-weight:600;color:#1F2937;">{{ $schedule->dealer->name }}</p>
                </div>
                <input type="hidden" name="dealer_id" value="{{ $schedule->dealer_id }}">
                <p style="font-size:0.68rem;color:#6B7280;margin-top:8px;">
                    <i class="bi bi-info-circle"></i> Dealer otomatis terisi sesuai jadwal
                </p>
            @else
                {{-- 2+ jadwal → pilih salah satu --}}
                <p style="font-size:0.72rem;color:#6B7280;margin-bottom:8px;">Kamu punya {{ $todaySchedules->count() }} jadwal hari ini, pilih dealer mana dulu:</p>
                <div style="position:relative;">
                    <i class="bi bi-shop" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
                    <select name="dealer_id" required
                        style="width:100%;border:1.5px solid #BBF7D0;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;background:white;appearance:none;outline:none;"
                        onfocus="this.style.borderColor='#C8102E'"
                        onblur="this.style.borderColor='#BBF7D0'">
                        <option value="">-- Pilih Dealer --</option>
                        @foreach($todaySchedules as $schedule)
                        <option value="{{ $schedule->dealer_id }}">
                            {{ $schedule->dealer->name }}
                        </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.75rem;pointer-events:none;"></i>
                </div>
            @endif
        </div>

    @else
        {{-- Tidak ada jadwal → pilih manual --}}
        <div style="background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:10px;padding:10px 12px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#D97706;font-size:0.85rem;"></i>
            <p style="font-size:0.75rem;color:#92400E;">Tidak ada jadwal hari ini. Pilih dealer secara manual.</p>
        </div>
        <div style="position:relative;">
            <i class="bi bi-shop" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
            <select name="dealer_id" required
                style="width:100%;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;background:white;appearance:none;outline:none;transition:all 0.2s;"
                onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                <option value="">-- Pilih Dealer --</option>
                @foreach($dealers as $dealer)
                <option value="{{ $dealer->id }}" {{ old('dealer_id') == $dealer->id ? 'selected' : '' }}>
                    {{ $dealer->name }}
                </option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.75rem;pointer-events:none;"></i>
        </div>
    @endif

    @error('dealer_id')
    <p style="color:#EF4444;font-size:0.72rem;margin-top:5px;display:flex;align-items:center;gap:4px;">
        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
    </p>
    @enderror
</div>

                {{-- Role --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">
                        Role yang Diaudit <span style="color:#C8102E;">*</span>
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-shield-check" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
                        <select name="role_id" required
                            style="width:100%;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;background:white;appearance:none;outline:none;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.75rem;pointer-events:none;"></i>
                    </div>
                    @error('role_id')
                    <p style="color:#EF4444;font-size:0.72rem;margin-top:5px;display:flex;align-items:center;gap:4px;">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:#F9FAFB;margin:20px 0;"></div>

                {{-- Toggle Atas Nama --}}
                <div style="margin-bottom:18px;">
                    <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:12px 14px;border-radius:10px;background:#F9FAFB;border:1.5px solid #E5E7EB;transition:all 0.2s;"
                           id="behalfToggleLabel">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:8px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-fill-gear" style="color:#C8102E;font-size:0.9rem;"></i>
                            </div>
                            <div>
                                <p style="font-size:0.82rem;font-weight:600;color:#1F2937;">Isi Atas Nama User Lain</p>
                                <p style="font-size:0.7rem;color:#9CA3AF;margin-top:1px;">Aktifkan jika mengisi untuk user yang tidak hadir</p>
                            </div>
                        </div>
                        {{-- Toggle switch --}}
                        <div style="position:relative;width:44px;height:24px;flex-shrink:0;">
                            <input type="checkbox" id="behalfToggle" name="is_behalf" value="1"
                                style="opacity:0;position:absolute;width:100%;height:100%;cursor:pointer;z-index:1;"
                                onchange="toggleBehalf(this)">
                            <div id="toggleTrack" style="width:44px;height:24px;background:#E5E7EB;border-radius:100px;transition:all 0.2s;"></div>
                            <div id="toggleThumb" style="position:absolute;top:3px;left:3px;width:18px;height:18px;background:white;border-radius:50%;transition:all 0.2s;box-shadow:0 1px 4px rgba(0,0,0,0.2);"></div>
                        </div>
                    </label>
                </div>

                {{-- Section: Atas Nama (hidden by default) --}}
                <div id="behalfSection" style="display:none;margin-bottom:18px;">
                    <div style="background:#FFF5F5;border:1.5px solid #FED7D7;border-radius:12px;padding:14px;margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <i class="bi bi-info-circle-fill" style="color:#C8102E;font-size:0.85rem;"></i>
                            <p style="font-size:0.78rem;font-weight:600;color:#C8102E;">Mode Atas Nama Aktif</p>
                        </div>
                        <p style="font-size:0.72rem;color:#9B2335;line-height:1.5;">
                            Kamu mengisi checklist atas nama user lain. Nama yang tercatat di hasil tetap nama user yang dipilih, bukan namamu.
                        </p>
                    </div>

                    <label style="display:block;font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">
                        Pilih User yang Digantikan <span style="color:#C8102E;">*</span>
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-person-fill" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
                        <select name="behalf_user_id" id="behalfUserSelect"
                            style="width:100%;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;background:white;appearance:none;outline:none;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'"
                            onchange="fillAuditeeName(this)">
                            <option value="">-- Pilih User --</option>
                            @foreach($auditors as $auditor)
                                @if($auditor->id !== auth()->id())
                                <option value="{{ $auditor->id }}" data-name="{{ $auditor->name }}">
                                    {{ $auditor->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.75rem;pointer-events:none;"></i>
                    </div>
                </div>

                {{-- Nama Staf --}}
                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">
                        Nama Staf Dealer <span style="color:#C8102E;">*</span>
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-person" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
                        <input type="text" name="auditee_name" id="auditeeName" value="{{ old('auditee_name') }}"
                            placeholder="Nama staf yang diwawancara" required
                            style="width:100%;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;outline:none;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                    @error('auditee_name')
                    <p style="color:#EF4444;font-size:0.72rem;margin-top:5px;display:flex;align-items:center;gap:4px;">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Honda ID --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">
                        Honda ID Staf
                        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#D1D5DB;margin-left:6px;">Opsional</span>
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-card-text" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9CA3AF;font-size:0.9rem;pointer-events:none;"></i>
                        <input type="text" name="honda_id" value="{{ old('honda_id') }}"
                            placeholder="Masukkan Honda ID"
                            style="width:100%;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px 10px 36px;font-size:0.875rem;color:#1F2937;outline:none;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    style="width:100%;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:13px;border-radius:12px;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;box-shadow:0 4px 15px rgba(200,16,46,0.35);transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;"
                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 25px rgba(200,16,46,0.45)'"
                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(200,16,46,0.35)'">
                    <i class="bi bi-clipboard2-check"></i>
                    Mulai Isi Checklist
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>
        </div>
    </div>
</div>

<script>
function toggleBehalf(checkbox) {
    const section = document.getElementById('behalfSection');
    const track = document.getElementById('toggleTrack');
    const thumb = document.getElementById('toggleThumb');

    if (checkbox.checked) {
        section.style.display = 'block';
        track.style.background = '#C8102E';
        thumb.style.left = '23px';
    } else {
        section.style.display = 'none';
        track.style.background = '#E5E7EB';
        thumb.style.left = '3px';
        // Reset auditee name
        document.getElementById('auditeeName').value = '';
        document.getElementById('behalfUserSelect').value = '';
    }
}

function fillAuditeeName(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption.value) {
        document.getElementById('auditeeName').value = selectedOption.dataset.name;
    }
}
</script>

@endsection