<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genba MSK - Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/bgputih.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/bgputih.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/bgputih.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --red-primary: #C8102E;
            --red-dark: #9B0B22;
            --red-deeper: #7A0919;
            --sidebar-bg: #0F0F0F;
            --sidebar-surface: #1A1A1A;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-hover: rgba(200,16,46,0.15);
            --sidebar-active: #C8102E;
            --text-muted: #6B7280;
        }

        /* Sidebar */
        #sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
        }

        #sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 200px;
            background: radial-gradient(ellipse at top left, rgba(200,16,46,0.18) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .sidebar-logo {
            position: relative;
            z-index: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #9CA3AF;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: #F3F4F6;
        }

        .nav-item:hover .nav-icon {
            color: #C8102E;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(200,16,46,0.25), rgba(200,16,46,0.10));
            color: #FFFFFF;
            border: 1px solid rgba(200,16,46,0.3);
        }

        .nav-item.active .nav-icon {
            color: #F87171;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: #C8102E;
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #4B5563;
            padding: 16px 12px 6px;
        }

        /* Honda badge */
        .honda-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #C8102E;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* Header */
        #main-header {
            background: white;
            border-bottom: 1px solid #F3F4F6;
        }

        /* Hamburger button */
        .toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            color: #374151;
            transition: all 0.2s;
            cursor: pointer;
        }
        .toggle-btn:hover {
            background: #FEF2F2;
            border-color: #FECACA;
            color: #C8102E;
        }

            @keyframes spin {
             to { transform: rotate(360deg); }
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); box-shadow: 0 4px 20px rgba(200,16,46,0.3); }
                50% { transform: scale(1.05); box-shadow: 0 8px 30px rgba(200,16,46,0.5); }
            }

            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        /* User button */
        .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px 6px 6px;
            border-radius: 100px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            cursor: pointer;
            transition: all 0.2s;
        }
        .user-pill:hover {
            background: #FEF2F2;
            border-color: #FECACA;
        }
        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C8102E, #7A0919);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
        }

        /* Alert */
        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-left: 4px solid #22C55E;
            color: #15803D;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .alert-error {
            background: #FFF5F5;
            border: 1px solid #FED7D7;
            border-left: 4px solid #C8102E;
            color: #9B2335;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Scrollbar sidebar */
        #sidebar nav::-webkit-scrollbar { width: 4px; }
        #sidebar nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* Main content scroll */
        #main-scroll::-webkit-scrollbar { width: 6px; }
        #main-scroll::-webkit-scrollbar-track { background: #F9FAFB; }
        #main-scroll::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 4px; }

        /* Slide animation for mobile sidebar */
        @media (max-width: 767px) {
            #sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        }

        /* Dropdown */
        #dropdownUser {
            border: 1px solid #F3F4F6;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    <!-- OVERLAY MOBILE -->
    <div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden" onclick="toggleSidebar()"></div>


    {{-- Loading Screen --}}
<div id="pageLoader"
     style="position:fixed;inset:0;background:#0F0F0F;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity 0.4s ease;">

    {{-- Logo --}}
    <div style="margin-bottom:32px;text-align:center;">
        <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;animation:pulse 1.5s ease-in-out infinite;">
            <img src="{{ asset('assets/bgputih.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <p style="color:white;font-weight:700;font-size:1rem;">Genba MSK</p>
        <p style="color:#4B5563;font-size:0.72rem;margin-top:2px;font-family:'DM Mono',monospace;">Auditor Panel</p>
    </div>

    {{-- Spinner --}}
    <div style="position:relative;width:48px;height:48px;">
        <div style="position:absolute;inset:0;border:3px solid rgba(200,16,46,0.15);border-radius:50%;"></div>
        <div style="position:absolute;inset:0;border:3px solid transparent;border-top-color:#C8102E;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
    </div>

    <p style="color:#4B5563;font-size:0.72rem;margin-top:16px;letter-spacing:0.05em;">Memuat halaman...</p>
</div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed md:static z-50 md:z-auto w-64 flex flex-col
               transform -translate-x-full md:translate-x-0
               transition-all duration-300 overflow-hidden h-full">

        <!-- Logo -->
        <div class="sidebar-logo px-5 py-5 flex items-center gap-3" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                <img src="{{ asset('assets/bgputih.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
            </div>
            <div class="menu-text">
                <p class="text-white font-bold text-sm leading-tight">Digital Network</p>
                <p style="color:#6B7280;font-size:0.7rem;font-family:'DM Mono',monospace;">Main Dealer Panel</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 nav-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <div class="section-label menu-text">Master Data</div>

            <a href="{{ route('admin.dealers.index') }}"
               class="nav-item {{ request()->routeIs('admin.dealers.*') ? 'active' : '' }}">
                <i class="bi bi-shop nav-icon"></i>
                <span class="menu-text">Dealer</span>
            </a>

            <a href="{{ route('admin.roles.index') }}"
               class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock nav-icon"></i>
                <span class="menu-text">Role</span>
            </a>

            <a href="{{ route('admin.questions.index') }}"
               class="nav-item {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                <i class="bi bi-patch-question nav-icon"></i>
                <span class="menu-text">Pertanyaan</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people nav-icon"></i>
                <span class="menu-text">User MD</span>
            </a>
            
            <a href="{{ route('admin.schedules.index') }}"
               class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-week nav-icon"></i>
                <span class="menu-text">Jadwal Genba</span>
            </a>

            <div class="section-label menu-text">Monitoring</div>
            
            <a href="{{ route('admin.evidence.index') }}"
               class="nav-item {{ request()->routeIs('admin.evidence.*') ? 'active' : '' }}">
                <i class="bi bi-camera-fill nav-icon"></i>
                <span class="menu-text">Evidence Foto</span>
            </a>

            <a href="{{ route('admin.summary') }}"
               class="nav-item {{ request()->routeIs('admin.summary') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line nav-icon"></i>
                <span class="menu-text">Summary</span>
            </a>

            <a href="{{ route('admin.rekap.index') }}"
               class="nav-item {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text nav-icon"></i>
                <span class="menu-text">Rekap Genba</span>
            </a>

            <a href="{{ route('admin.pica.index') }}"
               class="nav-item {{ request()->routeIs('admin.pica.*') ? 'active' : '' }}">
                <i class="bi bi-tools nav-icon"></i>
                <span class="menu-text">PICA</span>
            </a>

        </nav>

        <!-- Footer sidebar -->
        <div class="px-4 py-4 menu-text" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-2">
                <span class="honda-badge">
                    <i class="bi bi-patch-check-fill"></i>
                    V.2.0
                </span>
                <span style="color:#4B5563;font-size:0.68rem;">Genba System</span>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- HEADER -->
        <header id="main-header" class="flex items-center justify-between px-4 md:px-6 py-3 z-30 sticky top-0">

            <!-- LEFT -->
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="toggle-btn">
                    <i class="bi bi-list" style="font-size:1.1rem;"></i>
                </button>

                <!-- Breadcrumb hint (optional visual) -->
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-400">
                    <i class="bi bi-house-door" style="font-size:0.8rem;"></i>
                    <span style="font-size:0.75rem;">Admin</span>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-3">

              <!-- Notification bell -->
@php
    $draftCount = \App\Models\GenbaSession::where('status', 'draft')
        ->whereDate('created_at', '<', today())
        ->count();
    $picaCount = \App\Models\Pica::where('status', 'open')->count();
    $totalNotif = $draftCount + $picaCount;
@endphp

<div class="relative" id="adminNotifWrapper">
    <button onclick="toggleAdminNotif()" class="toggle-btn relative" title="Notifikasi">
        <i class="bi bi-bell" style="font-size:0.95rem;"></i>
        @if($totalNotif > 0)
        <span style="position:absolute;top:6px;right:6px;width:7px;height:7px;background:#C8102E;border-radius:50%;border:1.5px solid white;"></span>
        @endif
    </button>

    {{-- Dropdown Notifikasi --}}
    <div id="adminNotifDropdown"
         class="hidden absolute right-0 mt-2 w-80 bg-white z-50"
         style="border:1px solid #F3F4F6;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.12);overflow:hidden;">

        <div class="px-4 py-3" style="border-bottom:1px solid #F3F4F6;background:#F9FAFB;">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-800 text-sm">Notifikasi</p>
                @if($totalNotif > 0)
                <span style="background:#FEE2E2;color:#C8102E;font-size:0.7rem;font-weight:700;padding:2px 8px;border-radius:100px;">
                    {{ $totalNotif }} aktif
                </span>
                @endif
            </div>
        </div>

        <div style="max-height:300px;overflow-y:auto;">
            @if($draftCount > 0)
            <a href="{{ route('admin.rekap.index') }}?status=draft"
               style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
               onmouseover="this.style.background='#FFFBEB'" onmouseout="this.style.background='white'">
                <div style="width:36px;height:36px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-clock-history" style="color:#D97706;font-size:0.95rem;"></i>
                </div>
                <div>
                    <p style="font-size:0.8rem;font-weight:600;color:#1F2937;line-height:1.3;">Draft Tertunda</p>
                    <p style="font-size:0.72rem;color:#6B7280;margin-top:2px;line-height:1.4;">
                        <span style="font-weight:600;color:#D97706;">{{ $draftCount }} checklist</span>
                        belum diselesaikan auditor sejak kemarin
                    </p>
                    <p style="font-size:0.68rem;color:#D97706;margin-top:4px;">Lihat rekap genba →</p>
                </div>
            </a>
            @endif

            @if($picaCount > 0)
            <a href="{{ route('admin.pica.index') }}"
               style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid #F9FAFB;transition:background 0.15s;"
               onmouseover="this.style.background='#FFF5F5'" onmouseout="this.style.background='white'">
                <div style="width:36px;height:36px;border-radius:10px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-tools" style="color:#C8102E;font-size:0.95rem;"></i>
                </div>
                <div>
                    <p style="font-size:0.8rem;font-weight:600;color:#1F2937;line-height:1.3;">PICA Belum Selesai</p>
                    <p style="font-size:0.72rem;color:#6B7280;margin-top:2px;line-height:1.4;">
                        <span style="font-weight:600;color:#C8102E;">{{ $picaCount }} temuan</span>
                        masih berstatus Open
                    </p>
                    <p style="font-size:0.68rem;color:#C8102E;margin-top:4px;">Lihat PICA →</p>
                </div>
            </a>
            @endif

            @if($totalNotif === 0)
            <div style="padding:32px 16px;text-align:center;color:#9CA3AF;">
                <i class="bi bi-check-circle" style="font-size:2rem;color:#22C55E;display:block;margin-bottom:8px;"></i>
                <p style="font-size:0.8rem;font-weight:600;color:#374151;">Semua beres!</p>
                <p style="font-size:0.72rem;margin-top:4px;">Tidak ada notifikasi aktif</p>
            </div>
            @endif
        </div>

        <div style="padding:10px 16px;background:#F9FAFB;border-top:1px solid #F3F4F6;text-align:center;">
            <p style="font-size:0.68rem;color:#9CA3AF;">Diperbarui setiap refresh halaman</p>
        </div>
    </div>
</div>

                <!-- User Dropdown -->
                <div class="relative">
                    <button id="dropdownUserButton" data-dropdown-toggle="dropdownUser" class="user-pill">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-600 text-gray-800 leading-tight" style="font-weight:600;">{{ auth()->user()->name }}</p>
                            <p class="text-gray-400" style="font-size:0.65rem;">Administrator</p>
                        </div>
                        <i class="bi bi-chevron-down text-gray-400" style="font-size:0.6rem;"></i>
                    </button>

                    <div id="dropdownUser" class="hidden absolute right-0 mt-2 w-52 bg-white">
                        <div class="px-4 py-3" style="border-bottom:1px solid #F3F4F6;">
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width:34px;height:34px;font-size:0.8rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">Admin</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
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
            <div class="p-4 md:p-6 max-w-screen-xl mx-auto">

                @if(session('success'))
                    <div class="alert-success mb-5">
                        <i class="bi bi-check-circle-fill text-green-500" style="font-size:1.1rem;flex-shrink:0;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error mb-5">
                        <i class="bi bi-exclamation-circle-fill" style="font-size:1.1rem;flex-shrink:0;color:#C8102E;"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </main>

    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (window.innerWidth < 768) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    } else {
        const isCollapsed = sidebar.classList.contains('w-20');

        if (isCollapsed) {
            sidebar.style.width = '256px';
        } else {
            sidebar.style.width = '80px';
        }

        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        document.querySelectorAll('.menu-text').forEach(el => {
            el.classList.toggle('hidden');
        });
    }
}


function toggleAdminNotif() {
    const dropdown = document.getElementById('adminNotifDropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('adminNotifWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('adminNotifDropdown').classList.add('hidden');
    }
});

  // Sembunyikan loader saat halaman sudah siap
    window.addEventListener('load', function() {
        const loader = document.getElementById('pageLoader');
        loader.style.animation = 'fadeOut 0.4s ease forwards';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 400);
    });

    // Tampilkan loader saat pindah halaman
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link &&
            link.href &&
            !link.href.startsWith('#') &&
            !link.target &&
            link.hostname === window.location.hostname &&
            !link.hasAttribute('data-dropdown-toggle')) {

            const loader = document.getElementById('pageLoader');
            loader.style.display = 'flex';
            loader.style.opacity = '1';
            loader.style.animation = 'none';
        }
    });

    // Tampilkan loader saat submit form
    document.addEventListener('submit', function() {
        const loader = document.getElementById('pageLoader');
        loader.style.display = 'flex';
        loader.style.opacity = '1';
        loader.style.animation = 'none';
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>