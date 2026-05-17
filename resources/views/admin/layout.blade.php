<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Admin') - NATAKOS</title>

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

            button,
            input,
            select,
            textarea {
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

            .page-header {
                display: flex;
                flex-direction: column;
                gap: 20px;
                margin-bottom: 28px;
            }

            .eyebrow {
                margin: 0 0 8px;
                color: var(--ui-body);
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
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

            .button,
            .button-inline {
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

            .button-danger {
                background: var(--ui-canvas);
                color: #991b1b;
                border: 1px solid #fecaca;
            }

            .button-danger:hover {
                background: #fef2f2;
            }

            .flash,
            .card,
            .empty-state {
                border-radius: 16px;
            }

            .content-stack {
                display: grid;
                gap: 24px;
            }

            .flash {
                margin-bottom: 20px;
                padding: 16px 18px;
                font-size: 14px;
                line-height: 1.6;
                border: 1px solid transparent;
                box-shadow: var(--ui-shadow-soft);
            }

            .flash-success {
                background: var(--ui-success);
                color: #065f46;
                border-color: #a7f3d0;
            }

            .flash-error {
                background: var(--ui-danger);
                color: #991b1b;
                border-color: #fecaca;
            }

            .flash ul {
                margin: 0;
                padding-left: 18px;
            }

            .card {
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
                overflow: hidden;
                box-shadow: var(--ui-shadow);
            }

            .card-body {
                padding: 22px;
            }

            .card-body-tight {
                padding: 18px 22px;
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

            .card-title {
                margin: 0;
                font-size: 24px;
                line-height: 1.25;
            }

            .card-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 14px;
                line-height: 1.6;
            }

            .surface-soft {
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
                border-radius: 16px;
            }

            .metric-grid {
                display: grid;
                gap: 16px;
                grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            }

            .metric-card {
                padding: 22px;
            }

            .metric-label {
                margin: 0 0 10px;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.5;
            }

            .metric-value {
                margin: 0;
                font-size: 34px;
                line-height: 1;
                font-weight: 700;
            }

            .metric-hint {
                margin: 10px 0 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            .alert-stack {
                display: grid;
                gap: 12px;
            }

            .alert-box {
                border-radius: 16px;
                padding: 18px 20px;
                border: 1px solid transparent;
                box-shadow: var(--ui-shadow-soft);
            }

            .alert-box h2,
            .alert-box h3 {
                margin: 0 0 6px;
                font-size: 18px;
                line-height: 1.3;
            }

            .alert-box p {
                margin: 0;
                font-size: 14px;
                line-height: 1.6;
            }

            .alert-box-warning {
                background: var(--ui-warning);
                color: #78350f;
                border-color: #fcd34d;
            }

            .alert-box-danger {
                background: var(--ui-danger);
                color: #991b1b;
                border-color: #fecaca;
            }

            .alert-box-success {
                background: var(--ui-success);
                color: #065f46;
                border-color: #a7f3d0;
            }

            .table-wrap {
                overflow-x: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                min-width: 880px;
            }

            th,
            td {
                padding: 16px 18px;
                text-align: left;
                border-bottom: 1px solid var(--ui-border);
                vertical-align: top;
                font-size: 14px;
                line-height: 1.6;
            }

            th {
                background: var(--ui-soft);
                font-weight: 600;
            }

            tbody tr:last-child td {
                border-bottom: 0;
            }

            .thumb {
                width: 88px;
                height: 66px;
                border-radius: 12px;
                object-fit: cover;
                background: var(--ui-soft);
                display: block;
                border: 1px solid var(--ui-border);
            }

            .thumb-placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px;
                color: var(--ui-body);
                font-size: 12px;
                line-height: 1.4;
                text-align: center;
            }

            .room-name {
                margin: 0 0 4px;
                font-size: 16px;
                font-weight: 600;
            }

            .meta-line {
                display: flex;
                flex-wrap: wrap;
                gap: 8px 12px;
                align-items: center;
            }

            .room-slug,
            .muted {
                color: var(--ui-body);
                font-size: 13px;
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

            .badge-maintenance {
                background: #d9d9d9;
                color: #000000;
            }

            .badge-room {
                background: #efefef;
                color: #000000;
            }

            .badge-public {
                background: #000000;
                color: #ffffff;
            }

            .badge-active {
                background: #000000;
                color: #ffffff;
            }

            .badge-inactive {
                background: #efefef;
                color: #000000;
            }

            .badge-moved-out {
                background: #d9d9d9;
                color: #000000;
            }

            .badge-unpaid,
            .badge-safe {
                background: #efefef;
                color: #000000;
            }

            .badge-pending-verification {
                background: #fff7ed;
                color: #9a3412;
            }

            .badge-paid {
                background: #d1fae5;
                color: #065f46;
            }

            .badge-rejected,
            .badge-overdue {
                background: #fee2e2;
                color: #991b1b;
            }

            .badge-due-soon {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-due-today {
                background: #fde68a;
                color: #78350f;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
            }

            .split-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
                justify-content: space-between;
            }

            .empty-state {
                padding: 32px;
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
                margin: 0 0 20px;
                max-width: 640px;
                color: var(--ui-body);
                line-height: 1.7;
            }

            .empty-state-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 20px;
            }

            .form-card {
                padding: 24px;
            }

            .form-layout {
                display: grid;
                gap: 22px;
            }

            .form-section {
                display: grid;
                gap: 18px;
                padding: 20px;
                border-radius: 16px;
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
            }

            .form-section-title {
                margin: 0;
                font-size: 18px;
                line-height: 1.3;
            }

            .form-section-copy {
                margin: 0;
                color: var(--ui-body);
                font-size: 13px;
                line-height: 1.6;
            }

            .grid {
                display: grid;
                gap: 18px;
            }

            .field {
                display: grid;
                gap: 8px;
            }

            .field-full {
                grid-column: 1 / -1;
            }

            .field label {
                font-size: 14px;
                font-weight: 600;
            }

            .input,
            .select,
            .textarea,
            .file-input {
                width: 100%;
                border: 1px solid #d4d4d4;
                background: var(--ui-softer);
                color: var(--ui-ink);
                padding: 14px 16px;
                border-radius: 8px;
            }

            .input:focus,
            .select:focus,
            .textarea:focus,
            .file-input:focus {
                outline: none;
                border-color: var(--ui-ink);
                background: var(--ui-canvas);
            }

            .textarea {
                min-height: 160px;
                resize: vertical;
            }

            .helper,
            .field-error,
            .preview-meta {
                font-size: 13px;
                line-height: 1.6;
            }

            .helper,
            .preview-meta {
                color: var(--ui-body);
            }

            .field-error {
                color: #000000;
                font-weight: 600;
            }

            .preview {
                display: grid;
                gap: 12px;
                margin-top: 6px;
            }

            .preview-frame {
                display: grid;
                gap: 12px;
                padding: 16px;
                border-radius: 16px;
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
            }

            .preview-frame-spaced {
                margin-top: 12px;
            }

            .preview img {
                width: 100%;
                max-width: 320px;
                border-radius: 16px;
                object-fit: cover;
                background: var(--ui-soft);
                border: 1px solid var(--ui-border);
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 8px;
            }

            .checkbox-sections {
                display: grid;
                gap: 16px;
            }

            .checkbox-group {
                display: grid;
                gap: 12px;
                padding: 20px;
                border-radius: 16px;
                background: var(--ui-soft);
            }

            .checkbox-group-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }

            .checkbox-grid {
                display: grid;
                gap: 10px;
            }

            .checkbox-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 12px 14px;
                border-radius: 12px;
                background: var(--ui-canvas);
                border: 1px solid var(--ui-border);
            }

            .checkbox-item input {
                margin-top: 2px;
            }

            .checkbox-copy {
                display: grid;
                gap: 4px;
            }

            .checkbox-copy strong {
                font-size: 14px;
                font-weight: 600;
            }

            .tag-list {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .tag {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                padding: 8px 12px;
                background: var(--ui-soft);
                font-size: 12px;
                font-weight: 600;
                line-height: 1.2;
            }

            .tag-muted {
                background: var(--ui-softer);
                color: var(--ui-body);
            }

            .muted-note {
                margin-top: 8px;
                line-height: 1.5;
            }

            .section-divider {
                border-top: 1px solid var(--ui-border);
                margin: 4px 0;
            }

            .nav-link:focus-visible,
            .button:focus-visible,
            .input:focus-visible,
            .select:focus-visible,
            .textarea:focus-visible,
            .file-input:focus-visible {
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

                .card-head,
                .card-body,
                .card-body-tight,
                .form-card,
                .empty-state {
                    padding-left: 18px;
                    padding-right: 18px;
                }

                .metric-card {
                    padding: 18px;
                }

                .responsive-table {
                    min-width: 0;
                }

                .responsive-table thead {
                    display: none;
                }

                .responsive-table,
                .responsive-table tbody,
                .responsive-table tr,
                .responsive-table td {
                    display: block;
                    width: 100%;
                }

                .responsive-table tbody tr {
                    padding: 18px;
                    border-bottom: 1px solid #e2e2e2;
                }

                .responsive-table tbody tr:last-child {
                    border-bottom: 0;
                }

                .responsive-table td {
                    padding: 0 0 14px;
                    border: 0;
                }

                .responsive-table td:last-child {
                    padding-bottom: 0;
                }

                .responsive-table td::before {
                    content: attr(data-label);
                    display: block;
                    margin-bottom: 6px;
                    color: #5e5e5e;
                    font-size: 12px;
                    font-weight: 600;
                    line-height: 1.4;
                }

                .split-actions {
                    align-items: stretch;
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

                .page-header {
                    flex-direction: row;
                    align-items: flex-end;
                    justify-content: space-between;
                }

                .grid-two {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
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
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Dashboard</a>
                        <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.rooms.*')) aria-current="page" @endif>Kamar</a>
                        <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.facilities.*')) aria-current="page" @endif>Fasilitas</a>
                        <a href="{{ route('admin.tenants.index') }}" class="nav-link {{ request()->routeIs('admin.tenants.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.tenants.*')) aria-current="page" @endif>Penghuni</a>
                        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.payments.*')) aria-current="page" @endif>Pembayaran</a>
                        <a href="{{ route('admin.settings.kos-profile.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.settings.*')) aria-current="page" @endif>Pengaturan Kos</a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button button-primary">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="page-shell">
            <section class="page-header">
                <div>
                    <p class="eyebrow">@yield('eyebrow', 'Admin')</p>
                    <h1 class="page-title">@yield('page_title')</h1>

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
        </main>
    </body>
</html>
