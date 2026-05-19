@extends('layouts.kacab')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Rekap Genba</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">{{ auth()->user()->dealer->name ?? '' }}</p>
</div>

{{-- Filter --}}
<div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;margin-bottom:20px;">
    <form method="GET" action="{{ route('kacab.rekap.index') }}" class="flex flex-wrap gap-4">

        {{-- Bulan --}}
        <div class="flex-1 min-w-32">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Bulan</label>
            <select name="month" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tahun --}}
        <div style="min-width:90px;">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Tahun</label>
            <select name="year" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- Role --}}
        <div class="flex-1 min-w-36">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Role</label>
            <select name="role_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="flex-1 min-w-32">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="draft"     {{ $status === 'draft'     ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex items-end gap-2">
            <button type="submit"
                style="background:#C8102E;color:white;padding:8px 18px;border-radius:8px;font-size:0.82rem;border:none;cursor:pointer;">
                Filter
            </button>
            <a href="{{ route('kacab.rekap.index') }}"
               style="background:#F3F4F6;color:#6B7280;padding:8px 14px;border-radius:8px;font-size:0.82rem;text-decoration:none;">
                Reset
            </a>
        </div>

    </form>
</div>

{{-- Info periode --}}
<p style="font-size:0.75rem;color:#9CA3AF;margin-bottom:12px;">
    Menampilkan data
    <strong style="color:#6B7280;">
        {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
    </strong>
    — {{ $sessions->total() }} sesi ditemukan
</p>

{{-- Aktivitas digroup per tanggal --}}
@forelse($sessionsByDate as $tanggal => $group)

<div style="margin-bottom:10px;border:1px solid #F3F4F6;border-radius:12px;overflow:hidden;">

    {{-- Header dropdown --}}
    <button type="button"
        onclick="toggleDropdown('tgl-{{ $loop->index }}')"
        style="width:100%;display:flex;justify-content:space-between;align-items:center;
               padding:12px 16px;background:#FAFAFA;border:none;cursor:pointer;text-align:left;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:0.82rem;font-weight:700;color:#1F2937;">📅 {{ $tanggal }}</span>
            <span style="background:#FEE2E2;color:#C8102E;font-size:0.7rem;font-weight:700;
                         padding:2px 8px;border-radius:100px;">
                {{ $group->count() }} sesi
            </span>
        </div>
        <span id="arrow-{{ $loop->index }}"
              style="font-size:0.75rem;color:#9CA3AF;transition:transform 0.2s;">▼</span>
    </button>

    {{-- Konten dropdown --}}
    <div id="tgl-{{ $loop->index }}" style="display:none;">
        @foreach($group as $session)
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:12px 16px;border-top:1px solid #F3F4F6;">
            <div>
                <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">
                    {{ $session->role->name }}
                </p>
                <p style="font-size:0.72rem;color:#9CA3AF;margin-top:3px;">
                    {{ $session->auditee_name }} · {{ $session->user->name ?? '-' }}
                </p>
                <p style="font-size:0.68rem;color:#D1D5DB;margin-top:2px;">
                    {{ $session->created_at->format('H:i') }}
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                @if($session->status === 'submitted')
                    <span style="padding:4px 12px;border-radius:100px;font-size:0.72rem;font-weight:700;
                        {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                        {{ $session->score }}%
                    </span>
                    <a href="{{ route('kacab.rekap.show', $session) }}"
                       style="font-size:0.75rem;color:#C8102E;font-weight:600;text-decoration:none;">
                        Detail →
                    </a>
                @else
                    <span style="font-size:0.72rem;background:#FEF3C7;color:#D97706;
                                 font-weight:600;padding:4px 10px;border-radius:100px;">Draft</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

</div>

@empty
<div style="background:white;border-radius:12px;padding:40px;text-align:center;color:#9CA3AF;">
    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
    <p>Belum ada data genba di periode ini</p>
</div>
@endforelse

{{-- Pagination --}}
<div class="mt-4">
    {{ $sessions->appends(request()->query())->links() }}
</div>

<script>
function toggleDropdown(id) {
    const el    = document.getElementById(id);
    const idx   = id.split('-')[1];
    const arrow = document.getElementById('arrow-' + idx);
    const isOpen = el.style.display !== 'none';
    el.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
}
</script>

@endsection