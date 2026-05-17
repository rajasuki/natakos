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
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: #ffffff;
                color: #000000;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            button {
                font: inherit;
            }

            .navbar {
                border-bottom: 1px solid #e2e2e2;
                background: #ffffff;
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
                gap: 16px;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .brand {
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
            }

            .nav-row {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .nav-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                padding: 12px 18px;
                background: #efefef;
                font-size: 14px;
                font-weight: 600;
            }

            .nav-link.is-active {
                background: #000000;
                color: #ffffff;
            }

            .page-shell {
                padding-top: 32px;
                padding-bottom: 40px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 999px;
                cursor: pointer;
                padding: 12px 18px;
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
                transition: background-color 0.2s ease, color 0.2s ease;
            }

            .button-primary {
                background: #000000;
                color: #ffffff;
            }

            .button-primary:hover {
                background: #282828;
            }

            .button-secondary {
                background: #efefef;
                color: #000000;
            }

            .button-secondary:hover {
                background: #e2e2e2;
            }

            .page-title {
                margin: 0;
                font-size: 40px;
                line-height: 1.15;
            }

            .page-copy {
                margin: 12px 0 0;
                max-width: 720px;
                color: #5e5e5e;
                font-size: 16px;
                line-height: 1.7;
            }

            .eyebrow {
                margin: 0 0 8px;
                color: #5e5e5e;
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
                background: #ffffff;
                border: 1px solid #e2e2e2;
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
                color: #5e5e5e;
                font-size: 14px;
                line-height: 1.6;
            }

            .detail-list {
                display: grid;
                gap: 14px;
                margin-top: 18px;
            }

            .detail-item {
                display: grid;
                gap: 6px;
            }

            .detail-label {
                color: #5e5e5e;
                font-size: 13px;
                line-height: 1.5;
            }

            .detail-value {
                font-size: 15px;
                line-height: 1.6;
                font-weight: 600;
            }

            .muted {
                color: #5e5e5e;
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
                background: #fef3c7;
                color: #78350f;
            }

            .alert-danger {
                background: #fee2e2;
                color: #991b1b;
            }

            .empty-state {
                padding: 28px;
                background: #efefef;
            }

            .empty-state h2 {
                margin: 0 0 10px;
                font-size: 28px;
                line-height: 1.2;
            }

            .empty-state p {
                margin: 0;
                color: #5e5e5e;
                line-height: 1.7;
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
