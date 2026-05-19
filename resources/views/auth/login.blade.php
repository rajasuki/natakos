<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login {{ $kosName }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                --ui-ink:            #1C2B22;
                --ui-body:           #5B7060;
                --ui-canvas:         #FBF8F3;
                --ui-soft:           #EEF5EF;
                --ui-softer:         #F5FAF5;
                --ui-shadow:         rgba(28,43,34,.10) 0px 4px 16px 0px;
                --ui-shadow-strong:  rgba(28,43,34,.16) 0px 4px 16px 0px;
                --ui-accent:         #4A7C59;
                --ui-accent-hover:   #3D6A4A;
                --ui-accent-soft:    #EEF5EF;
                --ui-border:         #E0EBE2;
                --radius-sm: 8px;
                --radius-md: 14px;
                --radius-lg: 22px;
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            html, body { height: 100%; }

            body {
                background: var(--ui-canvas);
                color: var(--ui-ink);
                line-height: 1.5;
                min-height: 100vh;
                font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            a { color: inherit; text-decoration: none; }

            /* ── HEADER ── */
            .site-header {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(255,255,255,0.92);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--ui-border);
            }

            .header-inner {
                max-width: 1180px;
                margin: 0 auto;
                padding: 0 28px;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .brand {
                font-size: 22px;
                font-weight: 700;
                color: var(--ui-accent);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .brand-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--ui-accent);
                flex-shrink: 0;
            }

            .btn-back {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                border-radius: 999px;
                border: 1px solid var(--ui-border);
                background: #fff;
                color: var(--ui-body);
                font-size: 13px;
                font-weight: 500;
                cursor: pointer;
                transition: border-color .18s, color .18s, box-shadow .18s;
                box-shadow: var(--ui-shadow);
            }

            .btn-back:hover {
                border-color: var(--ui-accent);
                color: var(--ui-accent-hover);
                box-shadow: var(--ui-shadow);
            }

            .btn-back svg { flex-shrink: 0; }

            /* ── LAYOUT ── */
            .page-wrap {
                min-height: calc(100vh - 64px);
                display: grid;
                place-items: center;
                padding: 48px 24px;
            }

            .auth-shell {
                width: 100%;
                max-width: 980px;
                display: grid;
                grid-template-columns: 1fr 420px;
                gap: 0;
                background: #fff;
                border-radius: var(--radius-lg);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow-strong);
                overflow: hidden;
            }

            /* ── LEFT PANEL ── */
            .info-panel {
                background: linear-gradient(160deg, var(--ui-accent-hover) 0%, var(--ui-ink) 100%);
                padding: 52px 44px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                overflow: hidden;
            }

            .info-panel::before {
                content: '';
                position: absolute;
                top: -80px;
                right: -80px;
                width: 280px;
                height: 280px;
                border-radius: 50%;
                background: rgba(255,255,255,.05);
                pointer-events: none;
            }

            .info-panel::after {
                content: '';
                position: absolute;
                bottom: -60px;
                left: -40px;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: rgba(255,255,255,.04);
                pointer-events: none;
            }

            .info-top { position: relative; }

            .info-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 999px;
                background: rgba(255,255,255,.12);
                border: 1px solid rgba(255,255,255,.18);
                color: rgba(255,255,255,.8);
                font-size: 11px;
                font-weight: 600;
                letter-spacing: .12em;
                text-transform: uppercase;
                margin-bottom: 28px;
            }

            .info-tag-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: var(--ui-accent);
                animation: pulse-dot 2s ease-in-out infinite;
            }

            @keyframes pulse-dot {
                0%, 100% { opacity: 1; transform: scale(1); }
                50%       { opacity: .5; transform: scale(1.3); }
            }

            .info-headline {
                font-size: 38px;
                font-weight: 700;
                line-height: 1.15;
                color: #ffffff;
                margin-bottom: 20px;
            }

            .info-headline em {
                font-style: italic;
                color: #6FAE82;
            }

            .info-desc {
                color: rgba(255,255,255,.65);
                font-size: 14px;
                line-height: 1.75;
                max-width: 340px;
            }

            .info-desc code {
                background: rgba(255,255,255,.12);
                border-radius: 4px;
                padding: 1px 6px;
                font-size: 12px;
                color: #6FAE82;
            }

            .info-chips {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 32px;
            }

            .info-chip {
                padding: 7px 14px;
                border-radius: 999px;
                background: rgba(255,255,255,.1);
                border: 1px solid rgba(255,255,255,.15);
                color: rgba(255,255,255,.8);
                font-size: 12px;
                font-weight: 500;
            }

            .info-bottom {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                position: relative;
                margin-top: 48px;
            }

            .stat-card {
                background: rgba(255,255,255,.08);
                border: 1px solid rgba(255,255,255,.12);
                border-radius: var(--radius-md);
                padding: 18px 20px;
            }

            .stat-value {
                font-size: 32px;
                font-weight: 700;
                color: #ffffff;
                line-height: 1;
                margin-bottom: 6px;
            }

            .stat-label {
                color: rgba(255,255,255,.55);
                font-size: 12px;
                line-height: 1.5;
            }

            /* ── RIGHT PANEL (FORM) ── */
            .form-panel {
                padding: 52px 44px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background: #fff;
            }

            .form-eyebrow {
                font-size: 11px;
                font-weight: 600;
                letter-spacing: .14em;
                text-transform: uppercase;
                color: var(--ui-accent);
                margin-bottom: 10px;
            }

            .form-title {
                font-size: 30px;
                font-weight: 700;
                color: var(--ui-ink);
                line-height: 1.18;
                margin-bottom: 6px;
            }

            .form-sub {
                font-size: 13.5px;
                color: var(--ui-body);
                line-height: 1.65;
                margin-bottom: 32px;
            }

            /* alert */
            .alert {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 14px 16px;
                border-radius: var(--radius-sm);
                border: 1px solid #fca5a5;
                background: #fff5f5;
                color: #b91c1c;
                font-size: 13px;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .alert svg { flex-shrink: 0; margin-top: 1px; }

            /* form */
            .form-layout { display: grid; gap: 20px; }

            .field { display: grid; gap: 7px; }

            .field-label {
                font-size: 13px;
                font-weight: 600;
                color: var(--ui-ink);
            }

            .input-wrap { position: relative; }

            .input-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #A3B8A8;
                pointer-events: none;
            }

            .input {
                width: 100%;
                height: 48px;
                padding: 0 16px 0 42px;
                border: 1.5px solid var(--ui-border);
                border-radius: var(--radius-sm);
                background: var(--ui-canvas);
                color: var(--ui-ink);
                font-family: inherit;
                font-size: 14px;
                transition: border-color .18s, background .18s, box-shadow .18s;
            }

            .input::placeholder { color: #B0C4B6; }

            .input:focus {
                outline: none;
                border-color: var(--ui-accent);
                background: #fff;
                box-shadow: 0 0 0 3px rgba(74,124,89,.12);
            }

            .remember-row {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .remember-row input[type=checkbox] {
                appearance: none;
                width: 18px;
                height: 18px;
                border: 1.5px solid var(--ui-border);
                border-radius: 4px;
                background: var(--ui-canvas);
                cursor: pointer;
                transition: border-color .15s, background .15s;
                flex-shrink: 0;
                position: relative;
            }

            .remember-row input[type=checkbox]:checked {
                background: var(--ui-accent);
                border-color: var(--ui-accent);
            }

            .remember-row input[type=checkbox]:checked::after {
                content: '';
                position: absolute;
                left: 4px;
                top: 1.5px;
                width: 6px;
                height: 10px;
                border: 2px solid #fff;
                border-top: 0;
                border-left: 0;
                transform: rotate(45deg);
            }

            .remember-label {
                font-size: 13.5px;
                color: var(--ui-body);
                cursor: pointer;
            }

            .divider {
                border: none;
                border-top: 1px solid var(--ui-border);
                margin: 4px 0;
            }

            .btn-login {
                width: 100%;
                height: 50px;
                border: none;
                border-radius: var(--radius-sm);
                background: var(--ui-accent);
                color: #ffffff;
                font-family: inherit;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                letter-spacing: .01em;
                box-shadow: 0 3px 12px rgba(74,124,89,.3);
                transition: transform .15s, box-shadow .15s, background .15s;
            }

            .btn-login:hover {
                background: var(--ui-accent-hover);
                box-shadow: 0 6px 20px rgba(74,124,89,.38);
                transform: translateY(-1px);
            }

            .btn-login:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(74,124,89,.25);
            }

            /* register link */
            .register-row {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: 20px;
            }

            .register-text {
                font-size: 13.5px;
                color: var(--ui-body);
            }

            .btn-register {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 13.5px;
                font-weight: 600;
                color: var(--ui-accent);
                transition: color .15s, gap .15s;
            }

            .btn-register:hover {
                color: var(--ui-accent-hover);
                gap: 7px;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 820px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                    max-width: 480px;
                }

                .info-panel {
                    padding: 40px 32px;
                }

                .info-headline { font-size: 28px; }

                .info-bottom { display: none; }

                .form-panel {
                    padding: 40px 32px;
                }
            }

            @media (max-width: 480px) {
                .page-wrap { padding: 24px 16px; }
                .info-panel, .form-panel { padding: 32px 24px; }
                .info-chips { gap: 6px; }
            }
        </style>
    </head>
    <body>

        <header class="site-header">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-dot"></span>
                    {{ $kosName }}
                </a>
                <a href="{{ route('home') }}" class="btn-back">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 12L6 8l4-4"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </header>

        <main class="page-wrap">
            <div class="auth-shell">

                {{-- ── LEFT INFO PANEL ── --}}
                <div class="info-panel">
                    <div class="info-top">
                        <div class="info-tag">
                            <span class="info-tag-dot"></span>
                            Akses aplikasi
                        </div>

                        <h1 class="info-headline">
                            Masuk ke<br>
                            <em>dashboard</em><br>
                            kos Anda.
                        </h1>

                        <p class="info-desc">
                            Gunakan akun yang sudah terdaftar di database
                            <code>natakos</code>. Sistem akan otomatis mengarahkan Anda
                            ke dashboard sesuai role.
                        </p>

                        <div class="info-chips">
                            <span class="info-chip">Admin &amp; Tenant</span>
                            <span class="info-chip">Redirect Otomatis</span>
                            <span class="info-chip">Session Aman</span>
                        </div>
                    </div>

                    <div class="info-bottom">
                        <div class="stat-card">
                            <div class="stat-value">2</div>
                            <div class="stat-label">Role utama: admin &amp; tenant</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">1</div>
                            <div class="stat-label">Sistem kos terintegrasi</div>
                        </div>
                    </div>
                </div>

                {{-- ── RIGHT FORM PANEL ── --}}
                <div class="form-panel">
                    <p class="form-eyebrow">Masuk ke akun</p>
                    <h2 class="form-title">Login {{ $kosName }}</h2>
                    <p class="form-sub">Masukkan email dan password untuk melanjutkan ke dashboard.</p>

                    @if (isset($errors) && $errors->any())
                        <div class="alert">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="8" r="7"/>
                                <path d="M8 5v3M8 11v.5"/>
                            </svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="form-layout">
                        @csrf

                        <div class="field">
                            <label class="field-label" for="email">Email</label>
                            <div class="input-wrap">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="14" height="10" rx="2"/>
                                    <path d="M1 5l7 4.5L15 5"/>
                                </svg>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                    autofocus
                                    class="input"
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label" for="password">Password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="7" width="10" height="7" rx="1.5"/>
                                    <path d="M5 7V5a3 3 0 016 0v2"/>
                                </svg>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="••••••••"
                                    required
                                    class="input"
                                >
                            </div>
                        </div>

                        <div class="remember-row">
                            <input id="remember" type="checkbox" name="remember" value="1">
                            <label for="remember" class="remember-label">Ingat saya di perangkat ini</label>
                        </div>

                        <hr class="divider">

                        <button type="submit" class="btn-login">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 8H2M7 5l3 3-3 3"/>
                                <path d="M6 3H13a1 1 0 011 1v8a1 1 0 01-1 1H6"/>
                            </svg>
                            Masuk Sekarang
                        </button>
                    </form>

                    <div class="register-row">
                        <span class="register-text">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="btn-register">
                            Daftar sekarang
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 4l4 4-4 4"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </main>

    </body>
</html>