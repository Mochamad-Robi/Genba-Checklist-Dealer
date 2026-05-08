<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Genba MSK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/bgputih.png') }}" type="image/png">
    <style>
        :root {
            --red: #C8102E;
            --red-dark: #9B0B22;
            --red-deeper: #7A0919;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            overflow: hidden;
            background: #0a0a0a;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            width: 460px;
            min-width: 460px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 44px;
            position: relative;
            z-index: 2;
            overflow-y: auto;
            gap: 32px;
            box-shadow: 8px 0 40px rgba(0,0,0,0.15);
        }

        /* Subtle red accent strip on left edge */
        .left-panel::before {
            content: '';
            position: absolute;
            left: 0; top: 10%; bottom: 10%;
            width: 4px;
            background: linear-gradient(to bottom, transparent, var(--red), transparent);
            border-radius: 0 4px 4px 0;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .right-bg {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('assets/logo1.jpeg') }}');
            background-size: cover;
            background-position: center;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .right-panel:hover .right-bg {
            transform: scale(1.0);
        }

        /* Dark overlay */
        .right-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.55) 0%, rgba(155,11,34,0.3) 100%);
            z-index: 1;
        }

        /* Animated grid lines on right panel */
        .right-grid {
            position: absolute;
            inset: 0;
            z-index: 2;
            background-image:
                linear-gradient(rgba(200,16,46,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(200,16,46,0.08) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: 60px 60px; }
        }

        .right-overlay {
            position: absolute;
            bottom: 48px;
            left: 48px;
            right: 48px;
            z-index: 3;
        }

        /* ── FLOATING PARTICLES ── */
        .particles {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(200,16,46,0.6);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
        }

        /* ── FORM ELEMENTS ── */
        .input-field {
            width: 100%;
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            color: #1F2937;
            font-size: 0.875rem;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            outline: none;
        }

        .input-field:focus {
            border-color: var(--red);
            background: #FFF5F5;
            box-shadow: 0 0 0 3px rgba(200,16,46,0.1);
            transform: translateY(-1px);
        }

        .input-field::placeholder { color: #9CA3AF; }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrapper:focus-within .input-icon { color: var(--red); }

        /* ── BUTTON ── */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 4px 20px rgba(200,16,46,0.35);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before { left: 100%; }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(200,16,46,0.5);
        }

        .btn-login:active { transform: translateY(0); }

        /* ── ERROR ── */
        .error-msg {
            color: #EF4444;
            font-size: 0.75rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        /* ── LABEL ── */
        .field-label {
            color: #6B7280;
            font-size: 0.72rem;
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── ENTRANCE ANIMATIONS ── */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-up:nth-child(1) { animation-delay: 0.1s; }
        .fade-up:nth-child(2) { animation-delay: 0.2s; }
        .fade-up:nth-child(3) { animation-delay: 0.3s; }
        .fade-up:nth-child(4) { animation-delay: 0.4s; }
        .fade-up:nth-child(5) { animation-delay: 0.5s; }
        .fade-up:nth-child(6) { animation-delay: 0.6s; }

        /* ── LOGO PULSE ── */
        .logo-wrap {
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { filter: drop-shadow(0 0 0px rgba(200,16,46,0)); }
            50% { filter: drop-shadow(0 0 12px rgba(200,16,46,0.3)); }
        }

        /* ── CAPTCHA CONTAINER ── */
        .captcha-box {
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
            background: #F9FAFB;
            transition: border-color 0.2s;
        }

        .captcha-box:hover { border-color: var(--red); }

        /* ── REFRESH BTN ── */
        .refresh-btn {
            width: 44px; height: 44px;
            background: #F3F4F6;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .refresh-btn:hover {
            background: #FEE2E2;
            border-color: var(--red);
            transform: rotate(180deg);
        }

        .refresh-btn i { color: #6B7280; font-size: 1rem; transition: color 0.2s; }
        .refresh-btn:hover i { color: var(--red); }

        /* ── RIGHT PANEL TEXT ── */
        .right-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 12px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.4);
        }

        .right-sub {
            color: rgba(255,255,255,0.75);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        /* ── DIVIDER LINE ── */
        .red-line {
            width: 32px; height: 3px;
            background: var(--red);
            border-radius: 2px;
            margin: 12px 0 0;
        }

        /* ── BLINK PULSE DOT ── */
        .pulse-dot {
            width: 7px; height: 7px;
            background: #fff;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }

        /* ── MOBILE ── */
        @media (max-width: 767px) {
            body { flex-direction: column; background: white; }
            .left-panel { width: 100%; min-width: unset; padding: 32px 24px; box-shadow: none; }
            .right-panel { display: none; }
        }
    </style>
</head>
<body>

{{-- LEFT PANEL --}}
<div class="left-panel">

    {{-- Logo --}}
    <div class="fade-up" style="text-align:center;">
        <div class="logo-wrap" style="width:72px;height:72px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <h1 style="color:#1F2937;font-weight:800;font-size:1.4rem;line-height:1.2;">Genba MSK</h1>
        <p style="color:#9CA3AF;font-size:0.78rem;margin-top:6px;">Digital Network Monitoring System</p>
        <div class="red-line" style="margin:10px auto 0;"></div>
    </div>

    {{-- Form --}}
    <div>
        @if(session('status'))
        <div class="fade-up" style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:10px 14px;margin-bottom:16px;color:#16A34A;font-size:0.8rem;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="fade-up" style="margin-bottom:18px;">
                <label class="field-label">Email</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" id="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email kamu"
                        class="input-field" required autofocus>
                </div>
                @error('email')
                <p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="fade-up" style="margin-bottom:18px;">
                <label class="field-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="password"
                        placeholder="Masukkan password"
                        class="input-field" required>
                    <button type="button" onclick="togglePassword()"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:0.9rem;padding:0;transition:color 0.2s;"
                        onmouseover="this.style.color='#C8102E'" onmouseout="this.style.color='#9CA3AF'">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="fade-up" style="display:flex;align-items:center;margin-bottom:20px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="remember" id="remember_me"
                        style="width:15px;height:15px;accent-color:#C8102E;border-radius:4px;">
                    <span style="color:#6B7280;font-size:0.8rem;">Ingat saya</span>
                </label>
            </div>

            {{-- Captcha --}}
            <div class="fade-up" style="margin-bottom:22px;">
                <label class="field-label">Captcha <span style="color:#C8102E;">*</span></label>

                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div class="captcha-box">
                        <img id="captchaImg" src="{{ route('captcha') }}" alt="Captcha"
                             style="width:150px;display:block;cursor:pointer;"
                             onclick="refreshCaptcha()" title="Klik untuk refresh">
                    </div>
                    <button type="button" onclick="refreshCaptcha()" class="refresh-btn" title="Refresh CAPTCHA">
                        <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
                    </button>
                </div>

                <div class="input-wrapper">
                    <i class="bi bi-shield-check input-icon"></i>
                    <input type="text" name="captcha" id="captcha"
                        placeholder="Masukkan kode di atas"
                        class="input-field"
                        style="text-transform:uppercase;letter-spacing:0.2em;font-weight:600;"
                        required autocomplete="off">
                </div>

                @error('captcha')
                <p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="fade-up">
                <button type="submit" class="btn-login" id="submitBtn">
                    <span id="btnText">Masuk &nbsp;<i class="bi bi-arrow-right"></i></span>
                    <span id="btnLoader" style="display:none;">
                        <i class="bi bi-arrow-repeat" style="animation:spin 0.8s linear infinite;display:inline-block;"></i>
                        &nbsp;Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    <div class="fade-up" style="text-align:center;padding-top:16px;border-top:1px solid #F3F4F6;">
        <div style="display:inline-flex;align-items:center;gap:6px;background:#C8102E;color:white;font-size:0.65rem;font-weight:700;letter-spacing:0.1em;padding:3px 10px;border-radius:4px;text-transform:uppercase;">
            <i class="bi bi-patch-check-fill"></i> PT MSK
        </div>
        <p style="color:#9CA3AF;font-size:0.68rem;margin-top:6px;">© {{ date('Y') }} Genba System. All rights reserved.</p>
    </div>

</div>

{{-- RIGHT PANEL --}}
<div class="right-panel">
    <div class="right-bg"></div>
    <div class="right-grid"></div>

    {{-- Floating particles --}}
    <div class="particles" id="particles"></div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
// Floating particles
(function() {
    const container = document.getElementById('particles');
    if (!container) return;
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.width = p.style.height = (Math.random() * 3 + 2) + 'px';
        p.style.animationDuration = (Math.random() * 15 + 10) + 's';
        p.style.animationDelay = (Math.random() * 10) + 's';
        p.style.opacity = Math.random() * 0.6 + 0.2;
        container.appendChild(p);
    }
})();

// Refresh captcha
function refreshCaptcha() {
    const img = document.getElementById('captchaImg');
    const icon = document.getElementById('refreshIcon');
    icon.style.animation = 'spin 0.5s linear';
    setTimeout(() => icon.style.animation = '', 500);
    img.src = '{{ route('captcha') }}?' + new Date().getTime();
    document.getElementById('captcha').value = '';
}

// Toggle password visibility
function togglePassword() {
    const pw = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pw.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Loading state on submit
document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnLoader').style.display = 'inline';
    document.getElementById('submitBtn').disabled = true;
});
</script>

</body>
</html>