@extends('layouts.kacab')
@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Rekap Genba</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:4px;">{{ auth()->user()->dealer->name ?? '' }}</p>
</div>

{{-- Filter --}}
<div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;margin-bottom:20px;">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-40">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Role</label>
            <select name="role_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-40">
            <label style="display:block;font-size:0.72rem;font-weight:600;color:#6B7280;margin-bottom:6px;">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
            <a href="{{ route('kacab.rekap.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm">Reset</a>
        </div>
    </form>
</div>

{{-- Sessions --}}
<div class="space-y-3">
    @forelse($sessions as $session)
    <div style="background:white;border:1px solid #F3F4F6;border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <p style="font-size:0.875rem;font-weight:700;color:#1F2937;">{{ $session->role->name }}</p>
            <p style="font-size:0.72rem;color:#9CA3AF;margin-top:3px;">
                {{ $session->auditee_name }} · {{ $session->user->name ?? '-' }}
            </p>
            <p style="font-size:0.68rem;color:#D1D5DB;margin-top:2px;">{{ $session->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            @if($session->status === 'submitted')
            <span style="padding:4px 12px;border-radius:100px;font-size:0.72rem;font-weight:700;
                {{ $session->score >= 70 ? 'background:#F0FDF4;color:#16A34A;' : 'background:#FFF5F5;color:#DC2626;' }}">
                {{ $session->score }}%
            </span>
            <a href="{{ route('kacab.rekap.show', $session) }}"
               style="font-size:0.75rem;color:#C8102E;font-weight:600;text-decoration:none;">Detail →</a>
            @else
            <span style="font-size:0.72rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:4px 10px;border-radius:100px;">Draft</span>
            @endif
        </div>
    </div>
    @empty
    <div style="background:white;border-radius:12px;padding:40px;text-align:center;color:#9CA3AF;">
        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:8px;color:#E5E7EB;"></i>
        <p>Belum ada data genba</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $sessions->links() }}</div>

@endsection