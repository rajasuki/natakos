<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login NATAKOS</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                --ui-ink: #000000;
                --ui-body: #5e5e5e;
                --ui-canvas: #ffffff;
                --ui-soft: #efefef;
                --ui-softer: #f3f3f3;
                --ui-border: #e2e2e2;
                --ui-danger: #fee2e2;
                --ui-shadow: rgba(0, 0, 0, 0.12) 0px 4px 16px 0px;
                --ui-shadow-soft: rgba(0, 0, 0, 0.08) 0px 2px 8px 0px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--ui-canvas);
                color: var(--ui-ink);
                line-height: 1.5;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .site-shell {
                width: 100%;
                max-width: 1180px;
                margin: 0 auto;
                padding-left: 24px;
                padding-right: 24px;
            }

            .site-header {
                position: sticky;
                top: 0;
                z-index: 20;
                border-bottom: 1px solid var(--ui-border);
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(8px);
            }

            .header-row {
                display: flex;
                flex-direction: column;
                gap: 16px;
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .brand {
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
            }

            .nav-row,
            .button-row,
            .chip-row {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .button,
            .chip {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
            }

            .button {
                min-height: 44px;
                padding: 14px 18px;
                border: 0;
                cursor: pointer;
                transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            }

            .button-primary {
                background: var(--ui-ink);
                color: var(--ui-canvas);
            }

            .button-primary:hover {
                background: #282828;
            }

            .button-secondary {
                background: var(--ui-canvas);
                color: var(--ui-ink);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow-soft);
            }

            .button-secondary:hover {
                background: var(--ui-soft);
            }

            .page-section {
                padding-top: 36px;
                padding-bottom: 40px;
            }

            .auth-grid {
                display: grid;
                gap: 24px;
                align-items: stretch;
            }

            .hero-band,
            .hero-card,
            .auth-card {
                border-radius: 16px;
            }

            .hero-band,
            .auth-card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }

            .hero-band {
                padding: 32px;
            }

            .hero-card {
                background: var(--ui-soft);
                padding: 24px;
                border: 1px solid var(--ui-border);
            }

            .eyebrow {
                margin: 0 0 12px;
                color: var(--ui-body);
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            .headline,
            .card-title {
                margin: 0;
                font-weight: 700;
                line-height: 1.18;
            }

            .headline {
                font-size: 40px;
                max-width: 620px;
            }

            .card-title {
                font-size: 28px;
            }

            .lead,
            .muted,
            .card-copy,
            .field-copy {
                color: var(--ui-body);
                line-height: 1.7;
            }

            .lead {
                margin: 16px 0 0;
                max-width: 560px;
                font-size: 16px;
            }

            .muted,
            .card-copy,
            .field-copy {
                font-size: 14px;
            }

            .hero-stats {
                display: grid;
                gap: 16px;
                margin-top: 24px;
            }

            .hero-stat {
                display: grid;
                gap: 6px;
                padding: 16px;
                border-radius: 16px;
                background: var(--ui-canvas);
                box-shadow: var(--ui-shadow-soft);
            }

            .hero-stat-value {
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
            }

            .hero-stat-label {
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.5;
            }

            .chip {
                padding: 8px 12px;
                background: var(--ui-soft);
                color: var(--ui-ink);
                font-size: 12px;
            }

            .chip-row-spaced {
                margin-top: 24px;
            }

            .auth-card {
                padding: 28px;
            }

            .alert {
                margin-bottom: 18px;
                padding: 14px 16px;
                border-radius: 16px;
                border: 1px solid #fecaca;
                background: var(--ui-danger);
                color: #991b1b;
                font-size: 14px;
                line-height: 1.6;
            }

            .form-layout {
                display: grid;
                gap: 18px;
            }

            .field {
                display: grid;
                gap: 8px;
            }

            label {
                font-size: 14px;
                font-weight: 600;
            }

            .input {
                width: 100%;
                border: 1px solid #d4d4d4;
                background: var(--ui-softer);
                color: var(--ui-ink);
                padding: 14px 16px;
                border-radius: 8px;
                font: inherit;
            }

            .input:focus {
                outline: none;
                border-color: var(--ui-ink);
                background: var(--ui-canvas);
            }

            .remember-row {
                display: flex;
                align-items: center;
                gap: 10px;
                color: var(--ui-body);
                font-size: 14px;
            }

            .remember-row label {
                margin: 0;
                font-weight: 500;
            }

            .section-divider {
                border-top: 1px solid var(--ui-border);
            }

            .button:focus-visible,
            .input:focus-visible {
                outline: 2px solid var(--ui-ink);
                outline-offset: 2px;
            }

            @media (max-width: 767px) {
                .headline {
                    font-size: 32px;
                }

                .hero-band,
                .hero-card,
                .auth-card {
                    padding-left: 18px;
                    padding-right: 18px;
                }
            }

            @media (min-width: 900px) {
                .auth-grid {
                    grid-template-columns: 1.08fr 0.92fr;
                }
            }

            @media (min-width: 1024px) {
                .headline {
                    font-size: 52px;
                }

                .header-row {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }
            }
        </style>
    </head>
    <body>
        <header class="site-header">
            <div class="site-shell header-row">
                <a href="{{ route('home') }}" class="brand">NATAKOS</a>

                <div class="nav-row">
                    <a href="{{ route('home') }}" class="button button-secondary">Kembali ke homepage</a>
                </div>
            </div>
        </header>

        <main class="page-section">
            <div class="site-shell auth-grid">
                <section class="hero-band">
                    <p class="eyebrow">Akses aplikasi</p>
                    <h1 class="headline">Masuk ke dashboard admin atau penghuni dengan tampilan yang jelas dan alur yang sederhana.</h1>
                    <p class="lead">
                        Gunakan akun yang sudah terdaftar di database <code>natakos</code>. Sistem akan langsung mengarahkan Anda ke dashboard sesuai role.
                    </p>

                    <div class="chip-row chip-row-spaced">
                        <span class="chip">Admin dan tenant dalam satu alur login</span>
                        <span class="chip">Redirect otomatis sesuai role</span>
                        <span class="chip">Keamanan session Laravel</span>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-value">2</div>
                            <div class="hero-stat-label">Role utama: admin dan tenant</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value">1</div>
                            <div class="hero-stat-label">Aplikasi kos yang terkelola untuk kamar, penghuni, dan pembayaran manual</div>
                        </div>
                    </div>
                </section>

                <aside class="hero-card">
                    <p class="eyebrow">Masuk ke akun</p>
                    <div class="auth-card">
                        <h2 class="card-title">Login NATAKOS</h2>
                        <p class="card-copy">Masukkan email dan password akun Anda untuk melanjutkan ke dashboard.</p>

                        @if (isset($errors) && $errors->any())
                            <div class="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="form-layout">
                            @csrf

                            <div class="field">
                                <label for="email">Email</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="input"
                                >
                            </div>

                            <div class="field">
                                <label for="password">Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="input"
                                >
                            </div>

                            <div class="remember-row">
                                <input id="remember" type="checkbox" name="remember" value="1">
                                <label for="remember">Ingat saya</label>
                            </div>

                            <div class="section-divider"></div>

                            <button type="submit" class="button button-primary">
                                Login
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </main>
    </body>
</html>
