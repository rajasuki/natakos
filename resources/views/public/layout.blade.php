<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'IchiKOS')</title>

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

             html { scroll-behavior: smooth; }

             *, *::before, *::after { box-sizing: border-box; }

             #fasilitas,
             #lokasi,
             #kontak {
                 scroll-margin-top: 80px;
             }

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
                padding-left: 32px;
                padding-right: 32px;
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
                  font-weight: 400;
                  line-height: 1;
               }

              .brand-text strong {
                  font-weight: 700;
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
                  gap: 6px;
                  color: #fff;
                  font-weight: 600;
                  font-size: 14px;
                  transition: background-color .2s ease, color .2s ease, box-shadow .2s ease;
                  background: var(--ui-accent);
                  border: 0;
                  cursor: pointer;
                  padding: 10px 20px;
                  border-radius: 999px;
                  line-height: 1.2;
                  min-height: 42px;
               }

               .nav-auth-link:hover {
                  background: var(--ui-accent-hover);
                  color: #fff;
               }

              .nav-auth-link:hover {
                  color: var(--ui-ink);
               }

              .nav-greeting {
                  font-size: 14px;
                  font-weight: 600;
                  color: rgba(0,0,0,.45);
                  white-space: nowrap;
              }

              /* ── Dashboard dropdown ── */
              .nav-item {
                  position: relative;
              }

              .nav-chevron {
                  transition: transform .2s ease;
              }

              .nav-item.is-open .nav-chevron {
                  transform: rotate(180deg);
              }

              .nav-dropdown {
                  position: absolute;
                  top: calc(100% + 8px);
                  left: 0;
                  min-width: 170px;
                  background: #fff;
                  border: 1px solid var(--ui-border);
                  border-radius: 12px;
                  box-shadow: var(--ui-shadow-strong);
                  padding: 6px;
                  opacity: 0;
                  visibility: hidden;
                  transform: translateY(4px);
                  transition: all .2s ease;
                  z-index: 60;
              }

              .nav-item.is-open .nav-dropdown {
                  opacity: 1;
                  visibility: visible;
                  transform: translateY(0);
              }

              .dropdown-link {
                  display: flex;
                  align-items: center;
                  width: 100%;
                  padding: 9px 12px;
                  border: 0;
                  border-radius: 7px;
                  background: transparent;
                  color: #3A5A45;
                  font-size: 13.5px;
                  font-weight: 500;
                  cursor: pointer;
                  transition: all .15s ease;
                  text-align: left;
                  white-space: nowrap;
              }

              .dropdown-link:hover {
                  background: var(--ui-soft);
                  color: var(--ui-ink);
              }

              .dropdown-link.is-active {
                  background: var(--ui-soft);
                  color: var(--ui-ink);
                  font-weight: 600;
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
             .room-card-media { aspect-ratio: 16 / 9; }
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
             .footer { background: linear-gradient(135deg, #1C2B22 0%, #0f1f17 50%, #13211a 100%); color: var(--ui-canvas); }

            .footer-shell {
                display: grid;
                grid-template-columns: 1fr;
                gap: 32px;
                padding-top: 40px;
                padding-bottom: 0;
            }

            @media (min-width: 768px) {
                .footer-shell { grid-template-columns: 1.5fr 1fr 1.2fr; }
            }

            .footer-section h3 {
                font-size: 13px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin: 0 0 16px;
                color: rgba(255,255,255,.6);
            }

            .footer-section p,
            .footer-section .text-sm {
                font-size: 13px;
                line-height: 1.7;
                margin: 0 0 8px;
                color: rgba(255,255,255,.8);
            }

            .footer-section a {
                display: inline-block;
                font-size: 13px;
                color: rgba(255,255,255,.8);
                text-decoration: none;
                padding: 3px 0;
                transition: color .15s;
            }
            .footer-section a:hover { color: #fff; }

            .footer-links-col { display: flex; flex-direction: column; gap: 2px; }

            .footer-contact-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                font-size: 13px;
                line-height: 1.6;
                color: rgba(255,255,255,.8);
                margin-bottom: 10px;
            }
            .footer-contact-item svg {
                flex-shrink: 0;
                width: 18px;
                height: 18px;
                margin-top: 2px;
                opacity: .7;
            }

            .footer-contact-item a {
                color: rgba(255,255,255,.8);
                text-decoration: none;
                padding: 0;
                transition: color .15s;
            }
            .footer-contact-item a:hover { color: #fff; }

            .footer-bottom {
                border-top: 1px solid rgba(255,255,255,.1);
                margin-top: 32px;
                padding: 20px 0 32px;
                font-size: 12px;
                color: rgba(255,255,255,.5);
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 8px;
            }

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

             .glow-blob:nth-child(3) {
                 width: 300px; height: 300px;
                 top: 40%; left: -60px;
                 background: var(--ui-glow-2);
                 opacity: .25;
                 animation-duration: 14s;
                 animation-delay: -2s;
             }

             .glow-blob:nth-child(4) {
                 width: 250px; height: 250px;
                 bottom: 25%; right: -40px;
                 background: var(--ui-glow-1);
                 opacity: .2;
                 animation-duration: 18s;
                 animation-delay: -6s;
             }

             .glow-blob:nth-child(5) {
                 width: 350px; height: 350px;
                 bottom: -100px; left: 30%;
                 background: var(--ui-glow-2);
                 opacity: .18;
                 animation-duration: 15s;
                 animation-delay: -10s;
             }

             .cursor-glow {
                 position: fixed;
                 width: 280px;
                 height: 280px;
                 border-radius: 50%;
                 background: radial-gradient(circle, rgba(74,124,89,.3) 0%, transparent 65%);
                 pointer-events: none;
                 z-index: -1;
                 will-change: transform;
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

                 .room-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
                 .gallery-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
             }

             .facility-icon {
                 width: 32px;
                 height: 32px;
                 display: inline-flex;
                 align-items: center;
                 justify-content: center;
                 border-radius: 8px;
                 font-size: 18px;
                 background: var(--ui-soft);
                 border: 1px solid var(--ui-border);
                 color: var(--ui-body);
                 flex-shrink: 0;
             }

             .facility-icon-sm {
                 width: 22px;
                 height: 22px;
                 border-radius: 6px;
                 font-size: 14px;
             }
        </style>

        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
        @stack('styles')
    </head>
     <body>
     <div class="site-glow" aria-hidden="true">
         <div class="glow-blob"></div>
         <div class="glow-blob"></div>
         <div class="cursor-glow"></div>
     </div>

        {{-- ── Site Header ──────────────────────────────────────────── --}}
        @include('partials.navbar')

        {{-- ── Page Content ──────────────────────────────────────────── --}}
        @yield('content')

        {{-- ── Site Footer ──────────────────────────────────────────── --}}
        <footer class="footer">
            <div class="site-shell footer-shell">

                <div class="footer-section">
                    <h3>{{ $profile['name'] }}</h3>
                    <p>{{ $profile['description'] }}</p>
                    <p class="text-sm" style="margin-top:12px;">Pemilik: {{ $profile['owner_name'] }}</p>
                </div>

                <div class="footer-section">
                    <h3>Jelajahi</h3>
                    <nav class="footer-links-col" aria-label="Navigasi footer">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('rooms.index') }}">Kamar</a>
                        <a href="{{ route('home') }}#fasilitas">Fasilitas</a>
                        <a href="{{ route('home') }}#lokasi">Lokasi</a>
                        <a href="{{ route('home') }}#kontak">Kontak</a>
                    </nav>
                </div>

                <div class="footer-section">
                    <h3>Hubungi Kami</h3>

                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('/^62/', '0', $profile['whatsapp_number']) }}</a>
                    </div>

                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:{{ $profile['email'] }}">{{ $profile['email'] }}</a>
                    </div>

                    <div class="footer-contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $profile['address'] }}</span>
                    </div>

                    <div style="margin-top:12px;">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" style="font-size:13px;color:rgba(255,255,255,.8);text-decoration:underline;text-underline-offset:3px;">Laporkan Masalah</a>
                    </div>
                </div>

            </div>

            <div class="site-shell">
                <div class="footer-bottom">
                    <span>&copy; {{ date('Y') }} {{ $profile['name'] }}.</span>
                    <span>Hak cipta dilindungi undang-undang.</span>
                </div>
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

           document.addEventListener('click', function (event) {
               var toggle = event.target.closest('[data-nav-dropdown]');
               if (toggle) {
                   event.preventDefault();
                   var item = toggle.closest('.nav-item');
                   if (item) item.classList.toggle('is-open');
                   return;
               }
               if (window.innerWidth >= 1024) {
                   var inside = event.target.closest('.nav-item');
                   if (!inside) {
                       document.querySelectorAll('.nav-item.is-open').forEach(function (el) {
                           el.classList.remove('is-open');
                       });
                   }
               }
           });

           (function () {
               var glow = document.querySelector('.cursor-glow');
               if (!glow) return;
               var tx = -9999, ty = -9999, cx = -9999, cy = -9999;
               function tick() {
                   cx += (tx - cx) * 0.12;
                   cy += (ty - cy) * 0.12;
                   glow.style.transform = 'translate(' + (cx - 140) + 'px, ' + (cy - 140) + 'px)';
                   requestAnimationFrame(tick);
               }
               document.addEventListener('mousemove', function (e) {
                   tx = e.clientX; ty = e.clientY;
               });
               tick();
           })();
        </script>

    </body>
</html>
