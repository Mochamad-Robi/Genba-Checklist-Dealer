@extends('layouts.admin')
@section('content')

<div class="mb-5">
    <a href="{{ route('admin.evidence.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:#9CA3AF;font-size:0.8rem;text-decoration:none;margin-bottom:10px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Evidence Foto</h2>
    <p style="color:#9CA3AF;font-size:0.8rem;margin-top:2px;">
        {{ $dealer->name }} &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
    </p>
</div>

@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-left:4px solid #22C55E;border-radius:10px;padding:10px 14px;margin-bottom:14px;color:#15803D;font-size:0.82rem;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#FFF5F5;border:1px solid #FED7D7;border-left:4px solid #C8102E;border-radius:10px;padding:10px 14px;margin-bottom:14px;color:#9B2335;font-size:0.82rem;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
</div>
@endif

@php
    $totalFotoAll = $sessions->sum(fn($s) => $s->evidences->count());
    $totalKuota   = $sessions->count() * 2;
    $fotoFree     = \App\Models\GenbaEvidence::where('dealer_id', $dealer->id)
                        ->where('tanggal_kunjungan', $tanggal)
                        ->whereNull('session_id')
                        ->with('uploader')
                        ->get();
    $sisaFree     = 4 - $fotoFree->count();
@endphp

