<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Tenant — {{ $kosName }}</title>

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
                grid-template-columns: 1fr 460px;
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
                top: -80px; right: -80px;
                width: 280px; height: 280px;
                border-radius: 50%;
                background: rgba(255,255,255,.05);
                pointer-events: none;
            }

            .info-panel::after {
                content: '';
                position: absolute;
                bottom: -60px; left: -40px;
                width: 200px; height: 200px;
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
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--ui-accent);
            }

            .info-headline {
                font-size: 36px;
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

            .info-steps {
                display: grid;
                gap: 12px;
                margin-top: 32px;
                position: relative;
            }

            .info-step {
                display: flex;
                align-items: flex-start;
                gap: 14px;
            }

            .step-num {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: rgba(255,255,255,.12);
                border: 1px solid rgba(255,255,255,.2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 700;
                color: rgba(255,255,255,.8);
                flex-shrink: 0;
                margin-top: 1px;
            }

            .step-text {
                color: rgba(255,255,255,.7);
                font-size: 13px;
                line-height: 1.6;
            }

            .step-text strong {
                color: rgba(255,255,255,.9);
                font-weight: 600;
                display: block;
                margin-bottom: 2px;
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
                font-size: 28px;
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

            /* ── RIGHT FORM PANEL ── */
            .form-panel {
                padding: 48px 40px;
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
                font-size: 28px;
                font-weight: 700;
                color: var(--ui-ink);
                line-height: 1.18;
                margin-bottom: 6px;
            }

            .form-sub {
                font-size: 13px;
                color: var(--ui-body);
                line-height: 1.65;
                margin-bottom: 28px;
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

            /* form */
            .form-layout { display: grid; gap: 16px; }

            .field { display: grid; gap: 7px; }

            .field-label {
                font-size: 13px;
                font-weight: 600;
                color: var(--ui-ink);
            }

            .field-hint {
                font-size: 11.5px;
                color: var(--ui-body);
                margin-top: -4px;
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
                height: 46px;
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

            .input.is-error {
                border-color: #f87171;
                background: #fff8f8;
            }

            .field-error {
                font-size: 12px;
                color: #dc2626;
            }

            .divider { border: none; border-top: 1px solid var(--ui-border); margin: 4px 0; }

            .btn-register {
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

            .btn-register:hover {
                background: var(--ui-accent-hover);
                box-shadow: 0 6px 20px rgba(74,124,89,.38);
                transform: translateY(-1px);
            }

            .btn-register:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(74,124,89,.25);
            }

            .login-row {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: 20px;
            }

            .login-text { font-size: 13.5px; color: var(--ui-body); }

            .btn-login-link {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 13.5px;
                font-weight: 600;
                color: var(--ui-accent);
                transition: color .15s, gap .15s;
            }

            .btn-login-link:hover { color: var(--ui-accent-hover); gap: 7px; }

            /* ── RESPONSIVE ── */
            @media (max-width: 820px) {
                .auth-shell {
                    grid-template-columns: 1fr;
                    max-width: 480px;
                }
                .info-panel { padding: 36px 28px; }
                .info-headline { font-size: 26px; }
                .info-bottom { display: none; }
                .form-panel { padding: 36px 28px; }
            }

            @media (max-width: 480px) {
                .page-wrap { padding: 20px 14px; }
                .info-panel, .form-panel { padding: 28px 20px; }
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
                <a href="{{ route('login') }}" class="btn-back">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 12L6 8l4-4"/>
                    </svg>
                    Kembali ke Login
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
                            Pendaftaran Tenant
                        </div>

                        <h1 class="info-headline">
                            Daftar sebagai<br>
                            <em>penghuni</em><br>
                            {{ $kosName }}.
                        </h1>

                        <p class="info-desc">
                            Buat akun tenant untuk mengakses dashboard pembayaran dan informasi kamar kos Anda secara mandiri.
                        </p>

                        <div class="info-steps">
                            <div class="info-step">
                                <div class="step-num">1</div>
                                <div class="step-text">
                                    <strong>Isi data diri</strong>
                                    Nama lengkap, email, nomor HP, dan password.
                                </div>
                            </div>
                            <div class="info-step">
                                <div class="step-num">2</div>
                                <div class="step-text">
                                    <strong>Akun dibuat otomatis</strong>
                                    Role tenant langsung ditetapkan oleh sistem.
                                </div>
                            </div>
                            <div class="info-step">
                                <div class="step-num">3</div>
                                <div class="step-text">
                                    <strong>Masuk ke dashboard</strong>
                                    Pantau tagihan dan status kamar kapan saja.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-bottom">
                        <div class="stat-card">
                            <div class="stat-value">Tenant</div>
                            <div class="stat-label">Role otomatis setelah daftar</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">Free</div>
                            <div class="stat-label">Tidak ada biaya pendaftaran</div>
                        </div>
                    </div>
                </div>

                {{-- ── RIGHT FORM PANEL ── --}}
                <div class="form-panel">
                    <p class="form-eyebrow">Buat akun baru</p>
                    <h2 class="form-title">Daftar sebagai Tenant</h2>
                    <p class="form-sub">Lengkapi data di bawah untuk membuat akun penghuni kos.</p>

                    @if (isset($errors) && $errors->any())
                        <div class="alert">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="8" r="7"/><path d="M8 5v3M8 11v.5"/>
                            </svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="form-layout">
                        @csrf

                        {{-- Nama --}}
                        <div class="field">
                            <label class="field-label" for="name">Nama Lengkap</label>
                            <div class="input-wrap">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="5" r="3"/>
                                    <path d="M2 14c0-3.314 2.686-6 6-6s6 2.686 6 6"/>
                                </svg>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap Anda"
                                    required
                                    autofocus
                                    class="input {{ $errors->has('name') ? 'is-error' : '' }}"
                                >
                            </div>
                            @error('name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
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
                                    class="input {{ $errors->has('email') ? 'is-error' : '' }}"
                                >
                            </div>
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="field">
                            <label class="field-label" for="phone">Nomor HP</label>
                            <div class="input-wrap">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="1" width="8" height="14" rx="2"/>
                                    <circle cx="8" cy="12" r=".8" fill="currentColor" stroke="none"/>
                                </svg>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                    class="input {{ $errors->has('phone') ? 'is-error' : '' }}"
                                >
                            </div>
                            @error('phone')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
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
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="input {{ $errors->has('password') ? 'is-error' : '' }}"
                                >
                            </div>
                            @error('password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="field">
                            <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="7" width="10" height="7" rx="1.5"/>
                                    <path d="M5 7V5a3 3 0 016 0v2"/>
                                    <path d="M6 11l1.5 1.5L10 10" stroke-width="1.8"/>
                                </svg>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Ulangi password"
                                    required
                                    class="input"
                                >
                            </div>
                        </div>

                        <hr class="divider">

                        <button type="submit" class="btn-register">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2v12M2 8h12"/>
                            </svg>
                            Buat Akun Tenant
                        </button>
                    </form>

                    <div class="login-row">
                        <span class="login-text">Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="btn-login-link">
                            Masuk sekarang
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