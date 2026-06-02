<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard Penghuni') - IchiKOS</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

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

                --gray-50:  #FBF8F3;
                --gray-100: #EEF5EF;
                --gray-200: #DCE7DD;
                --gray-300: #C8D8C9;
                --gray-400: #7E9C85;
                --gray-500: #5B7060;
                --gray-600: #3A5A45;
                --gray-700: #2D4735;
                --gray-800: #1F3226;
                --gray-900: #1C2B22;

                --ui-warning:        #fffbeb;
                --ui-warning-border: #fcd34d;
                --ui-danger:         #fff1f2;
                --ui-danger-border:  #fda4af;
                --ui-success:        #f0fdf4;
                --ui-success-border: #86efac;

                --radius-sm:   8px;
                --radius-md:   12px;
                --radius-lg:   14px;
                --radius-xl:   20px;
                --radius-pill: 999px;
            }

            *, *::before, *::after { box-sizing: border-box; }
            html { scroll-behavior: smooth; }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--gray-50);
                color: var(--ui-ink);
                line-height: 1.5;
                font-size: 14px;
            }

            a { color: inherit; text-decoration: none; }
            img { max-width: 100%; height: auto; }
            button, input, select, textarea { font: inherit; }

            /* ── NAVBAR (sama dengan admin/public) ── */
            .site-header {
                position: sticky;
                top: 0;
                z-index: 40;
                border-bottom: 1px solid var(--ui-border);
                background: rgba(251,248,243,.92);
                backdrop-filter: blur(18px);
                transition: transform .35s ease, background .35s ease;
            }

            .site-shell {
                width: 100%;
                padding-left: 32px;
                padding-right: 32px;
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

            .nav-link,
            .mobile-nav-link,
            .nav-auth-link {
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

            .header-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
                justify-content: flex-end;
            }

            .nav-greeting {
                font-size: 14px;
                font-weight: 600;
                color: rgba(28,43,34,.45);
                white-space: nowrap;
            }

            .nav-logout-form {
                display: inline;
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
                border-radius: var(--radius-md);
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
                color: var(--gray-600);
                font-size: 13.5px;
                font-weight: 500;
                cursor: pointer;
                transition: all .15s ease;
                text-align: left;
                white-space: nowrap;
            }

            .dropdown-link:hover {
                background: var(--gray-100);
                color: var(--ui-ink);
            }

            .dropdown-link.is-active {
                background: var(--gray-100);
                color: var(--ui-ink);
                font-weight: 600;
            }

            /* ── Mobile menu ── */
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

            .mobile-nav-divider {
                height: 1px;
                background: var(--ui-border);
                margin: 8px 0;
            }

            .mobile-nav-label {
                font-size: 10px;
                font-weight: 600;
                letter-spacing: .1em;
                text-transform: uppercase;
                color: var(--ui-body);
                padding: 4px 10px;
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
            }

            /* ── APP LAYOUT ── */
            .app-layout {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            .main-wrap {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .page-shell {
                padding: 28px 32px 48px;
                flex: 1;
                width: 100%;
            }

            /* ── PAGE HEADER ── */
            .page-header {
                display: flex;
                flex-direction: column;
                gap: 20px;
                margin-bottom: 28px;
            }

            .eyebrow {
                margin: 0 0 5px;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--ui-body);
            }

            .page-title {
                margin: 0;
                font-family: 'Inter', sans-serif;
                font-size: 26px;
                font-weight: 700;
                line-height: 1.2;
                letter-spacing: -0.3px;
                color: var(--ui-ink);
            }

            .page-copy {
                margin: 8px 0 0;
                max-width: 680px;
                color: var(--ui-body);
                font-size: 14px;
                line-height: 1.7;
            }

            /* ── FLASH MESSAGES ── */
            .flash {
                margin-bottom: 20px;
                padding: 14px 18px;
                font-size: 14px;
                line-height: 1.6;
                border: 1px solid transparent;
                border-radius: var(--radius-md);
            }

            .flash-success {
                background: var(--ui-success);
                color: #166534;
                border-color: var(--ui-success-border);
            }

            .flash-error {
                background: var(--ui-danger);
                color: #9f1239;
                border-color: var(--ui-danger-border);
            }

            .flash ul { margin: 0; padding-left: 18px; }

            /* ── CONTENT STACK ── */
            .content-stack { display: grid; gap: 22px; }

            /* ── HERO ── */
            .hero-card {
                display: grid;
                gap: 20px;
                padding: 28px 32px;
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-xl);
                box-shadow: var(--ui-shadow);
                position: relative;
                overflow: hidden;
            }

            .hero-copy {
                margin: 10px 0 0;
                color: var(--ui-body);
                font-size: 15px;
                line-height: 1.75;
            }

            /* ── CARDS ── */
            .card {
                background: #fff;
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-lg);
                overflow: hidden;
            }

            .card-head {
                display: flex;
                flex-direction: column;
                gap: 4px;
                padding: 20px 22px 0;
            }

            .card-head.has-divider {
                padding-bottom: 16px;
                border-bottom: 1px solid var(--ui-border);
            }

            .card-body { padding: 20px 22px; }
            .card-body-tight { padding: 14px 22px; }

            .card-title {
                margin: 0;
                font-family: 'Inter', sans-serif;
                font-size: 16px;
                font-weight: 700;
                color: var(--ui-ink);
                letter-spacing: -0.1px;
            }

            .card-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            /* ── BADGES ── */
            .badge {
                display: inline-flex;
                align-items: center;
                border-radius: var(--radius-pill);
                padding: 4px 10px;
                font-size: 11.5px;
                font-weight: 600;
                white-space: nowrap;
                letter-spacing: 0.01em;
            }

            .badge-available,
            .badge-active,
            .badge-paid       { background: #dcfce7; color: #15803d; }
            .badge-occupied   { background: var(--gray-100); color: var(--gray-600); }
            .badge-maintenance { background: #fef9c3; color: #854d0e; }
            .badge-inactive,
            .badge-moved-out,
            .badge-unpaid,
            .badge-safe,
            .badge-room,
            .badge-no-end-date { background: var(--gray-100); color: var(--gray-600); }
            .badge-public     { background: var(--gray-100); color: var(--gray-600); }

            .badge-pending-verification {
                background: #fff7ed;
                color: #9a3412;
            }

            .badge-rejected,
            .badge-overdue    { background: #ffe4e6; color: #9f1239; }
            .badge-due-soon   { background: #fef9c3; color: #854d0e; }
            .badge-due-today  { background: #ffedd5; color: #9a3412; }
            .badge-ending-soon { background: #fef9c3; color: #854d0e; }
            .badge-ends-today { background: #ffedd5; color: #9a3412; }
            .badge-ended      { background: #ffe4e6; color: #9f1239; }

            /* ── BUTTONS ── */
            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                border: 0;
                border-radius: var(--radius-pill);
                cursor: pointer;
                padding: 9px 18px;
                min-height: 38px;
                font-size: 13px;
                font-weight: 600;
                line-height: 1.2;
                transition: all 0.15s ease;
                white-space: nowrap;
            }

            .button-primary {
                background: var(--ui-accent);
                color: #ffffff;
            }

            .button-primary:hover {
                background: var(--ui-accent-hover);
                transform: translateY(-1px);
            }

            .button-secondary {
                background: #fff;
                color: var(--ui-ink);
                border: 1px solid var(--ui-border);
            }

            .button-secondary:hover {
                background: var(--gray-50);
                border-color: var(--gray-300);
            }

            .button-subtle {
                background: var(--gray-100);
                color: var(--gray-600);
                border: 1px solid var(--ui-border);
            }

            .button-subtle:hover {
                background: var(--gray-200);
            }

            .button-danger {
                background: #fff;
                color: #9f1239;
                border: 1px solid var(--ui-danger-border);
            }

            .button-danger:hover { background: var(--ui-danger); }

            .button-sm {
                padding: 7px 14px;
                min-height: 34px;
                font-size: 12px;
            }

            /* ── FORM ELEMENTS ── */
            .surface-soft {
                background: var(--gray-50);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-lg);
            }

            .form-card { padding: 24px; }

            .form-layout { display: grid; gap: 20px; }

            .form-section {
                display: grid;
                gap: 16px;
                padding: 20px;
                border-radius: var(--radius-lg);
                background: var(--gray-50);
                border: 1px solid var(--ui-border);
            }

            .form-section-title {
                margin: 0;
                font-family: 'Inter', sans-serif;
                font-size: 15px;
                font-weight: 700;
            }

            .form-section-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            .grid { display: grid; gap: 16px; }

            .field { display: grid; gap: 6px; }
            .field-full { grid-column: 1 / -1; }

            .field label {
                font-size: 13px;
                font-weight: 600;
                color: var(--gray-600);
            }

            .input, .select, .textarea, .file-input {
                width: 100%;
                border: 1px solid var(--ui-border);
                background: #fff;
                color: var(--ui-ink);
                padding: 10px 14px;
                border-radius: var(--radius-md);
                font-size: 14px;
                transition: border-color 0.15s, box-shadow 0.15s;
            }

            .input:focus, .select:focus, .textarea:focus, .file-input:focus {
                outline: none;
                border-color: var(--ui-accent);
                box-shadow: 0 0 0 3px rgba(74,124,89,.12);
            }

            .textarea { min-height: 140px; resize: vertical; }

            .helper, .field-error, .preview-meta { font-size: 12px; line-height: 1.6; }

            .helper, .preview-meta { color: var(--ui-body); }

            .field-error { color: #be123c; font-weight: 600; }

            .preview { display: grid; gap: 12px; margin-top: 6px; }

            .preview-frame {
                display: grid;
                gap: 10px;
                padding: 14px;
                border-radius: var(--radius-lg);
                background: #fff;
                border: 1px solid var(--ui-border);
            }

            .preview img {
                width: 100%;
                max-width: 280px;
                border-radius: 10px;
                object-fit: cover;
                background: var(--gray-100);
                border: 1px solid var(--ui-border);
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 4px;
            }

            /* ── ALERTS ── */
            .alert-stack { display: grid; gap: 10px; }

            .alert {
                border-radius: var(--radius-md);
                padding: 14px 18px;
                border: 1px solid transparent;
            }

            .alert h2 {
                margin: 0 0 3px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.3;
            }

            .alert p {
                margin: 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .alert-warning {
                background: var(--ui-warning);
                color: #92400e;
                border-color: var(--ui-warning-border);
            }

            .alert-danger {
                background: var(--ui-danger);
                color: #9f1239;
                border-color: var(--ui-danger-border);
            }

            .alert-success {
                background: var(--ui-success);
                color: #166534;
                border-color: var(--ui-success-border);
            }

            /* ── EMPTY STATE ── */
            .empty-state {
                padding: 36px 28px;
                background: var(--gray-50);
                border: 1.5px dashed var(--gray-300);
                border-radius: var(--radius-lg);
                text-align: center;
            }

            .empty-state h2 {
                margin: 0 0 8px;
                font-family: 'Inter', sans-serif;
                font-size: 20px;
                font-weight: 700;
            }

            .empty-state p {
                margin: 0 auto;
                max-width: 560px;
                color: var(--ui-body);
                line-height: 1.7;
                font-size: 14px;
            }

            .empty-state-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 18px;
                justify-content: center;
            }

            /* ── DETAIL LIST ── */
            .detail-list { display: grid; gap: 14px; margin: 20px 0; }

            .detail-item  { display: grid; gap: 6px; }
            .detail-label { color: var(--ui-body); font-size: 13px; }
            .detail-value { font-size: 15px; line-height: 1.6; font-weight: 600; }

            .muted { color: var(--ui-body); font-size: 13px; }
            .muted-note { margin-top: 6px; line-height: 1.5; }

            /* ── ACTIONS ── */
            .actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

            .card-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 20px;
            }

            .split-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
                justify-content: space-between;
            }

            /* ── MISC ── */
            .hero-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .hero-meta-pill {
                display: inline-flex;
                align-items: center;
                padding: 6px 14px;
                border-radius: var(--radius-pill);
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
                font-size: 12px;
                font-weight: 600;
                color: var(--ui-accent);
                line-height: 1.2;
            }

            .tag-list { display: flex; flex-wrap: wrap; gap: 6px; }

            .tag {
                display: inline-flex;
                align-items: center;
                border-radius: var(--radius-pill);
                padding: 5px 10px;
                background: var(--gray-100);
                font-size: 12px;
                font-weight: 600;
                line-height: 1.2;
                color: var(--gray-600);
            }

            .tag-muted { color: var(--ui-body); }

            .section-divider { border-top: 1px solid var(--ui-border); margin: 4px 0; }

            /* ── FOCUS ── */
            .button:focus-visible,
            .input:focus-visible,
            .select:focus-visible,
            .textarea:focus-visible {
                outline: 2px solid var(--ui-accent);
                outline-offset: 2px;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 767px) {
                .page-shell {
                    padding: 20px 18px 40px;
                }
            }

            @media (max-width: 520px) {
                .site-shell {
                    padding-left: 14px;
                    padding-right: 14px;
                }
            }

            @media (min-width: 768px) {
                .page-header {
                    flex-direction: row;
                    align-items: flex-end;
                    justify-content: space-between;
                }

                .grid-two {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
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
            }
        </style>

        @stack('styles')
    </head>
    <body>
        <div class="app-layout">

            {{-- ── NAVBAR ── --}}
            @include('partials.navbar')

            {{-- ── MAIN ── --}}
            <main class="main-wrap">
                <div class="page-shell">
                    <section class="page-header">
                        <div>
                            <p class="eyebrow">@yield('eyebrow', 'Penghuni')</p>
                            @hasSection('page_title')
                                <h1 class="page-title">@yield('page_title')</h1>
                            @endif

                            @hasSection('page_description')
                                <p class="page-copy">@yield('page_description')</p>
                            @endif
                        </div>

                        @hasSection('page_actions')
                            <div class="actions">
                                @yield('page_actions')
                            </div>
                        @endif
                    </section>

                    @if (session('success'))
                        <div class="flash flash-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="flash flash-error">{{ session('error') }}</div>
                    @endif

                    @if (isset($errors) && $errors->any())
                        <div class="flash flash-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('click', function (event) {
                var dropdownToggle = event.target.closest('[data-nav-dropdown]');
                if (dropdownToggle) {
                    event.preventDefault();
                    var navItem = dropdownToggle.closest('.nav-item');
                    if (navItem) {
                        navItem.classList.toggle('is-open');
                    }
                    return;
                }

                if (window.innerWidth >= 1024) {
                    var isDropdownClick = event.target.closest('.nav-item');
                    if (!isDropdownClick) {
                        document.querySelectorAll('.nav-item.is-open').forEach(function (item) {
                            item.classList.remove('is-open');
                        });
                    }
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>