<div style="display:grid;grid-template-columns:280px 1fr;gap:14px;align-items:start;">

    {{-- KIRI: Accordion + Upload Bebas --}}
    <div style="display:flex;flex-direction:column;gap:6px;">

        {{-- Summary --}}
        <div style="background:white;border-radius:10px;border:1px solid #F3F4F6;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
            <div>
                <p style="font-size:0.78rem;font-weight:700;color:#1F2937;">Sesi Genba</p>
                <p style="font-size:0.65rem;color:#9CA3AF;">{{ $sessions->count() }} role · {{ $totalFotoAll }}/{{ $totalKuota }} foto</p>
            </div>
            @if($totalFotoAll == $totalKuota)
            <span style="font-size:0.6rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:2px 8px;border-radius:100px;border:1px solid #BBF7D0;"><i class="bi bi-check-circle-fill"></i> Lengkap</span>
            @else
            <span style="font-size:0.6rem;background:#FEF3C7;color:#D97706;font-weight:600;padding:2px 8px;border-radius:100px;border:1px solid #FDE68A;"><i class="bi bi-exclamation-circle"></i> {{ $totalKuota - $totalFotoAll }} kurang</span>
            @endif
        </div>

        {{-- Accordion Per Role --}}
        @foreach($sessions as $session)
        @php
            $fotoCount = $session->evidences->count();
            $sisaFoto  = 2 - $fotoCount;
            $inputId   = 'fotoInput_' . $session->id;
            $previewId = 'fotoPreview_' . $session->id;
            $bodyId    = 'accBody_' . $session->id;
        @endphp
        <div style="background:white;border-radius:10px;border:1px solid #F3F4F6;overflow:hidden;">
            <div onclick="toggleAcc('{{ $bodyId }}')"
                 style="display:flex;align-items:center;justify-content:space-between;padding:9px 12px;cursor:pointer;user-select:none;"
                 onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='white'">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:26px;height:26px;border-radius:6px;background:#FFF5F5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person-badge" style="color:#C8102E;font-size:0.75rem;"></i>
                    </div>
                    <div>
                        <p style="font-size:0.75rem;font-weight:700;color:#1F2937;line-height:1.2;">{{ $session->role->name }}</p>
                        <p style="font-size:0.62rem;color:#9CA3AF;">{{ $session->user->name }}</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:5px;">
                    @if($fotoCount == 2)
                    <span style="font-size:0.6rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:1px 6px;border-radius:100px;border:1px solid #BBF7D0;">✓ 2/2</span>
                    @else
                    <span style="font-size:0.6rem;background:#F3F4F6;color:#9CA3AF;font-weight:600;padding:1px 6px;border-radius:100px;">{{ $fotoCount }}/2</span>
                    @endif
                    <i class="bi bi-chevron-down" id="icon_{{ $bodyId }}" style="color:#9CA3AF;font-size:0.7rem;transition:transform 0.2s;"></i>
                </div>
            </div>
            <div id="{{ $bodyId }}" style="display:none;border-top:1px solid #F3F4F6;padding:12px;">
                @if($sisaFoto > 0)
                <form method="POST" action="{{ route('admin.evidence.upload', [$dealer->id, $tanggal]) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <div style="border:2px dashed #E5E7EB;border-radius:8px;padding:10px;text-align:center;cursor:pointer;"
                         onclick="document.getElementById('{{ $inputId }}').click()"
                         onmouseover="this.style.borderColor='#C8102E';this.style.background='#FFF5F5'"
                         onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'">
                        <i class="bi bi-cloud-upload" style="font-size:1.1rem;color:#9CA3AF;display:block;margin-bottom:2px;"></i>
                        <p style="font-size:0.65rem;color:#9CA3AF;">Klik pilih foto · maks {{ $sisaFoto }}</p>
                    </div>
                    <input type="file" id="{{ $inputId }}" name="foto[]" multiple accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewFoto(this, '{{ $previewId }}')">
                    <div id="{{ $previewId }}" style="margin-top:6px;display:flex;gap:4px;"></div>
                    <input type="text" name="keterangan" placeholder="Keterangan (opsional)"
                        style="width:100%;border:1px solid #E5E7EB;border-radius:7px;padding:6px 8px;font-size:0.7rem;margin-top:6px;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#C8102E'" onblur="this.style.borderColor='#E5E7EB'">
                    <button type="submit" style="width:100%;margin-top:6px;background:linear-gradient(135deg,#C8102E,#9B0B22);color:white;padding:7px;border-radius:7px;font-weight:700;font-size:0.72rem;border:none;cursor:pointer;">
                        <i class="bi bi-cloud-upload"></i> Upload
                    </button>
                </form>
                @else
                <div style="text-align:center;padding:10px;background:#F9FAFB;border-radius:8px;">
                    <i class="bi bi-check-circle-fill" style="color:#16A34A;font-size:1rem;display:block;margin-bottom:3px;"></i>
                    <p style="font-size:0.7rem;font-weight:600;color:#374151;">Foto lengkap</p>
                    <p style="font-size:0.62rem;color:#9CA3AF;">Hapus foto untuk mengganti</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Upload Foto Bebas --}}
        <div style="background:white;border-radius:10px;border:1px solid #E5E7EB;border-style:dashed;overflow:hidden;margin-top:4px;">
            <div onclick="toggleAcc('accFree')"
                 style="display:flex;align-items:center;justify-content:space-between;padding:9px 12px;cursor:pointer;user-select:none;"
                 onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='white'">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:26px;height:26px;border-radius:6px;background:#F0F9FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-images" style="color:#0EA5E9;font-size:0.75rem;"></i>
                    </div>
                    <div>
                        <p style="font-size:0.75rem;font-weight:700;color:#1F2937;line-height:1.2;">Foto Bebas</p>
                        <p style="font-size:0.62rem;color:#9CA3AF;">Tambahan · maks 4 foto</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:5px;">
                    @if($fotoFree->count() > 0)
                    <span style="font-size:0.6rem;background:#F0F9FF;color:#0EA5E9;font-weight:600;padding:1px 6px;border-radius:100px;border:1px solid #BAE6FD;">{{ $fotoFree->count() }}/4</span>
                    @else
                    <span style="font-size:0.6rem;background:#F3F4F6;color:#9CA3AF;font-weight:600;padding:1px 6px;border-radius:100px;">0/4</span>
                    @endif
                    <i class="bi bi-chevron-down" id="icon_accFree" style="color:#9CA3AF;font-size:0.7rem;transition:transform 0.2s;"></i>
                </div>
            </div>
            <div id="accFree" style="display:none;border-top:1px solid #F3F4F6;padding:12px;">
                @if($sisaFree > 0)
                <form method="POST" action="{{ route('admin.evidence.upload-free', [$dealer->id, $tanggal]) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="border:2px dashed #E5E7EB;border-radius:8px;padding:10px;text-align:center;cursor:pointer;"
                         onclick="document.getElementById('fotoFreeInput').click()"
                         onmouseover="this.style.borderColor='#0EA5E9';this.style.background='#F0F9FF'"
                         onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'">
                        <i class="bi bi-cloud-upload" style="font-size:1.1rem;color:#9CA3AF;display:block;margin-bottom:2px;"></i>
                        <p style="font-size:0.65rem;color:#9CA3AF;">Klik pilih foto · maks {{ $sisaFree }}</p>
                    </div>
                    <input type="file" id="fotoFreeInput" name="foto[]" multiple accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewFoto(this, 'fotoFreePreview')">
                    <div id="fotoFreePreview" style="margin-top:6px;display:flex;gap:4px;"></div>
                    <input type="text" name="keterangan" placeholder="Keterangan (opsional)"
                        style="width:100%;border:1px solid #E5E7EB;border-radius:7px;padding:6px 8px;font-size:0.7rem;margin-top:6px;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#0EA5E9'" onblur="this.style.borderColor='#E5E7EB'">
                    <button type="submit" style="width:100%;margin-top:6px;background:linear-gradient(135deg,#0EA5E9,#0284C7);color:white;padding:7px;border-radius:7px;font-weight:700;font-size:0.72rem;border:none;cursor:pointer;">
                        <i class="bi bi-cloud-upload"></i> Upload Foto Bebas
                    </button>
                </form>
                @else
                <div style="text-align:center;padding:10px;background:#F9FAFB;border-radius:8px;">
                    <i class="bi bi-check-circle-fill" style="color:#0EA5E9;font-size:1rem;display:block;margin-bottom:3px;"></i>
                    <p style="font-size:0.7rem;font-weight:600;color:#374151;">4 foto bebas lengkap</p>
                    <p style="font-size:0.62rem;color:#9CA3AF;">Hapus foto untuk mengganti</p>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- KANAN: Grid Foto --}}
    <div style="display:flex;flex-direction:column;gap:12px;">

        {{-- Foto Per Role --}}
        <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:16px;">
            <p style="font-size:0.65rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">Foto Evidence per Role</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;">
                @foreach($sessions as $session)
                <div style="border:1px solid #F3F4F6;border-radius:10px;overflow:hidden;">
                    <div style="padding:6px 10px;background:#FAFAFA;border-bottom:1px solid #F3F4F6;display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:0.68rem;font-weight:700;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px;">{{ $session->role->name }}</p>
                        @if($session->evidences->count() == 2)
                        <span style="font-size:0.58rem;background:#F0FDF4;color:#16A34A;font-weight:600;padding:1px 5px;border-radius:100px;border:1px solid #BBF7D0;flex-shrink:0;">✓ 2/2</span>
                        @else
                        <span style="font-size:0.58rem;color:#9CA3AF;flex-shrink:0;">{{ $session->evidences->count() }}/2</span>
                        @endif
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:#F3F4F6;">
                        @foreach($session->evidences as $evidence)
                        <div style="position:relative;background:white;">
                            <img src="{{ asset('storage/' . $evidence->foto) }}"
                                 alt="Evidence"
                                 style="width:100%;aspect-ratio:1/1;object-fit:cover;display:block;cursor:pointer;"
                                 onclick="openLightbox('{{ asset('storage/' . $evidence->foto) }}')">
                            <form method="POST" action="{{ route('admin.evidence.destroy', $evidence) }}"
                                  onsubmit="return confirm('Hapus foto ini?')"
                                  style="position:absolute;top:4px;right:4px;">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:18px;height:18px;background:rgba(0,0,0,0.5);border:none;border-radius:3px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-trash" style="color:white;font-size:0.5rem;"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                        @for($i = $session->evidences->count(); $i < 2; $i++)
                        <div style="background:#FAFAFA;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-plus" style="color:#E5E7EB;font-size:1rem;"></i>
                        </div>
                        @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Foto Bebas --}}
        <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:16px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <p style="font-size:0.65rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.08em;">Foto Bebas</p>
                <span style="font-size:0.6rem;background:#F0F9FF;color:#0EA5E9;font-weight:600;padding:2px 8px;border-radius:100px;border:1px solid #BAE6FD;">{{ $fotoFree->count() }}/4</span>
            </div>
            @if($fotoFree->count() > 0)
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;">
                @foreach($fotoFree as $evidence)
                <div style="position:relative;border-radius:8px;overflow:hidden;border:1px solid #F3F4F6;">
                    <img src="{{ asset('storage/' . $evidence->foto) }}"
                         alt="Foto Bebas"
                         style="width:100%;aspect-ratio:1/1;object-fit:cover;display:block;cursor:pointer;"
                         onclick="openLightbox('{{ asset('storage/' . $evidence->foto) }}')">
                    <form method="POST" action="{{ route('admin.evidence.destroy', $evidence) }}"
                          onsubmit="return confirm('Hapus foto ini?')"
                          style="position:absolute;top:4px;right:4px;">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:18px;height:18px;background:rgba(0,0,0,0.5);border:none;border-radius:3px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-trash" style="color:white;font-size:0.5rem;"></i>
                        </button>
                    </form>
                    @if($evidence->keterangan)
                    <div style="padding:4px 6px;background:white;">
                        <p style="font-size:0.58rem;color:#6B7280;">{{ $evidence->keterangan }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
                {{-- Slot kosong --}}
                @for($i = $fotoFree->count(); $i < 4; $i++)
                <div style="aspect-ratio:1/1;border-radius:8px;border:2px dashed #F3F4F6;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-plus" style="color:#E5E7EB;font-size:1rem;"></i>
                </div>
                @endfor
            </div>
            @else
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
                @for($i = 0; $i < 4; $i++)
                <div style="aspect-ratio:1/1;border-radius:8px;border:2px dashed #F3F4F6;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;">
                    <i class="bi bi-image" style="color:#E5E7EB;font-size:1rem;"></i>
                    <p style="font-size:0.58rem;color:#E5E7EB;">Foto {{ $i + 1 }}</p>
                </div>
                @endfor
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;align-items:center;justify-content:center;" onclick="closeLightbox()">
    <img id="lightboxImg" src="" style="max-width:90%;max-height:90vh;border-radius:12px;object-fit:contain;">
    <button onclick="closeLightbox()" style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,0.2);border:none;color:white;width:40px;height:40px;border-radius:50%;cursor:pointer;font-size:1.2rem;">✕</button>
</div>

<script>
function toggleAcc(id) {
    const body = document.getElementById(id);
    const icon = document.getElementById('icon_' + id);
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}
function previewFoto(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML += `<div style="width:40px;height:40px;border-radius:5px;overflow:hidden;border:1px solid #E5E7EB;"><img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;"></div>`;
        };
        reader.readAsDataURL(file);
    });
}
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}
</script>

@endsection