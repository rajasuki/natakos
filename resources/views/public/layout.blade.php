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
                 --ui-border:         #DCE7DD;
                 --ui-shadow:         rgba(28,43,34,.10) 0px 4px 16px 0px;
                 --ui-shadow-strong:  rgba(28,43,34,.16) 0px 4px 16px 0px;
                 --ui-accent:         #4A7C59;
                 --ui-glow-1:        #4A7C59;
                 --ui-glow-2:        #3D6A4A;
                 --ui-accent-hover:   #3D6A4A;
                 --ui-accent-soft:    #EEF5EF;
            }

            *, *::before, *::after { box-sizing: border-box; }

             body {
                 margin: 0;
                 min-height: 100vh;
                 background: rgba(251,248,243,.85);
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
                  z-index: 40;
                  border-bottom: 1px solid var(--ui-border);
                  background: rgba(251,248,243,.92);
                  backdrop-filter: blur(18px);
                  transition: transform .35s ease, background .35s ease;
              }

              .site-header.nav-hidden {
                  transform: translateY(-100%);
              }

             .header-row {
                 position: relative;
                 display: grid;
                 grid-template-columns: minmax(0, 1fr) auto;
                 align-items: center;
                 gap: 16px;
                 padding-top: 12px;
                 padding-bottom: 12px;
              }

              /* ── Brand ─────────────────────────────── */
              .brand {
                  font-size: 26px;
                  font-weight: 700;
                  line-height: 1;
              }

              .brand-link {
                 display: inline-flex;
                 align-items: center;
                 gap: 12px;
                 min-width: 0;
              }

              .brand-mark {
                 display: inline-flex;
                 align-items: center;
                 justify-content: center;
                 width: 44px;
                 height: 44px;
                 flex-shrink: 0;
                 border-radius: 14px;
                 background: var(--ui-accent);
                 color: #fff;
                 font-size: 18px;
                 font-weight: 700;
                 box-shadow: var(--ui-shadow);
              }

              .brand-text {
                 min-width: 0;
                 overflow: hidden;
                 text-overflow: ellipsis;
                 white-space: nowrap;
                 font-size: 22px;
                 font-weight: 700;
                 line-height: 1;
              }

              /* ── Nav ───────────────────────────────── */
              .nav-links {
                 display: flex;
                 flex-wrap: wrap;
                  align-items: center;
                  justify-content: center;
                 gap: 8px;
              }

              .desktop-nav {
                 display: none;
                 padding: 6px;
                 border: 1px solid var(--ui-border);
                 border-radius: 999px;
                 background: rgba(238,245,239,.92);
                 box-shadow: var(--ui-shadow);
              }

              .header-actions,
              .button-row,
              .chip-row,
              .room-card-chips,
              .detail-actions {
                  display: flex;
                  flex-wrap: wrap;
                  gap: 12px;
              }

              .header-actions {
                 align-items: center;
                 justify-content: flex-end;
              }

              .button-row {
                 justify-content: center;
              }

              .nav-link,
              .mobile-nav-link,
              .nav-auth-link,
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

              .nav-link,
              .mobile-nav-link {
                 min-height: 42px;
                 padding: 10px 16px;
                 color: var(--ui-body);
                 background: transparent;
                 transition: background-color .2s ease, color .2s ease, box-shadow .2s ease;
              }

              .nav-link:hover,
              .mobile-nav-link:hover {
                 background: rgba(255,255,255,.82);
                 color: var(--ui-ink);
              }

              .nav-link.is-active,
              .mobile-nav-link.is-active {
                 background: #fff;
                 color: var(--ui-ink);
                 box-shadow: var(--ui-shadow);
              }

              .nav-auth-link {
                  display: inline-flex;
                  align-items: center;
                  color: var(--ui-body);
                  font-weight: 600;
                  font-size: 14px;
                  transition: color .2s ease;
               }

              .nav-auth-link:hover {
                  color: var(--ui-ink);
               }

              .mobile-menu {
                 position: relative;
              }

              .mobile-menu summary {
                 list-style: none;
              }

              .mobile-menu summary::-webkit-details-marker {
                 display: none;
              }

              .mobile-menu-toggle {
                 display: inline-flex;
                 align-items: center;
                 justify-content: center;
                 width: 44px;
                 height: 44px;
                 border: 1px solid var(--ui-border);
                 border-radius: 14px;
                 background: rgba(255,255,255,.72);
                 color: var(--ui-ink);
                 cursor: pointer;
                 transition: background-color .2s ease, color .2s ease;
              }

              .mobile-menu[open] > .mobile-menu-toggle,
              .mobile-menu-toggle:hover {
                 background: var(--ui-soft);
              }

              .mobile-menu-panel {
                 display: none;
                 position: absolute;
                 right: 0;
                 top: calc(100% + 12px);
                 width: min(280px, calc(100vw - 48px));
                 padding: 12px;
                 border: 1px solid var(--ui-border);
                 border-radius: 24px;
                 background: rgba(251,248,243,.98);
                 box-shadow: var(--ui-shadow-strong);
              }

              .mobile-menu[open] .mobile-menu-panel {
                 display: block;
              }

              .mobile-nav-links {
                 display: grid;
                 gap: 6px;
              }

              .mobile-nav-link {
                 justify-content: flex-start;
                 width: 100%;
              }

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
             .footer { background: linear-gradient(135deg, #1C2B22 0%, #0f1f17 50%, #13211a 100%); color: var(--ui-canvas); margin-top: 32px; }

            .footer-shell {
                display: grid;
                gap: 16px;
                padding-top: 32px;
                padding-bottom: 32px;
            }

            .footer-links { display: flex; flex-wrap: wrap; gap: 16px; }
            .footer-links a { color: var(--ui-canvas); font-size: 14px; }

             /* ── Decorative glow ─────────────────────── */
             .site-glow {
                 position: fixed;
                 inset: 0;
                 z-index: -1;
                 pointer-events: none;
                 overflow: hidden;
             }

             .glow-blob {
                 position: absolute;
                 border-radius: 999px;
                 filter: blur(80px);
                 opacity: .35;
                 animation: glow-float 14s ease-in-out infinite alternate;
             }

             .glow-blob:nth-child(1) {
                 width: 400px; height: 400px;
                 top: -80px; left: -100px;
                 background: var(--ui-glow-1);
                 animation-duration: 16s;
             }

             .glow-blob:nth-child(2) {
                 width: 350px; height: 350px;
                 bottom: -60px; right: -80px;
                 background: var(--ui-glow-2);
                 animation-duration: 12s;
                 animation-delay: -4s;
             }

             @keyframes glow-float {
                 0%   { transform: translate(0, 0) scale(1); }
                 50%  { transform: translate(30px, -20px) scale(1.08); }
                 100% { transform: translate(-20px, 10px) scale(.92); }
             }

             /* ── Focus ─────────────────────────────── */
             .nav-link:focus-visible,
             .mobile-nav-link:focus-visible,
             .nav-auth-link:focus-visible,
             .mobile-menu-toggle:focus-visible,
             .button:focus-visible {
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
                 .header-row {
                     grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
                     padding-top: 18px;
                     padding-bottom: 18px;
                 }

                 .desktop-nav {
                     display: flex;
                 }

                 .mobile-menu {
                     display: none;
                 }

                 .headline { font-size: 52px; }

                 .room-grid,
                 .gallery-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
             }
        </style>

        @stack('styles')
    </head>
     <body>
     <div class="site-glow" aria-hidden="true">
         <div class="glow-blob"></div>
         <div class="glow-blob"></div>
     </div>

        {{-- ── Site Header ──────────────────────────────────────────── --}}
         <header class="site-header">
             <div class="site-shell header-row">

                <a href="{{ route('home') }}" class="brand-link">
                    <span class="brand-mark">{{ strtoupper(mb_substr($profile['name'], 0, 1)) }}</span>
                    <span class="brand-text">{{ $profile['name'] }}</span>
                </a>

                <nav class="nav-links desktop-nav" aria-label="Navigasi publik">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                    <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('rooms.*')) aria-current="page" @endif>Kamar</a>
                    <a href="{{ route('home') }}#fasilitas" class="nav-link">Fasilitas</a>
                    <a href="{{ route('home') }}#lokasi" class="nav-link">Lokasi</a>
                    <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>
                </nav>

                <div class="header-actions">
                     <a href="{{ route('login') }}" class="nav-auth-link">Sign</a>

                    <details class="mobile-menu">
                        <summary class="mobile-menu-toggle" aria-label="Buka navigasi">
                            <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </summary>

                        <div class="mobile-menu-panel">
                            <nav class="mobile-nav-links" aria-label="Navigasi publik seluler">
                                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                                <a href="{{ route('rooms.index') }}" class="mobile-nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('rooms.*')) aria-current="page" @endif>Kamar</a>
                                <a href="{{ route('home') }}#fasilitas" class="mobile-nav-link">Fasilitas</a>
                                <a href="{{ route('home') }}#lokasi" class="mobile-nav-link">Lokasi</a>
                                <a href="{{ route('home') }}#kontak" class="mobile-nav-link">Kontak</a>
                            </nav>
                        </div>
                    </details>
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
                     <a href="{{ route('home') }}#fasilitas">Fasilitas</a>
                     <a href="{{ route('home') }}#lokasi">Lokasi</a>
                     <a href="{{ route('home') }}#kontak">Kontak</a>
                     <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                 </nav>

                <p class="footer-copy">{{ $profile['address'] }}</p>

            </div>
        </footer>

     <script>
         let lastScroll = 0;
         const header = document.querySelector('.site-header');
         const threshold = 40;
         window.addEventListener('scroll', () => {
             const current = window.pageYOffset;
             if (Math.abs(current - lastScroll) < 8) return;
             if (current > threshold && current > lastScroll) {
                 header.classList.add('nav-hidden');
             } else {
                 header.classList.remove('nav-hidden');
             }
             lastScroll = current;
         }, { passive: true });
     </script>
    </body>
</html>
