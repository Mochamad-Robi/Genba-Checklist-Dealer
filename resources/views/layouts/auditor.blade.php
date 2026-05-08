<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genba MSK</title>

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
            --sidebar-bg: #0F0F0F;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-hover: rgba(200,16,46,0.15);
        }

        /* Sidebar desktop */
        #sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s ease;
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

        .sidebar-inner { position: relative; z-index: 1; }

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

        .nav-item:hover { background: var(--sidebar-hover); color: #F3F4F6; }
        .nav-item:hover .nav-icon { color: #C8102E; }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(200,16,46,0.25), rgba(200,16,46,0.10));
            color: #FFFFFF;
            border: 1px solid rgba(200,16,46,0.3);
        }

        .nav-item.active .nav-icon { color: #F87171; }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: #C8102E;
            border-radius: 0 4px 4px 0;
        }

        .nav-item-cta {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 700;
            color: #FFFFFF;
            background: linear-gradient(135deg, #C8102E, #9B0B22);
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(200,16,46,0.35);
        }

        .nav-item-cta:hover {
            box-shadow: 0 6px 20px rgba(200,16,46,0.5);
            transform: translateY(-1px);
        }

        .nav-icon {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .sidebar-user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin: 0 4px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #C8102E, #7A0919);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            flex-shrink: 0;
        }

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

        #main-header {
            background: white;
            border-bottom: 1px solid #F3F4F6;
        }

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

        .user-pill:hover { background: #FEF2F2; border-color: #FECACA; }

        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C8102E, #7A0919);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
        }

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

        #dropdownUser {
            border: 1px solid #F3F4F6;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        /* Scrollbar */
        #sidebar nav::-webkit-scrollbar { width: 4px; }
        #sidebar nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        #main-scroll::-webkit-scrollbar { width: 6px; }
        #main-scroll::-webkit-scrollbar-track { background: #F9FAFB; }
        #main-scroll::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 4px; }

        /* ===================== MOBILE ===================== */
        @media (max-width: 767px) {
            /* Sidebar jadi drawer dari kiri */
            #sidebar {
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 50;
                width: 280px !important;
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            /* Overlay */
            #sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                backdrop-filter: blur(2px);
                z-index: 40;
            }

            #sidebar-overlay.open { display: block; }

            /* Sembunyikan toggle desktop di mobile */
            .desktop-toggle { display: none; }

            /* Bottom nav bar */
            #bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0; left: 0; right: 0;
                background: #0F0F0F;
                border-top: 1px solid rgba(255,255,255,0.08);
                z-index: 30;
                padding: 8px 0;
                padding-bottom: calc(8px + env(safe-area-inset-bottom));
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

            .bottom-nav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3px;
                padding: 6px 4px;
                color: #6B7280;
                text-decoration: none;
                transition: all 0.2s;
                border-radius: 8px;
                margin: 0 4px;
            }

            .bottom-nav-item.active {
                color: #C8102E;
            }

            .bottom-nav-item .bnav-icon {
                font-size: 1.2rem;
            }

            .bottom-nav-item .bnav-label {
                font-size: 0.6rem;
                font-weight: 600;
            }

            .bottom-nav-cta {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3px;
                padding: 6px 4px;
                text-decoration: none;
                border-radius: 8px;
                margin: 0 4px;
            }

            .bottom-nav-cta .bnav-icon-wrap {
                width: 36px; height: 36px;
                background: linear-gradient(135deg, #C8102E, #9B0B22);
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 4px 12px rgba(200,16,46,0.4);
            }

            .bottom-nav-cta .bnav-icon {
                font-size: 1.1rem;
                color: white;
            }

            .bottom-nav-cta .bnav-label {
                font-size: 0.6rem;
                font-weight: 700;
                color: #C8102E;
            }

            /* Tambah padding bawah konten agar tidak ketutup bottom nav */
            #main-scroll > div {
                padding-bottom: 80px !important;
            }

            /* Notif dropdown mobile - full width */
            #auditorNotifDropdown {
                width: calc(100vw - 32px) !important;
                right: -60px !important;
            }
        }

        @media (min-width: 768px) {
            #bottom-nav { display: none; }
            #sidebar-overlay { display: none !important; }
        }
    </style>
</head>

<body class="bg-gray-50">

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

