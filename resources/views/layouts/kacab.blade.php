<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genba MSK - Kepala Cabang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/bgputih.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/bgputih.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/bgputih.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root {
            --red-primary: #C8102E;
            --sidebar-bg: #0F0F0F;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-hover: rgba(200,16,46,0.15);
        }
        #sidebar { background: var(--sidebar-bg); border-right: 1px solid var(--sidebar-border); }
        #sidebar::before { content:'';position:absolute;top:0;left:0;right:0;height:200px;background:radial-gradient(ellipse at top left,rgba(200,16,46,0.18) 0%,transparent 70%);pointer-events:none;z-index:0; }
        .sidebar-inner { position:relative;z-index:1; }
        .nav-item { display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:0.875rem;font-weight:500;color:#9CA3AF;transition:all 0.2s ease;position:relative;overflow:hidden;text-decoration:none; }
        .nav-item:hover { background:var(--sidebar-hover);color:#F3F4F6; }
        .nav-item:hover .nav-icon { color:#C8102E; }
        .nav-item.active { background:linear-gradient(135deg,rgba(200,16,46,0.25),rgba(200,16,46,0.10));color:#FFFFFF;border:1px solid rgba(200,16,46,0.3); }
        .nav-item.active .nav-icon { color:#F87171; }
        .nav-item.active::before { content:'';position:absolute;left:0;top:20%;bottom:20%;width:3px;background:#C8102E;border-radius:0 4px 4px 0; }
        .nav-icon { font-size:1rem;width:20px;text-align:center;flex-shrink:0;transition:color 0.2s; }
        .sidebar-avatar { width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#C8102E,#7A0919);display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:700;flex-shrink:0; }
        .sidebar-user-chip { display:flex;align-items:center;gap:10px;padding:10px 12px;margin:0 4px;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07); }
        .honda-badge { display:inline-flex;align-items:center;gap:6px;background:#C8102E;color:white;font-size:0.65rem;font-weight:700;letter-spacing:0.1em;padding:3px 8px;border-radius:4px;text-transform:uppercase; }
        #main-header { background:white;border-bottom:1px solid #F3F4F6; }
        .toggle-btn { display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#F9FAFB;border:1px solid #E5E7EB;color:#374151;transition:all 0.2s;cursor:pointer; }
        .toggle-btn:hover { background:#FEF2F2;border-color:#FECACA;color:#C8102E; }
        .user-pill { display:flex;align-items:center;gap:8px;padding:6px 12px 6px 6px;border-radius:100px;background:#F9FAFB;border:1px solid #E5E7EB;cursor:pointer;transition:all 0.2s; }
        .user-avatar { width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#C8102E,#7A0919);display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:700; }
        .alert-success { background:#F0FDF4;border:1px solid #BBF7D0;border-left:4px solid #22C55E;color:#15803D;border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:0.875rem;font-weight:500; }
        #dropdownUser { border:1px solid #F3F4F6;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.12);overflow:hidden; }
        #sidebar nav::-webkit-scrollbar { width:4px; }
        #sidebar nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.1);border-radius:4px; }
        #main-scroll::-webkit-scrollbar { width:6px; }
        #main-scroll::-webkit-scrollbar-thumb { background:#D1D5DB;border-radius:4px; }

        @media (max-width: 767px) {
            #sidebar { position:fixed;top:0;left:0;bottom:0;z-index:50;width:280px!important;transform:translateX(-100%); }
            #sidebar.open { transform:translateX(0); }
            #sidebar-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(2px);z-index:40; }
            #sidebar-overlay.open { display:block; }
            #bottom-nav { display:flex;position:fixed;bottom:0;left:0;right:0;background:#0F0F0F;border-top:1px solid rgba(255,255,255,0.08);z-index:30;padding:8px 0; }
            .bottom-nav-item { flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px 4px;color:#6B7280;text-decoration:none;transition:all 0.2s;border-radius:8px;margin:0 4px; }
            .bottom-nav-item.active { color:#C8102E; }
            .bottom-nav-item .bnav-icon { font-size:1.2rem; }
            .bottom-nav-item .bnav-label { font-size:0.6rem;font-weight:600; }
            #main-scroll > div { padding-bottom:80px!important; }
        }
        @media (min-width: 768px) {
            #bottom-nav { display:none; }
            #sidebar-overlay { display:none!important; }
        }
    </style>
</head>
<body class="bg-gray-50">

{{-- Loading Screen --}}
<div id="pageLoader" style="position:fixed;inset:0;background:#0F0F0F;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;">
    <div style="margin-bottom:32px;text-align:center;">
        <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;animation:pulse 1.5s ease-in-out infinite;">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <p style="color:white;font-weight:700;font-size:1rem;">Genba MSK</p>
        <p style="color:#4B5563;font-size:0.72rem;margin-top:2px;font-family:'DM Mono',monospace;">Kepala Cabang Panel</p>
    </div>
    <div style="position:relative;width:48px;height:48px;">
        <div style="position:absolute;inset:0;border:3px solid rgba(200,16,46,0.15);border-radius:50%;"></div>
        <div style="position:absolute;inset:0;border:3px solid transparent;border-top-color:#C8102E;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
    </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }
@keyframes pulse { 0%,100%{transform:scale(1);box-shadow:0 4px 20px rgba(200,16,46,0.3);}50%{transform:scale(1.05);box-shadow:0 8px 30px rgba(200,16,46,0.5);} }
@keyframes fadeOut { from{opacity:1;}to{opacity:0;} }
</style>

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 flex flex-col overflow-hidden h-full flex-shrink-0">
        <div class="sidebar-inner flex flex-col h-full">

            <!-- Logo -->
            <div class="px-4 py-5 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                        <img src="{{ asset('assets/bgputih.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-tight">Genba MSK</p>
                        <p style="color:#6B7280;font-size:0.7rem;font-family:'DM Mono',monospace;">Kepala Cabang</p>
                    </div>
                </div>
                <button onclick="closeSidebar()" class="md:hidden text-gray-500 hover:text-white p-1">
                    <i class="bi bi-x-lg" style="font-size:1rem;"></i>
                </button>
            </div>

            <!-- User chip -->
            <div class="px-3 pt-4 pb-2">
                <div class="sidebar-user-chip">
                    <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="overflow-hidden">
                        <p class="text-white text-xs font-semibold leading-tight truncate">{{ auth()->user()->name }}</p>
                        <p style="color:#6B7280;font-size:0.65rem;">{{ auth()->user()->dealer->name ?? 'Kepala Cabang' }}</p>
                    </div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto">
                <a href="{{ route('kacab.dashboard') }}" class="nav-item {{ request()->routeIs('kacab.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 nav-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kacab.rekap.index') }}" class="nav-item {{ request()->routeIs('kacab.rekap.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text nav-icon"></i>
                    <span>Rekap Genba</span>
                </a>
                <a href="{{ route('kacab.pica.index') }}" class="nav-item {{ request()->routeIs('kacab.pica.*') ? 'active' : '' }}">
                    <i class="bi bi-tools nav-icon"></i>
                    <span>PICA</span>
                </a>
            </nav>

            <!-- Footer -->
            <div class="px-4 py-4" style="border-top:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-2">
                    <span class="honda-badge"><i class="bi bi-patch-check-fill"></i>V.2.0</span>
                    <span style="color:#4B5563;font-size:0.68rem;">Genba System</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- HEADER -->
        <header id="main-header" class="flex items-center justify-between px-4 md:px-6 py-3 z-30 sticky top-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="toggle-btn hidden md:flex">
                    <i class="bi bi-list" style="font-size:1.1rem;"></i>
                </button>
                <button onclick="openSidebar()" class="toggle-btn md:hidden">
                    <i class="bi bi-list" style="font-size:1.1rem;"></i>
                </button>
                <div class="md:hidden">
                    <p class="font-bold text-gray-800 text-sm">Genba MSK</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" class="user-pill">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs text-gray-800 leading-tight" style="font-weight:600;">{{ auth()->user()->name }}</p>
                            <p class="text-gray-400" style="font-size:0.65rem;">Kepala Cabang</p>
                        </div>
                        <i class="bi bi-chevron-down text-gray-400 hidden sm:block" style="font-size:0.6rem;"></i>
                    </button>
                    <div id="dropdownUser" class="hidden absolute right-0 mt-2 w-52 bg-white">
                        <div class="px-4 py-3" style="border-bottom:1px solid #F3F4F6;">
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width:34px;height:34px;font-size:0.8rem;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->dealer->name ?? 'Kepala Cabang' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-1" style="border-top:1px solid #F3F4F6;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main id="main-scroll" class="flex-1 overflow-y-auto bg-gray-50">
            <div class="max-w-5xl mx-auto p-4 md:p-6">
                @if(session('success'))
                <div class="alert-success mb-5">
                    <i class="bi bi-check-circle-fill text-green-500" style="font-size:1.1rem;flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</div>

{{-- Bottom Nav Mobile --}}
<nav id="bottom-nav">
    <a href="{{ route('kacab.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('kacab.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 bnav-icon"></i>
        <span class="bnav-label">Dashboard</span>
    </a>
    <a href="{{ route('kacab.rekap.index') }}" class="bottom-nav-item {{ request()->routeIs('kacab.rekap.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text bnav-icon"></i>
        <span class="bnav-label">Rekap</span>
    </a>
    <a href="{{ route('kacab.pica.index') }}" class="bottom-nav-item {{ request()->routeIs('kacab.pica.*') ? 'active' : '' }}">
        <i class="bi bi-tools bnav-icon"></i>
        <span class="bnav-label">PICA</span>
    </a>
</nav>

<script>
window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    loader.style.animation = 'fadeOut 0.4s ease forwards';
    setTimeout(() => loader.style.display = 'none', 400);
});

window.addEventListener('pageshow', function() {
    const loader = document.getElementById('pageLoader');
    loader.style.display = 'none';
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.href && !link.href.startsWith('#') && !link.target && link.hostname === window.location.hostname && !link.hasAttribute('data-dropdown-toggle')) {
        const loader = document.getElementById('pageLoader');
        loader.style.display = 'flex';
        loader.style.opacity = '1';
        loader.style.animation = 'none';
    }
});

document.addEventListener('submit', function(e) {
    const action = e.target.action || '';
    if (action.includes('logout')) return;
    const loader = document.getElementById('pageLoader');
    loader.style.display = 'flex';
    loader.style.opacity = '1';
    loader.style.animation = 'none';
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('w-20');
}

function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
@stack('scripts')
</body>
</html>