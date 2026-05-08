@extends('layouts.admin')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Jadwal Genba</h2>
        <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">Atur jadwal kunjungan auditor ke dealer</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Form Tambah Jadwal --}}
    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
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
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;color:#1F2937;outline:none;transition:all 0.2s;"
                    onfocus="this.style.borderColor='#C8102E';this.style.boxShadow='0 0 0 3px rgba(200,16,46,0.1)'"
                    onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'" required>
                @error('tanggal')<p style="color:#EF4444;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

           <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">
                    User MD
                </label>
            
                <div style="max-height:220px;overflow-y:auto;border:1.5px solid #E5E7EB;border-radius:8px;padding:10px;background:white;">
                    @foreach($users as $user)
                    <label style="display:flex;align-items:center;gap:8px;padding:6px 0;cursor:pointer;">
                        <input type="checkbox" name="user_id[]" value="{{ $user->id }}"
                            {{ is_array(old('user_id')) && in_array($user->id, old('user_id')) ? 'checked' : '' }}>
                        <span>{{ $user->name }}</span>
                    </label>
                    @endforeach
                </div>
            
                @error('user_id')
                <p style="color:#EF4444;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">Dealer</label>
                <select name="dealer_id" required
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;background:white;color:#1F2937;transition:all 0.2s;"
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
                    style="width:100%;border:1.5px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:0.875rem;outline:none;resize:none;color:#1F2937;transition:all 0.2s;"
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

    {{-- Timeline Jadwal --}}
    <div class="md:col-span-2">
        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
            <div style="padding:16px 20px;border-bottom:1px solid #F9FAFB;display:flex;justify-content:space-between;align-items:center;">
                <p style="font-size:0.72rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;">
                    <i class="bi bi-calendar-week" style="color:#C8102E;"></i> Timeline Jadwal
                </p>
                <span style="font-size:0.72rem;color:#9CA3AF;">{{ $schedules->total() }} jadwal</span>
            </div>

            @php
                $grouped = $schedules->getCollection()->groupBy(fn($s) => $s->tanggal->format('Y-m-d'));
            @endphp

            <div style="max-height:620px;overflow-y:auto;">
                @forelse($grouped as $date => $items)
                <div style="padding:16px 20px;border-bottom:1px solid #F9FAFB;">

                    {{-- Date Header --}}
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;
                            {{ \Carbon\Carbon::parse($date)->isToday() ? 'background:linear-gradient(135deg,#C8102E,#9B0B22);' : 'background:#F3F4F6;' }}">
                            <p style="font-size:0.58rem;font-weight:700;line-height:1;
                                {{ \Carbon\Carbon::parse($date)->isToday() ? 'color:rgba(255,255,255,0.7);' : 'color:#9CA3AF;' }}">
                                {{ \Carbon\Carbon::parse($date)->format('M') }}
                            </p>
                            <p style="font-size:1rem;font-weight:800;line-height:1.1;
                                {{ \Carbon\Carbon::parse($date)->isToday() ? 'color:white;' : 'color:#1F2937;' }}">
                                {{ \Carbon\Carbon::parse($date)->format('d') }}
                            </p>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <p style="font-size:0.85rem;font-weight:700;color:#1F2937;">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                                </p>
                                @if(\Carbon\Carbon::parse($date)->isToday())
                                <span style="font-size:0.65rem;background:#FEE2E2;color:#C8102E;font-weight:700;padding:2px 8px;border-radius:100px;">
                                    Hari ini
                                </span>
                                @elseif(\Carbon\Carbon::parse($date)->isPast())
                                <span style="font-size:0.65rem;background:#F3F4F6;color:#9CA3AF;font-weight:600;padding:2px 8px;border-radius:100px;">
                                    Lewat
                                </span>
                                @else
                                <span style="font-size:0.65rem;background:#EFF6FF;color:#2563EB;font-weight:600;padding:2px 8px;border-radius:100px;">
                                    Mendatang
                                </span>
                                @endif
                            </div>
                            <p style="font-size:0.72rem;color:#9CA3AF;margin-top:2px;">{{ $items->count() }} jadwal</p>
                        </div>
                    </div>

                    {{-- Schedule Items --}}
                    <div style="padding-left:50px;">
                        @foreach($items as $schedule)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#F9FAFB;border-radius:10px;margin-bottom:6px;border:1px solid #F3F4F6;transition:all 0.2s;"
                             onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#C8102E,#9B0B22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <span style="color:white;font-size:0.65rem;font-weight:700;">
                                        {{ strtoupper(substr($schedule->user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p style="font-size:0.82rem;font-weight:600;color:#1F2937;">{{ $schedule->user->name }}</p>
                                    <p style="font-size:0.72rem;color:#9CA3AF;margin-top:1px;">
                                        <i class="bi bi-shop"></i> {{ $schedule->dealer->name }}
                                    </p>
                                    @if($schedule->catatan)
                                    <p style="font-size:0.68rem;color:#D97706;margin-top:2px;">
                                        <i class="bi bi-chat-left-text"></i> {{ $schedule->catatan }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if($schedule->is_done)
                                <span style="font-size:0.65rem;background:#F0FDF4;color:#16A34A;font-weight:700;padding:3px 10px;border-radius:100px;border:1px solid #BBF7D0;">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </span>
                                @else
                                <span style="font-size:0.65rem;background:#FEF3C7;color:#D97706;font-weight:700;padding:3px 10px;border-radius:100px;border:1px solid #FDE68A;">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                                @endif
                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                                      onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        style="width:30px;height:30px;border-radius:8px;background:#FEE2E2;border:1px solid #FECACA;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;"
                                        onmouseover="this.style.background='#FCA5A5'" onmouseout="this.style.background='#FEE2E2'">
                                        <i class="bi bi-trash" style="color:#C8102E;font-size:0.75rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div style="padding:60px 20px;text-align:center;color:#9CA3AF;">
                    <i class="bi bi-calendar-x" style="font-size:3rem;display:block;margin-bottom:12px;color:#E5E7EB;"></i>
                    <p style="font-weight:600;color:#374151;font-size:0.9rem;">Belum ada jadwal</p>
                    <p style="font-size:0.78rem;margin-top:4px;">Tambahkan jadwal di form sebelah kiri</p>
                </div>
                @endforelse
            </div>

            <div style="padding:12px 20px;border-top:1px solid #F9FAFB;">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>

@endsection