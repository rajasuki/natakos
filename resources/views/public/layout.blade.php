<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'NATAKOS')</title>

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
            }

            *, *::before, *::after { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--ui-canvas);
                color: var(--ui-ink);
                line-height: 1.5;
            }

            a { color: inherit; text-decoration: none; }
            img { max-width: 100%; height: auto; display: block; }

            /* ── Shell ─────────────────────────────── */
            .site-shell {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding-left: 24px;
                padding-right: 24px;
            }

            /* ── Header ────────────────────────────── */
            .site-header {
                position: sticky;
                top: 0;
                z-index: 30;
                border-bottom: 1px solid var(--ui-border);
<<<<<<< HEAD
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(10px);
=======
                background: rgba(255,255,255,.96);
                backdrop-filter: blur(8px);
>>>>>>> origin/ardhan
            }

            .header-row {
                display: flex;
                flex-direction: column;
                gap: 14px;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            /* ── Brand ─────────────────────────────── */
            .brand {
                font-size: 26px;
                font-weight: 700;
                line-height: 1;
            }

            /* ── Nav ───────────────────────────────── */
            .nav-row {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links {
                align-items: center;
                justify-content: center;
                gap: 14px 24px;
            }

            .button-row {
                justify-content: center;
            }

            .nav-links,
            .button-row,
            .chip-row,
            .room-card-chips,
            .detail-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .nav-link,
            .button,
            .chip,
            .status-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
            }

            .nav-link {
<<<<<<< HEAD
                min-height: 0;
                padding: 0 0 8px;
                border-radius: 0;
                border-bottom: 2px solid transparent;
                color: var(--ui-body);
                font-weight: 500;
                background: transparent;
                transition: border-color 0.2s ease, color 0.2s ease;
            }

            .nav-link:hover {
                color: var(--ui-ink);
            }

            .nav-link.is-active {
                border-color: var(--ui-ink);
                color: var(--ui-ink);
            }

            .nav-login-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 0;
                padding: 0;
                color: var(--ui-ink);
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
                transition: color 0.2s ease, opacity 0.2s ease;
            }

            .nav-login-link:hover {
                opacity: 0.72;
=======
                padding: 12px 18px;
                min-height: 44px;
                background: var(--ui-soft);
                transition: background-color .2s ease, color .2s ease;
>>>>>>> origin/ardhan
            }
            .nav-link:hover    { background: var(--ui-border); }
            .nav-link.is-active { background: var(--ui-ink); color: var(--ui-canvas); }

            /* ── Buttons ───────────────────────────── */
            .button {
                border: 0;
                cursor: pointer;
                padding: 14px 18px;
                min-height: 44px;
                transition: background-color .2s ease, color .2s ease, box-shadow .2s ease;
            }

            .button-primary   { background: var(--ui-accent);       color: #fff; }
            .button-primary:hover { background: var(--ui-accent-hover); }

            .button-secondary {
                background: var(--ui-canvas);
                color: var(--ui-ink);
                box-shadow: rgba(0,0,0,.16) 0px 2px 8px 0px;
            }
            .button-secondary:hover { background: var(--ui-soft); }

            .button-subtle        { background: var(--ui-soft); color: var(--ui-ink); }
            .button-subtle:hover  { background: var(--ui-border); }

            /* ── Sections ──────────────────────────── */
            .page-section { padding-top: 32px; padding-bottom: 32px; }
            .page-stack   { display: grid; gap: 24px; }

            .section-dark { background: #1C2B22; color: #fff; }
            .section-dark .eyebrow { color: #6FAE82; }
            .contact-band { background: #1C2B22; }
            .nearby-marker,
            .nearby-estimate { background: var(--ui-accent); }
            .status-available { background: var(--ui-accent); color: #fff; }
            .chip-accent { background: var(--ui-accent); color: #fff; }
            

            /* ── Hero ──────────────────────────────── */
            .hero { display: grid; gap: 20px; align-items: stretch; }

            .hero-band,
            .hero-card,
            .feature-card,
            .room-card,
            .empty-state,
            .detail-card,
            .gallery-card,
            .contact-band {
                border-radius: 16px;
            }

            .hero-band {
                background: var(--ui-canvas);
                padding: 32px;
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }

            .hero-card,
            .feature-card,
            .room-card,
            .detail-card,
            .gallery-card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                overflow: hidden;
                box-shadow: var(--ui-shadow);
            }

            .hero-card { background: var(--ui-soft); padding: 24px; }

            /* ── Contact band ──────────────────────── */
            .contact-band {
                background: #000;
                color: #fff;
                padding: 28px;
            }

            /* ── Typography ────────────────────────── */
            .eyebrow {
                margin: 0 0 12px;
                color: var(--ui-body);
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .2em;
                text-transform: uppercase;
            }
            .section-dark .eyebrow,
            .contact-band .eyebrow { color: #afafaf; }

            .headline,
            .section-title,
            .room-title,
            .detail-title {
                margin: 0;
                font-weight: 700;
                line-height: 1.18;
            }

            .headline      { font-size: 40px; max-width: 620px; }
            .section-title { font-size: 32px; margin-bottom: 10px; }
            .room-title,
            .detail-title  { font-size: 24px; }

            .lead,
            .section-copy,
            .muted,
            .room-copy,
            .detail-copy,
            .footer-copy { color: var(--ui-body); line-height: 1.7; }

            .lead { margin: 16px 0 0; max-width: 560px; font-size: 16px; }

            .section-copy,
            .detail-copy,
            .room-copy,
            .footer-copy,
            .muted { font-size: 14px; }

            /* ── Grids ─────────────────────────────── */
            .hero-stats,
            .room-grid,
            .feature-grid,
            .detail-grid,
            .gallery-grid,
            .facilities-grid { display: grid; gap: 16px; }

            /* ── Stat cards ────────────────────────── */
            .hero-stat {
                display: grid;
                gap: 6px;
                padding: 16px;
                background: var(--ui-canvas);
                border-radius: 16px;
                box-shadow: var(--ui-shadow);
            }
            .hero-stat-value { font-size: 28px; font-weight: 700; line-height: 1; }
            .hero-stat-label { color: var(--ui-body); font-size: 13px; line-height: 1.5; }

            /* ── Section header ────────────────────── */
            .section-header          { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
            .section-header-tight    { margin-bottom: 0; }
            .section-actions         { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 18px; }
            .section-copy-on-dark    { color: #afafaf; }
            .section-copy-compact    { margin-bottom: 20px; }
            .section-title-tight     { margin-bottom: 12px; }
            .spaced-top-sm           { margin-top: 16px; }
            .spaced-top-md           { margin-top: 18px; }
            .spaced-top-lg           { margin-top: 24px; }

            .section-split {
                display: flex;
                flex-direction: column;
                gap: 16px;
                margin-bottom: 20px;
            }

            /* ── Media ─────────────────────────────── */
            .room-card-media,
            .detail-media,
            .gallery-image {
                width: 100%;
                background: var(--ui-soft);
                object-fit: cover;
                display: block;
            }
            .room-card-media,
            .detail-media { aspect-ratio: 4 / 3; }
            .gallery-image { aspect-ratio: 4 / 3; }

            .media-placeholder {
                display: grid;
                place-items: center;
                color: var(--ui-body);
                text-align: center;
                padding: 24px;
            }

            /* ── Card bodies ───────────────────────── */
            .room-card-body,
            .detail-body,
            .feature-card-body,
            .gallery-card-body { padding: 22px; }

            .room-card-head { display: flex; flex-direction: column; gap: 12px; margin-bottom: 14px; }

            .room-card-topbar {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
                justify-content: space-between;
            }
            .room-card-topbar-spaced { margin-bottom: 16px; }

            .room-card-footer {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
                justify-content: space-between;
                margin-top: 18px;
            }

            /* ── Badges & chips ────────────────────── */
            .status-badge,
            .chip { padding: 8px 12px; font-size: 12px; }

            .status-available   { background: #000; color: #fff; }
            .status-occupied    { background: var(--ui-soft); color: #000; }
            .status-maintenance { background: #d9d9d9; color: #000; }

            .chip { background: var(--ui-soft); color: #000; }

            /* ── Detail list ───────────────────────── */
            .detail-list { display: grid; gap: 14px; margin: 20px 0; }

            .spec-grid   { display: grid; gap: 14px; }

            .detail-item  { display: grid; gap: 6px; }
            .detail-label { color: var(--ui-body); font-size: 13px; }
            .detail-value { font-size: 15px; line-height: 1.6; font-weight: 600; }

            /* ── Map ───────────────────────────────── */
            .map-embed,
            .map-placeholder {
                width: 100%;
                min-height: 340px;
                border: 0;
                display: block;
                background: var(--ui-soft);
            }
            .map-placeholder { display: grid; align-items: center; padding: 24px; }

            /* ── Nearby ────────────────────────────── */
            .nearby-list  { display: grid; gap: 12px; }

            .nearby-item,
            .nearby-empty {
                display: grid;
                gap: 14px;
                padding: 16px;
                background: var(--ui-soft);
                border-radius: 16px;
            }
            .nearby-item      { grid-template-columns: auto 1fr; align-items: start; }
            .nearby-item-copy { display: grid; gap: 10px; }

            .nearby-marker {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 999px;
                background: var(--ui-ink);
                color: var(--ui-canvas);
                font-size: 12px;
                font-weight: 700;
                line-height: 1;
            }

            .nearby-estimate {
                display: inline-flex;
                align-items: center;
                width: fit-content;
                min-height: 36px;
                padding: 8px 12px;
                border-radius: 999px;
                background: var(--ui-ink);
                color: var(--ui-canvas);
                font-size: 13px;
                font-weight: 600;
                line-height: 1.2;
            }

            /* ── Empty state ───────────────────────── */
            .empty-state {
                background: var(--ui-soft);
                padding: 28px;
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }
            .empty-state h2 { margin: 0 0 10px; font-size: 28px; line-height: 1.2; }
            .empty-state p  { margin: 0; color: var(--ui-body); line-height: 1.7; }

            /* ── Footer ────────────────────────────── */
            .footer { background: var(--ui-ink); color: var(--ui-canvas); margin-top: 32px; }

            .footer-shell {
                display: grid;
                gap: 16px;
                padding-top: 32px;
                padding-bottom: 32px;
            }

            .footer-links { display: flex; flex-wrap: wrap; gap: 16px; }
            .footer-links a { color: var(--ui-canvas); font-size: 14px; }

            /* ── Focus ─────────────────────────────── */
            .nav-link:focus-visible,
            .button:focus-visible,
            .nav-login-link:focus-visible {
                outline: 2px solid var(--ui-ink);
                outline-offset: 2px;
            }

            /* ── Responsive ────────────────────────── */
            @media (max-width: 767px) {
                .headline { font-size: 32px; }

                .hero-band,
                .hero-card,
                .room-card-body,
                .detail-body,
                .feature-card-body,
                .gallery-card-body,
                .contact-band,
                .empty-state {
                    padding-left: 18px;
                    padding-right: 18px;
                }
            }

            @media (min-width: 768px) {
                .header-row {
                    display: grid;
                    grid-template-columns: auto minmax(0, 1fr) auto;
                    align-items: center;
                }

                .nav-row {
                    display: contents;
                }

                .nav-links {
                    gap: 18px 34px;
                }

                .button-row {
                    justify-content: flex-end;
                }

                .hero,
                .detail-grid { grid-template-columns: 1.15fr .85fr; }

                .room-grid,
                .gallery-grid,
                .facilities-grid,
                .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }

                .section-split {
                    flex-direction: row;
                    align-items: end;
                    justify-content: space-between;
                }

                .spec-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            }

            @media (min-width: 1024px) {
                .headline { font-size: 52px; }

                .room-grid,
                .gallery-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            }
        </style>

        @stack('styles')
    </head>
    <body>

        {{-- ── Site Header ──────────────────────────────────────────── --}}
        <header class="site-header">
            <div class="site-shell header-row">

                <a href="{{ route('home') }}" class="brand">{{ $profile['name'] }}</a>

                <div class="nav-row">
<<<<<<< HEAD
                    <nav class="nav-links" aria-label="Navigasi publik">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                        <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('rooms.*')) aria-current="page" @endif>Kamar</a>
                        <a href="{{ route('home') }}#fasilitas" class="nav-link">Fasilitas</a>
                        <a href="{{ route('home') }}#lokasi" class="nav-link">Lokasi</a>
                        <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>
=======
                    <nav class="nav-links" aria-label="Navigasi utama">
                        <a
                            href="{{ route('home') }}"
                            class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                            @if(request()->routeIs('home')) aria-current="page" @endif
                        >Home</a>

                        <a
                            href="{{ route('rooms.index') }}"
                            class="nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}"
                            @if(request()->routeIs('rooms.*')) aria-current="page" @endif
                        >Kamar</a>
>>>>>>> origin/ardhan
                    </nav>

                    <div class="button-row">
                        <a href="{{ route('login') }}" class="nav-login-link">Login</a>
                    </div>
                </div>

            </div>
        </header>

        {{-- ── Page Content ──────────────────────────────────────────── --}}
        @yield('content')

        {{-- ── Site Footer ──────────────────────────────────────────── --}}
        <footer class="footer">
            <div class="site-shell footer-shell">

                <div>
                    <div class="brand">{{ $profile['name'] }}</div>
                    <p class="footer-copy">{{ $profile['description'] }}</p>
                </div>

                <nav class="footer-links" aria-label="Navigasi footer">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('rooms.index') }}">Kamar</a>
<<<<<<< HEAD
                    <a href="{{ route('home') }}#fasilitas">Fasilitas</a>
                    <a href="{{ route('home') }}#lokasi">Lokasi</a>
                    <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                </div>
=======
                    <a
                        href="{{ $profile['whatsapp_url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >WhatsApp</a>
                </nav>
>>>>>>> origin/ardhan

                <p class="footer-copy">{{ $profile['address'] }}</p>

            </div>
        </footer>

    </body>
</html>