{{-- Mobile Overlay --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 flex flex-col overflow-hidden h-full flex-shrink-0">
        <div class="sidebar-inner flex flex-col h-full">

            <!-- Logo -->
            <div class="px-4 py-5 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                    <img src="{{ asset('assets/bgputih.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
                </div>
                    <div class="menu-text">
                        <p class="text-white font-bold text-sm leading-tight">Genba MSK</p>
                        <p style="color:#6B7280;font-size:0.7rem;font-family:'DM Mono',monospace;">Auditor Panel</p>
                    </div>
                </div>
                {{-- Close button mobile --}}
                <button onclick="closeSidebar()" class="md:hidden text-gray-500 hover:text-white transition-colors p-1">
                    <i class="bi bi-x-lg" style="font-size:1rem;"></i>
                </button>
            </div>

            <!-- User chip -->
            <div class="px-3 pt-4 pb-2 menu-text-block">
                <div class="sidebar-user-chip">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="menu-text overflow-hidden">
                        <p class="text-white text-xs font-semibold leading-tight truncate">{{ auth()->user()->name }}</p>
                        <p style="color:#6B7280;font-size:0.65rem;">Auditor</p>
                    </div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto">
                <a href="{{ route('auditor.dashboard') }}"
                   class="nav-item {{ request()->routeIs('auditor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 nav-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="{{ route('auditor.genba.index') }}"
                   class="nav-item {{ request()->routeIs('auditor.genba.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history nav-icon"></i>
                    <span class="menu-text">Riwayat</span>
                </a>
                <a href="{{ route('auditor.pica.index') }}"
                   class="nav-item {{ request()->routeIs('auditor.pica.*') ? 'active' : '' }}">
                    <i class="bi bi-tools nav-icon"></i>
                    <span class="menu-text">PICA</span>
                </a>
                <div style="height:1px;background:rgba(255,255,255,0.06);margin:10px 4px;"></div>
                <a href="{{ route('auditor.genba.create') }}" class="nav-item-cta">
                    <i class="bi bi-plus-circle-fill nav-icon"></i>
                    <span class="menu-text">Mulai Genba</span>
                </a>
            </nav>

            <!-- Footer sidebar -->
            <div class="px-4 py-4 menu-text" style="border-top: 1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center gap-2">
                    <span class="honda-badge">
                        <i class="bi bi-patch-check-fill"></i> V.2.0
                    </span>
                    <span style="color:#4B5563;font-size:0.68rem;">Genba System</span>
                </div>
            </div>

        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- HEADER -->
        <header id="main-header" class="flex items-center justify-between px-4 md:px-6 py-3 z-30 sticky top-0">

            <!-- LEFT -->
            <div class="flex items-center gap-3">
                {{-- Desktop toggle --}}
                <button onclick="toggleSidebar()" class="toggle-btn desktop-toggle hidden md:flex">
                    <i class="bi bi-list" style="font-size:1.1rem;"></i>
                </button>
                {{-- Mobile hamburger --}}
                <button onclick="openSidebar()" class="toggle-btn md:hidden">
                    <i class="bi bi-list" style="font-size:1.1rem;"></i>
                </button>

                {{-- Page title mobile --}}
                <div class="md:hidden">
                    <p class="font-bold text-gray-800 text-sm">Genba MSK</p>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-2">

                {{-- Bell Notification --}}
                @php
                    $draftCount = \App\Models\GenbaSession::where('user_id', auth()->id())
                        ->where('status', 'draft')
                        ->whereDate('created_at', '<', today())
                        ->count();
                    $picaCount = \App\Models\Pica::where('user_id', auth()->id())
                        ->where('status', 'open')
                        ->count();
                    $totalNotif = $draftCount + $picaCount;
                @endphp

                <div class="relative" id="auditorNotifWrapper">
                    <button onclick="toggleAuditorNotif()" class="toggle-btn relative" title="Notifikasi">
                        <i class="bi bi-bell" style="font-size:0.95rem;"></i>
                        @if($totalNotif > 0)
                        <span style="position:absolute;top:6px;right:6px;width:7px;height:7px;background:#C8102E;border-radius:50%;border:1.5px solid white;"></span>
                        @endif
                    </button>

                    <div id="auditorNotifDropdown"
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
                            <a href="{{ route('auditor.genba.index') }}"
                               style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid #F9FAFB;transition:background 0.15s;text-decoration:none;"
                               onmouseover="this.style.background='#FFFBEB'" onmouseout="this.style.background='white'">
                                <div style="width:36px;height:36px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-clock-history" style="color:#D97706;font-size:0.95rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.8rem;font-weight:600;color:#1F2937;line-height:1.3;">Draft Tertunda</p>
                                    <p style="font-size:0.72rem;color:#6B7280;margin-top:2px;line-height:1.4;">
                                        Kamu punya <span style="font-weight:600;color:#D97706;">{{ $draftCount }} checklist</span> belum diselesaikan sejak kemarin
                                    </p>
                                    <p style="font-size:0.68rem;color:#D97706;margin-top:4px;">Lihat riwayat →</p>
                                </div>
                            </a>
                            @endif

                            @if($picaCount > 0)
                            <a href="{{ route('auditor.pica.index') }}"
                               style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid #F9FAFB;transition:background 0.15s;text-decoration:none;"
                               onmouseover="this.style.background='#FFF5F5'" onmouseout="this.style.background='white'">
                                <div style="width:36px;height:36px;border-radius:10px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-tools" style="color:#C8102E;font-size:0.95rem;"></i>
                                </div>
                                <div>
                                    <p style="font-size:0.8rem;font-weight:600;color:#1F2937;line-height:1.3;">PICA Belum Selesai</p>
                                    <p style="font-size:0.72rem;color:#6B7280;margin-top:2px;line-height:1.4;">
                                        Ada <span style="font-weight:600;color:#C8102E;">{{ $picaCount }} temuan</span> masih Open
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
                            <p class="text-xs text-gray-800 leading-tight" style="font-weight:600;">{{ auth()->user()->name }}</p>
                            <p class="text-gray-400" style="font-size:0.65rem;">Auditor</p>
                        </div>
                        <i class="bi bi-chevron-down text-gray-400 hidden sm:block" style="font-size:0.6rem;"></i>
                    </button>

                    <div id="dropdownUser" class="hidden absolute right-0 mt-2 w-52 bg-white">
                        <div class="px-4 py-3" style="border-bottom:1px solid #F3F4F6;">
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width:34px;height:34px;font-size:0.8rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">Auditor</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-1" style="border-top:1px solid #F3F4F6;">
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
            <div class="max-w-5xl mx-auto p-4 md:p-6">

                @if(session('success'))
                    <div class="alert-success mb-5">
                        <i class="bi bi-check-circle-fill text-green-500" style="font-size:1.1rem;flex-shrink:0;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background:#FFF5F5;border:1px solid #FED7D7;border-left:4px solid #C8102E;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:#9B2335;font-weight:500;" class="mb-5">
                        <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;color:#C8102E;"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </main>

    </div>
</div>

{{-- Bottom Nav (Mobile Only) --}}
<nav id="bottom-nav">
    <a href="{{ route('auditor.dashboard') }}"
       class="bottom-nav-item {{ request()->routeIs('auditor.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 bnav-icon"></i>
        <span class="bnav-label">Dashboard</span>
    </a>
    <a href="{{ route('auditor.genba.index') }}"
       class="bottom-nav-item {{ request()->routeIs('auditor.genba.index') ? 'active' : '' }}">
        <i class="bi bi-clock-history bnav-icon"></i>
        <span class="bnav-label">Riwayat</span>
    </a>
    <a href="{{ route('auditor.genba.create') }}" class="bottom-nav-cta">
        <div class="bnav-icon-wrap">
            <i class="bi bi-plus-lg bnav-icon"></i>
        </div>
        <span class="bnav-label">Genba</span>
    </a>
    <a href="{{ route('auditor.pica.index') }}"
       class="bottom-nav-item {{ request()->routeIs('auditor.pica.*') ? 'active' : '' }}">
        <i class="bi bi-tools bnav-icon"></i>
        <span class="bnav-label">PICA</span>
    </a>
    <a href="#" onclick="openSidebar()" class="bottom-nav-item">
        <i class="bi bi-list bnav-icon"></i>
        <span class="bnav-label">Menu</span>
    </a>
</nav>

<script>
// Desktop: collapse sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const texts = document.querySelectorAll('.menu-text');
    const textBlocks = document.querySelectorAll('.menu-text-block');

    if (sidebar.classList.contains('w-64')) {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');
        texts.forEach(el => el.classList.add('hidden'));
        textBlocks.forEach(el => el.classList.add('hidden'));
    } else {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');
        texts.forEach(el => el.classList.remove('hidden'));
        textBlocks.forEach(el => el.classList.remove('hidden'));
    }
}

// Mobile: open drawer
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

// Mobile: close drawer
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Notifikasi
function toggleAuditorNotif() {
    const dropdown = document.getElementById('auditorNotifDropdown');
    dropdown.classList.toggle('hidden');
}

// Loading screen
window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    loader.style.animation = 'fadeOut 0.4s ease forwards';
    setTimeout(() => loader.style.display = 'none', 400);
});

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

document.addEventListener('submit', function() {
    const loader = document.getElementById('pageLoader');
    loader.style.display = 'flex';
    loader.style.opacity = '1';
    loader.style.animation = 'none';
});

// Loading screen - TAMBAHKAN INI
window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    loader.style.animation = 'fadeOut 0.4s ease forwards';
    setTimeout(() => loader.style.display = 'none', 400);
});

window.addEventListener('pageshow', function(e) {
    const loader = document.getElementById('pageLoader');
    loader.style.display = 'none';
    loader.style.opacity = '1';
    loader.style.animation = 'none';
});

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

document.addEventListener('submit', function(e) {
    const form = e.target;
    const action = form.action || '';
    if (action.includes('logout')) return;
    const loader = document.getElementById('pageLoader');
    loader.style.display = 'flex';
    loader.style.opacity = '1';
    loader.style.animation = 'none';
});

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('auditorNotifWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        const dropdown = document.getElementById('auditorNotifDropdown');
        if (dropdown) dropdown.classList.add('hidden');
    }
});

// Close sidebar on nav click (mobile)
document.querySelectorAll('#sidebar .nav-item, #sidebar .nav-item-cta').forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth < 768) closeSidebar();
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>