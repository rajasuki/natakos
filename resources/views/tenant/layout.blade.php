<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard Penghuni') - NATAKOS</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                font-family: 'DM Sans', system-ui, sans-serif;

                --green-50:  #f0fdf4;
                --green-100: #dcfce7;
                --green-200: #bbf7d0;
                --green-300: #86efac;
                --green-400: #4ade80;
                --green-500: #22c55e;
                --green-600: #16a34a;
                --green-700: #15803d;
                --green-800: #166534;
                --green-900: #14532d;

                --ui-ink:        #0f1f14;
                --ui-body:       #4b6a56;
                --ui-canvas:     #ffffff;
                --ui-soft:       #f0fdf4;
                --ui-softer:     #f7fef9;
                --ui-border:     #d1fae5;
                --ui-border-mid: #a7f3d0;
                --ui-accent:     #16a34a;
                --ui-accent-mid: #22c55e;
                --ui-accent-dim: #dcfce7;

                --ui-warning:        #fef9c3;
                --ui-warning-border: #fde047;
                --ui-danger:         #fef2f2;
                --ui-danger-border:  #fecaca;
                --ui-success:        #dcfce7;
                --ui-success-border: #86efac;

                --ui-shadow:      0 1px 3px rgba(22,163,74,0.06), 0 4px 16px rgba(22,163,74,0.04);
                --ui-shadow-soft: 0 1px 2px rgba(22,163,74,0.08), 0 2px 8px rgba(22,163,74,0.05);
                --ui-shadow-card: 0 2px 8px rgba(15,31,20,0.06), 0 8px 24px rgba(22,163,74,0.05);

                --radius-sm: 8px;
                --radius-md: 12px;
                --radius-lg: 16px;
                --radius-xl: 20px;
                --radius-pill: 999px;
            }

            *, *::before, *::after { box-sizing: border-box; }

            html { scroll-behavior: smooth; }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--green-50);
                background-image:
                    radial-gradient(ellipse 80% 50% at 50% -10%, rgba(34,197,94,0.08) 0%, transparent 60%);
                color: var(--ui-ink);
                line-height: 1.5;
            }

            a { color: inherit; text-decoration: none; }

            img { max-width: 100%; height: auto; }

            button { font: inherit; cursor: pointer; }

            /* ── NAVBAR ── */
            .navbar {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(255,255,255,0.88);
                border-bottom: 1px solid var(--ui-border);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }

            .navbar-shell,
            .page-shell {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding-left: 28px;
                padding-right: 28px;
            }

            .navbar-shell {
                display: flex;
                flex-direction: column;
                gap: 16px;
                padding-top: 16px;
                padding-bottom: 16px;
            }

            .brand {
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 22px;
                font-weight: 800;
                letter-spacing: -0.5px;
                color: var(--ui-accent);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .brand-dot {
                display: inline-block;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--ui-accent-mid);
            }

            .nav-row {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }

            .nav-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                border-radius: var(--radius-pill);
                padding: 10px 18px;
                min-height: 40px;
                background: transparent;
                font-size: 14px;
                font-weight: 600;
                color: var(--ui-body);
                border: 1px solid transparent;
                transition: all 0.18s ease;
            }

            .nav-link:hover {
                background: var(--ui-accent-dim);
                color: var(--ui-accent);
                border-color: var(--ui-border-mid);
            }

            .nav-link.is-active {
                background: var(--ui-accent);
                color: #ffffff;
                border-color: var(--ui-accent);
            }

            /* ── PAGE SHELL ── */
            .page-shell {
                padding-top: 40px;
                padding-bottom: 60px;
            }

            /* ── CONTENT STACK ── */
            .content-stack {
                display: grid;
                gap: 24px;
            }

            /* ── HERO CARD ── */
            .hero-card {
                display: grid;
                gap: 20px;
                padding: 28px 32px;
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                border-radius: var(--radius-xl);
                box-shadow: var(--ui-shadow-card);
                position: relative;
                overflow: hidden;
            }

            .hero-card::before {
                content: '';
                position: absolute;
                top: 0; right: 0;
                width: 300px; height: 300px;
                background: radial-gradient(circle at top right, rgba(34,197,94,0.08), transparent 70%);
                pointer-events: none;
            }

            .hero-copy {
                margin: 10px 0 0;
                color: var(--ui-body);
                font-size: 15px;
                line-height: 1.75;
            }

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
                border: 1px solid var(--ui-border-mid);
                font-size: 12px;
                font-weight: 600;
                color: var(--ui-accent);
                line-height: 1.2;
            }

            /* ── BUTTONS ── */
            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                border: 0;
                border-radius: var(--radius-pill);
                padding: 12px 22px;
                min-height: 44px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
                transition: all 0.18s ease;
                white-space: nowrap;
            }

            .button-primary {
                background: var(--ui-accent);
                color: #ffffff;
                box-shadow: 0 2px 8px rgba(22,163,74,0.25);
            }

            .button-primary:hover {
                background: var(--green-700);
                box-shadow: 0 4px 12px rgba(22,163,74,0.35);
                transform: translateY(-1px);
            }

            .button-primary:active {
                transform: translateY(0);
            }

            .button-secondary {
                background: var(--ui-canvas);
                color: var(--ui-accent);
                border: 1.5px solid var(--ui-border-mid);
            }

            .button-secondary:hover {
                background: var(--ui-soft);
                border-color: var(--ui-accent);
            }

            .button-subtle {
                background: var(--ui-soft);
                color: var(--ui-accent);
                border: 1px solid var(--ui-border);
            }

            .button-subtle:hover {
                background: var(--ui-accent-dim);
                border-color: var(--ui-border-mid);
            }

            /* ── TYPOGRAPHY ── */
            .page-title {
                margin: 0;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 38px;
                font-weight: 800;
                line-height: 1.15;
                letter-spacing: -0.5px;
                color: var(--ui-ink);
            }

            .page-copy {
                margin: 12px 0 0;
                max-width: 680px;
                color: var(--ui-body);
                font-size: 16px;
                line-height: 1.75;
            }

            .eyebrow {
                margin: 0 0 6px;
                color: var(--ui-accent);
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.15em;
                text-transform: uppercase;
            }

            /* ── CARDS ── */
            .card,
            .alert,
            .empty-state {
                border-radius: var(--radius-lg);
            }

            .card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }

            .card-head {
                display: flex;
                flex-direction: column;
                gap: 6px;
                padding: 22px 24px 0;
            }

            .card-head.has-divider {
                padding-bottom: 18px;
                border-bottom: 1px solid var(--ui-border);
            }

            .card-body {
                padding: 24px;
            }

            .card-title {
                margin: 0 0 8px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 20px;
                font-weight: 700;
                line-height: 1.25;
                letter-spacing: -0.2px;
            }

            .card-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 14px;
                line-height: 1.6;
            }

            /* ── DETAIL LIST ── */
            .detail-list {
                display: grid;
                gap: 16px;
                margin-top: 16px;
            }

            .detail-grid {
                display: grid;
                gap: 20px;
            }

            .detail-item {
                display: grid;
                gap: 4px;
                padding: 12px 14px;
                background: var(--ui-softer);
                border-radius: var(--radius-md);
                border: 1px solid var(--ui-border);
            }

            .detail-label {
                color: var(--ui-body);
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                line-height: 1.4;
            }

            .detail-value {
                font-size: 15px;
                line-height: 1.5;
                font-weight: 600;
                color: var(--ui-ink);
            }

            .muted {
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
                font-weight: 400;
            }

            /* ── BADGES ── */
            .badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: var(--radius-pill);
                padding: 5px 12px;
                font-size: 12px;
                font-weight: 700;
                white-space: nowrap;
                letter-spacing: 0.01em;
            }

            .badge-available {
                background: var(--ui-accent);
                color: #ffffff;
            }

            .badge-occupied {
                background: var(--ui-soft);
                color: var(--ui-accent);
                border: 1px solid var(--ui-border-mid);
            }

            .badge-safe,
            .badge-unpaid,
            .badge-no-end-date {
                background: #f1f5f9;
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            .badge-maintenance,
            .badge-inactive {
                background: #f1f5f9;
                color: #64748b;
                border: 1px solid #e2e8f0;
            }

            .badge-paid {
                background: var(--ui-success);
                color: var(--green-800);
                border: 1px solid var(--ui-success-border);
            }

            .badge-pending-verification,
            .badge-due-soon,
            .badge-ending-soon {
                background: var(--ui-warning);
                color: #854d0e;
                border: 1px solid var(--ui-warning-border);
            }

            .badge-due-today,
            .badge-ends-today {
                background: #fef3c7;
                color: #92400e;
                border: 1px solid #fcd34d;
            }

            .badge-overdue,
            .badge-rejected,
            .badge-ended {
                background: var(--ui-danger);
                color: #991b1b;
                border: 1px solid var(--ui-danger-border);
            }

            /* ── ALERTS ── */
            .alert {
                padding: 18px 22px;
                border: 1px solid transparent;
            }

            .alert-stack {
                display: grid;
                gap: 12px;
            }

            .alert h2 {
                margin: 0 0 5px;
                font-size: 16px;
                font-weight: 700;
                line-height: 1.3;
            }

            .alert p {
                margin: 0;
                font-size: 14px;
                line-height: 1.6;
            }

            .alert-warning {
                background: var(--ui-warning);
                color: #78350f;
                border-color: var(--ui-warning-border);
            }

            .alert-danger {
                background: var(--ui-danger);
                color: #991b1b;
                border-color: var(--ui-danger-border);
            }

            /* ── EMPTY STATE ── */
            .empty-state {
                padding: 40px 32px;
                background: var(--ui-canvas);
                border: 1.5px dashed var(--ui-border-mid);
                text-align: center;
            }

            .empty-state h2 {
                margin: 0 0 10px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 22px;
                font-weight: 700;
                line-height: 1.2;
            }

            .empty-state p {
                margin: 0;
                color: var(--ui-body);
                line-height: 1.7;
                max-width: 440px;
                margin-inline: auto;
            }

            .empty-state-actions,
            .card-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 20px;
            }

            .empty-state-actions {
                justify-content: center;
            }

            /* ── FOCUS ── */
            .nav-link:focus-visible,
            .button:focus-visible {
                outline: 2px solid var(--ui-accent);
                outline-offset: 2px;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 767px) {
                .navbar-shell, .page-shell {
                    padding-left: 18px;
                    padding-right: 18px;
                }

                .page-shell {
                    padding-top: 24px;
                    padding-bottom: 40px;
                }

                .page-title {
                    font-size: 28px;
                }

                .hero-card {
                    padding: 22px 20px;
                }

                .card-head,
                .card-body,
                .empty-state {
                    padding-left: 18px;
                    padding-right: 18px;
                }
            }

            @media (min-width: 768px) {
                .navbar-shell {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }

                .nav-row {
                    flex-direction: row;
                    align-items: center;
                }

                .detail-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }

                .card-span-2 {
                    grid-column: span 2;
                }
            }
        </style>

        @stack('styles')
    </head>
    <body>
        <header class="navbar">
            <div class="navbar-shell">
                <div class="brand">
                    <span class="brand-dot"></span>
                    NATAKOS
                </div>

                <div class="nav-row">
                    <nav class="nav-links">
                        <a href="{{ route('tenant.dashboard') }}"
                           class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'is-active' : '' }}"
                           @if(request()->routeIs('tenant.dashboard')) aria-current="page" @endif>
                            Dashboard
                        </a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button button-secondary">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="page-shell">
            @yield('content')
        </main>
    </body>
</html>