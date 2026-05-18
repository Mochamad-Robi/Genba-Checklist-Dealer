<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Genba — {{ $dealer->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
    --red:      #C8102E;
    --red-d:    #9B0B22;
    --red-pale: #FFF0F2;
    --red-bdr:  #FECDD3;
    --ink:      #111827;
    --ink-2:    #374151;
    --ink-3:    #6B7280;
    --ink-4:    #9CA3AF;
    --line:     #E5E7EB;
    --line-2:   #F3F4F6;
    --bg:       #F1F5F9;
    --white:    #FFFFFF;
    --green:    #059669;
    --green-bg: #ECFDF5;
    --green-bd: #6EE7B7;
    --amber:    #D97706;
    --amber-bg: #FFFBEB;
    --amber-bd: #FCD34D;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:var(--ink); background:var(--bg); -webkit-print-color-adjust:exact; print-color-adjust:exact; }

/* ── TOPBAR ── */
.topbar {
    background:#0F172A;
    padding:11px 32px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:relative;
    border-bottom:1px solid rgba(255,255,255,0.06);
}
.topbar-left { display:flex; align-items:center; gap:10px; }
.topbar-dot { width:8px; height:8px; border-radius:50%; background:var(--red); flex-shrink:0; }
.topbar-label { font-size:11px; font-weight:500; color:rgba(255,255,255,0.45); letter-spacing:0.04em; }
.topbar-label strong { color:rgba(255,255,255,0.85); font-weight:600; }
.btn-dl {
    background:var(--red); color:#fff; border:none;
    padding:8px 20px; border-radius:7px;
    font-family:'Plus Jakarta Sans',sans-serif;
    font-size:12px; font-weight:700; cursor:pointer;
    display:flex; align-items:center; gap:7px;
    transition:background .15s, transform .1s, opacity .15s;
    letter-spacing:0.02em;
}
.btn-dl:hover { background:var(--red-d); transform:translateY(-1px); }
.btn-dl:active { transform:translateY(0); }
.btn-dl:disabled { opacity:.55; cursor:not-allowed; transform:none; }

/* ── SHELL ── */
.shell {
    width:794px;
    margin:32px auto 56px;
}

/* ── HERO ── */
.hero {
    background:var(--red);
    border-radius:16px 16px 0 0;
    overflow:hidden; position:relative;
    padding:0;
}
.hero-noise {
    position:absolute; inset:0;
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    opacity:.4; pointer-events:none;
}
.hero-circle-1 { position:absolute; top:-80px; right:-80px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.06); pointer-events:none; }
.hero-circle-2 { position:absolute; bottom:-60px; left:25%; width:220px; height:220px; border-radius:50%; background:rgba(0,0,0,0.07); pointer-events:none; }
.hero-inner {
    position:relative; z-index:2;
    padding:32px 36px 28px;
    display:flex; justify-content:space-between; align-items:flex-start; gap:20px;
}
.hero-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,0.12);
    border:1px solid rgba(255,255,255,0.2);
    border-radius:99px; padding:3px 12px;
    font-size:9px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:rgba(255,255,255,0.8);
    margin-bottom:12px;
}
.hero-title { font-size:28px; font-weight:800; color:#fff; line-height:1.1; margin-bottom:8px; }
.hero-sub { font-size:13px; font-weight:500; color:rgba(255,255,255,0.75); }
.hero-right { text-align:right; flex-shrink:0; }
.hero-logo {
    height:48px; width:auto; object-fit:contain;
    display:block; margin-left:auto; margin-bottom:12px;
    filter:brightness(0) invert(1);
}
.hero-logo-text { font-size:26px; font-weight:900; letter-spacing:0.12em; color:#fff; margin-bottom:12px; display:none; }
.hero-meta { font-size:10px; color:rgba(255,255,255,0.65); line-height:1.9; text-align:right; }
.hero-meta strong { color:rgba(255,255,255,0.95); }
.hero-stripe { height:5px; background:linear-gradient(90deg, var(--red-d) 0%, #FF5A72 40%, #FF8C9A 60%, var(--red-d) 100%); }

/* ── CARD BODY ── */
.card {
    background:var(--white);
    border-radius:0 0 16px 16px;
    box-shadow:0 12px 48px rgba(0,0,0,0.10);
    padding:32px 36px 36px;
}

/* ── STAT ROW ── */
.stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:28px; }
.stat-box { border:1.5px solid var(--line); border-radius:10px; padding:14px 16px; transition:border-color .15s; }
.stat-box:hover { border-color:#D1D5DB; }
.stat-lbl { font-size:9px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--ink-4); margin-bottom:6px; }
.stat-val { font-size:18px; font-weight:800; color:var(--ink); line-height:1; }
.stat-val.red { color:var(--red); }

/* ── TRIPLE SCORE ── */
.triple { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:32px; }
.triple-card { border-radius:12px; padding:20px 16px; text-align:center; position:relative; overflow:hidden; }
.triple-card.g { background:var(--green-bg); border:1.5px solid var(--green-bd); }
.triple-card.r { background:var(--red-pale); border:1.5px solid var(--red-bdr); }
.triple-card.s { background:#F8FAFC; border:1.5px solid var(--line); }
.triple-icon { font-size:18px; margin-bottom:6px; display:block; }
.triple-num { font-size:42px; font-weight:800; line-height:1; margin-bottom:6px; }
.triple-card.g .triple-num { color:var(--green); }
.triple-card.r .triple-num { color:var(--red); }
.triple-card.s .triple-num { color:var(--ink-3); }
.triple-lbl { font-size:10px; font-weight:700; letter-spacing:0.05em; }
.triple-card.g .triple-lbl { color:var(--green); }
.triple-card.r .triple-lbl { color:var(--red); }
.triple-card.s .triple-lbl { color:var(--ink-3); }
.triple-corner { position:absolute; bottom:-12px; right:-12px; width:60px; height:60px; border-radius:50%; opacity:.07; }
.triple-card.g .triple-corner { background:var(--green); }
.triple-card.r .triple-corner { background:var(--red); }
.triple-card.s .triple-corner { background:#94A3B8; }

/* ── SECTION TITLE ── */
.sec { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
.sec-bar { width:4px; height:16px; background:var(--red); border-radius:3px; flex-shrink:0; }
.sec-title { font-size:10px; font-weight:800; letter-spacing:0.12em; text-transform:uppercase; color:var(--ink-3); }
.sec-count { font-size:10px; font-weight:600; color:var(--ink-4); margin-left:auto; }

/* ── DIVIDER ── */
.div { border:none; border-top:1px solid var(--line-2); margin:28px 0; }

/* ── BAR CHART ── */
.bars { margin-bottom:28px; }
.bar-row { display:flex; align-items:center; gap:12px; margin-bottom:10px; }
.bar-name { width:148px; font-size:11px; font-weight:600; color:var(--ink-2); flex-shrink:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bar-track { flex:1; background:var(--line-2); border-radius:99px; height:9px; overflow:hidden; }
.bar-fill { height:9px; border-radius:99px; }
.bar-pct { width:40px; text-align:right; font-size:11px; font-weight:800; flex-shrink:0; }

/* ── ROLE BLOCK ── */
.role-block { margin-bottom:24px; }
.role-head {
    display:flex; align-items:center; gap:8px;
    background:linear-gradient(135deg, var(--red-pale) 0%, #FFF5F6 100%);
    border:1px solid var(--red-bdr);
    border-radius:8px; padding:8px 14px; margin-bottom:10px;
}
.role-head-dot { width:7px; height:7px; border-radius:50%; background:var(--red); flex-shrink:0; }
.role-head-name { font-size:12px; font-weight:800; color:var(--red); letter-spacing:0.01em; }
.role-head-count { font-size:10px; color:var(--red-d); opacity:.7; margin-left:auto; font-weight:600; }

/* ── TABLE ── */
.tbl {
    width:100%;
    border-collapse:collapse;
    font-size:11px;
    table-layout:fixed;
}
.tbl thead tr { background:#F8FAFC; }
.tbl th {
    padding:8px 12px; text-align:left;
    font-size:9px; font-weight:800; letter-spacing:0.09em; text-transform:uppercase;
    color:var(--ink-4); border-bottom:2px solid var(--line);
}
.tbl td {
    padding:9px 12px;
    border-bottom:1px solid var(--line-2);
    color:var(--ink-2);
    vertical-align:middle;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
}
.tbl tbody tr:last-child td { border-bottom:none; }
.tbl tbody tr:hover td { background:#FAFBFC; }
.tbl tbody tr:nth-child(even) td { background:#FAFBFF; }
.tbl tbody tr:nth-child(even):hover td { background:#F3F4F6; }

.pill { display:inline-block; padding:3px 10px; border-radius:99px; font-size:10px; font-weight:700; }
.p-g { background:#DCFCE7; color:#15803D; }
.p-a { background:#FEF3C7; color:#92400E; }
.p-r { background:#FEE2E2; color:#B91C1C; }
.behalf { font-size:9px; background:#FEF3C7; color:#92400E; padding:2px 7px; border-radius:99px; margin-left:5px; font-weight:700; }
.num-g { color:var(--green); font-weight:800; }
.num-r { color:var(--red); font-weight:800; }
.num-s { color:var(--ink-4); font-weight:600; }
.auditee-name { font-weight:700; color:var(--ink); }

/* ── FOOTER ── */
.footer {
    margin-top:32px; padding-top:16px;
    border-top:1px solid var(--line);
    display:flex; justify-content:space-between; align-items:center; gap:20px;
}
.footer-l { font-size:9.5px; color:var(--ink-4); line-height:1.8; }
.footer-l strong { color:var(--ink-3); }
.footer-r { font-size:9px; color:#D1D5DB; text-align:right; line-height:1.8; flex-shrink:0; }
.footer-stamp {
    display:inline-block; border:1.5px solid var(--line);
    border-radius:6px; padding:4px 10px;
    font-size:9px; font-weight:700; color:var(--ink-4); letter-spacing:0.04em;
    margin-top:4px;
}

/* ── PRINT ── */
@media print {
    body { background:#fff; }
    .topbar { display:none !important; }
    .shell { max-width:100%; margin:0; }
    .hero { border-radius:0; }
    .card { border-radius:0; box-shadow:none; }
    .role-block { page-break-inside:avoid; }
    .triple { page-break-inside:avoid; }
    @page { size:A4 portrait; margin:10mm 12mm; }
}
</style>
</head>
<body>

<div class="shell">

{{-- HERO --}}
<div class="hero" id="page-content">
    <div class="hero-noise"></div>
    <div class="hero-circle-1"></div>
    <div class="hero-circle-2"></div>
    <div class="hero-inner">
        <div>
            <div class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Laporan Resmi · Genba Check
            </div>
            <div class="hero-title">Hasil Evaluasi<br>Pengetahuan Dealer</div>
            <div class="hero-sub">{{ $dealer->name }}</div>
        </div>
        <div class="hero-right">
            <img src="{{ asset('assets/logo.png') }}"
                 alt="Honda" class="hero-logo"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <div class="hero-logo-text">MSK</div>
            <div class="hero-meta">
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Kode Dealer: <strong>{{ $dealer->code ?? '-' }}</strong>
            </div>
        </div>
    </div>
    <div class="hero-stripe"></div>
</div>

{{-- CARD --}}
<div class="card">

    {{-- STAT ROW --}}
    <div class="stat-row">
        <div class="stat-box">
            <div class="stat-lbl">Nama Dealer</div>
            <div class="stat-val" style="font-size:13px;line-height:1.3;">{{ $dealer->name }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-lbl">Kode Dealer</div>
            <div class="stat-val">{{ $dealer->code ?? '-' }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-lbl">Jumlah Sesi</div>
            <div class="stat-val">{{ $sessions->count() }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-lbl">Rata-rata Score</div>
            <div class="stat-val red">{{ number_format($avgScore, 1) }}%</div>
        </div>
    </div>

    {{-- TRIPLE --}}
    <div class="triple">
        <div class="triple-card g">
            <div class="triple-corner"></div>
            <div class="triple-num">{{ $totalPaham }}</div>
            <div class="triple-lbl">Paham</div>
        </div>
        <div class="triple-card r">
            <div class="triple-corner"></div>
            <div class="triple-num">{{ $totalTidakPaham }}</div>
            <div class="triple-lbl">Tidak Paham</div>
        </div>
        <div class="triple-card s">
            <div class="triple-corner"></div>
            <div class="triple-num">{{ $totalTidakDipakai }}</div>
            <div class="triple-lbl">Tidak Dipakai</div>
        </div>
    </div>

    {{-- BAR CHART --}}
    <div class="bars">
        <div class="sec">
            <div class="sec-bar"></div>
            <div class="sec-title">Skor per Bagian / Role</div>
            <div class="sec-count">{{ $roleStats->count() }} role</div>
        </div>
        @foreach($roleStats as $stat)
        @php
            $sc    = $stat['avgScore'];
            $color = $sc >= 80 ? '#059669' : ($sc >= 60 ? '#D97706' : '#C8102E');
            $w     = min(100, max(2, $sc));
        @endphp
        <div class="bar-row">
            <div class="bar-name" title="{{ $stat['roleName'] }}">{{ $stat['roleName'] }}</div>
            <div class="bar-track">
                <div class="bar-fill" style="width:{{ $w }}%;background:{{ $color }};"></div>
            </div>
            <div class="bar-pct" style="color:{{ $color }};">{{ $sc }}%</div>
        </div>
        @endforeach
    </div>

    <hr class="div">

    {{-- DETAIL TABLE --}}
    <div class="sec">
        <div class="sec-bar"></div>
        <div class="sec-title">Detail Hasil per Role</div>
        <div class="sec-count">{{ $sessions->count() }} sesi total</div>
    </div>

    @foreach($roleStats as $stat)
    @php $namaRole = (string)($stat['roleName'] ?? '-'); @endphp
    <div class="role-block">
        <div class="role-head">
            <div class="role-head-dot"></div>
            <div class="role-head-name">{{ $namaRole }}</div>
            <div class="role-head-count">{{ $stat['roleSessions']->count() }} sesi</div>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:22%;">Auditee</th>
                    <th style="width:22%;">Atas Nama</th>
                    <th style="width:14%; text-align:center;">Paham</th>
                    <th style="width:14%; text-align:center;">Tdk Paham</th>
                    <th style="width:14%; text-align:center;">Tdk Dipakai</th>
                    <th style="width:14%; text-align:center;">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stat['roleSessions'] as $session)
                @php
                    $p   = $session->answers->where('indicator','1')->count();
                    $tp  = $session->answers->where('indicator','2')->count();
                    $td  = $session->answers->where('indicator','3')->count();
                    $sc  = $session->score;
                    $pc  = $sc >= 80 ? 'p-g' : ($sc >= 60 ? 'p-a' : 'p-r');
                @endphp
                <tr>
                    <td class="auditee-name">{{ $session->auditee_name ?? '-' }}</td>
                    <td>
                        {{ optional($session->user)->name ?? '-' }}
                        @if($session->is_behalf)<span class="behalf">Dibantu</span>@endif
                    </td>
                    <td style="text-align:center;" class="num-g">{{ $p }}</td>
                    <td style="text-align:center;" class="num-r">{{ $tp }}</td>
                    <td style="text-align:center;" class="num-s">{{ $td }}</td>
                    <td style="text-align:center;"><span class="pill {{ $pc }}">{{ $sc }}%</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>{{-- end card --}}
</div>{{-- end shell --}}

<script>
</script>
</body>
</html>