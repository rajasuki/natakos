<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Dashboard Penghuni') - NATAKOS</title>

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
                --ui-warning: #fef3c7;
                --ui-warning-strong: #fde68a;
                --ui-danger: #fee2e2;
                --ui-success: #d1fae5;
                --ui-shadow: rgba(0, 0, 0, 0.04) 0px 4px 16px 0px;
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

            img {
                max-width: 100%;
                height: auto;
            }

            button {
                font: inherit;
            }

            .navbar {
                position: sticky;
                top: 0;
                z-index: 30;
                border-bottom: 1px solid var(--ui-border);
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(8px);
            }

            .navbar-shell,
            .page-shell {
                width: 100%;
                max-width: 1180px;
                margin: 0 auto;
                padding-left: 24px;
                padding-right: 24px;
            }

            .navbar-shell {
                display: flex;
                flex-direction: column;
                gap: 18px;
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .brand {
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
            }

            .nav-row {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .nav-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                padding: 12px 18px;
                min-height: 44px;
                background: var(--ui-soft);
                font-size: 14px;
                font-weight: 600;
                transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            }

            .nav-link:hover {
                background: var(--ui-border);
            }

            .nav-link.is-active {
                background: var(--ui-ink);
                color: var(--ui-canvas);
            }

            .page-shell {
                padding-top: 40px;
                padding-bottom: 48px;
            }

            .content-stack {
                display: grid;
                gap: 24px;
            }

            .hero-card {
                display: grid;
                gap: 20px;
                padding: 26px;
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
                border-radius: 16px;
                box-shadow: var(--ui-shadow);
            }

            .hero-copy {
                margin: 12px 0 0;
                color: var(--ui-body);
                font-size: 15px;
                line-height: 1.7;
            }

            .hero-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .hero-meta-pill {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px 12px;
                border-radius: 999px;
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                font-size: 12px;
                font-weight: 600;
                line-height: 1.2;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 999px;
                cursor: pointer;
                padding: 12px 18px;
                min-height: 44px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
                transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
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

            .button-subtle {
                background: var(--ui-soft);
                color: var(--ui-ink);
            }

            .button-subtle:hover {
                background: var(--ui-border);
            }

            .page-title {
                margin: 0;
                font-size: 40px;
                line-height: 1.15;
            }

            .page-copy {
                margin: 12px 0 0;
                max-width: 720px;
                color: var(--ui-body);
                font-size: 16px;
                line-height: 1.7;
            }

            .eyebrow {
                margin: 0 0 8px;
                color: var(--ui-body);
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            .card,
            .alert,
            .empty-state {
                border-radius: 16px;
            }

            .card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }

            .card-head {
                display: flex;
                flex-direction: column;
                gap: 8px;
                padding: 22px 22px 0;
            }

            .card-head.has-divider {
                padding-bottom: 18px;
                border-bottom: 1px solid var(--ui-border);
            }

            .card-body {
                padding: 22px;
            }

            .card-title {
                margin: 0 0 12px;
                font-size: 24px;
                line-height: 1.25;
            }

            .card-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 14px;
                line-height: 1.6;
            }

            .detail-list {
                display: grid;
                gap: 14px;
                margin-top: 18px;
            }

            .detail-grid {
                display: grid;
                gap: 20px;
            }

            .detail-item {
                display: grid;
                gap: 6px;
            }

            .detail-label {
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.5;
            }

            .detail-value {
                font-size: 15px;
                line-height: 1.6;
                font-weight: 600;
            }

            .muted {
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                padding: 8px 12px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-available {
                background: #000000;
                color: #ffffff;
            }

            .badge-occupied {
                background: #efefef;
                color: #000000;
            }

            .badge-safe,
            .badge-unpaid,
            .badge-no-end-date {
                background: #efefef;
                color: #000000;
            }

            .badge-maintenance,
            .badge-inactive {
                background: #d9d9d9;
                color: #000000;
            }

            .badge-paid {
                background: #d1fae5;
                color: #065f46;
            }

            .badge-pending-verification,
            .badge-due-soon,
            .badge-ending-soon {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-due-today,
            .badge-ends-today {
                background: #fde68a;
                color: #78350f;
            }

            .badge-overdue,
            .badge-rejected,
            .badge-ended {
                background: #fee2e2;
                color: #991b1b;
            }

            .alert {
                padding: 18px 20px;
                border: 1px solid transparent;
                box-shadow: var(--ui-shadow-soft);
            }

            .alert-stack {
                display: grid;
                gap: 12px;
            }

            .alert h2 {
                margin: 0 0 6px;
                font-size: 18px;
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
                border-color: #fcd34d;
            }

            .alert-danger {
                background: var(--ui-danger);
                color: #991b1b;
                border-color: #fecaca;
            }

            .empty-state {
                padding: 28px;
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
                box-shadow: var(--ui-shadow);
            }

            .empty-state h2 {
                margin: 0 0 10px;
                font-size: 28px;
                line-height: 1.2;
            }

            .empty-state p {
                margin: 0;
                color: var(--ui-body);
                line-height: 1.7;
            }

            .empty-state-actions,
            .card-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 20px;
            }

            .section-copy-compact {
                margin-bottom: 12px;
            }

            .nav-link:focus-visible,
            .button:focus-visible {
                outline: 2px solid var(--ui-ink);
                outline-offset: 2px;
            }

            @media (max-width: 767px) {
                .page-shell {
                    padding-top: 28px;
                    padding-bottom: 36px;
                }

                .page-title {
                    font-size: 32px;
                }

                .hero-card,
                .card-body,
                .card-head,
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
                    justify-content: space-between;
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
                <div class="brand">NATAKOS</div>

                <div class="nav-row">
                    <nav class="nav-links">
                        <a href="{{ route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('tenant.dashboard')) aria-current="page" @endif>Dashboard</a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button button-primary">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="page-shell">
            @yield('content')
        </main>
    </body>
</html>
