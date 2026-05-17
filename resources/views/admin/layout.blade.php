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

            button,
            input,
            select,
            textarea {
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

            .page-header {
                display: flex;
                flex-direction: column;
                gap: 20px;
                margin-bottom: 24px;
            }

            .eyebrow {
                margin: 0 0 8px;
                color: #5e5e5e;
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
                color: #5e5e5e;
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

            .button-danger {
                background: #ffffff;
                color: #000000;
                border: 1px solid #d4d4d4;
            }

            .button-danger:hover {
                background: #efefef;
            }

            .flash,
            .card,
            .empty-state {
                border-radius: 16px;
            }

            .flash {
                margin-bottom: 20px;
                padding: 16px 18px;
                font-size: 14px;
                line-height: 1.6;
            }

            .flash-success {
                background: #efefef;
                color: #000000;
            }

            .flash-error {
                background: #000000;
                color: #ffffff;
            }

            .flash ul {
                margin: 0;
                padding-left: 18px;
            }

            .card {
                background: #ffffff;
                border: 1px solid #e2e2e2;
                overflow: hidden;
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
                border-bottom: 1px solid #e2e2e2;
                vertical-align: top;
                font-size: 14px;
            }

            th {
                background: #efefef;
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
                background: #efefef;
                display: block;
            }

            .thumb-placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px;
                color: #5e5e5e;
                font-size: 12px;
                line-height: 1.4;
                text-align: center;
            }

            .room-name {
                margin: 0 0 4px;
                font-size: 16px;
                font-weight: 600;
            }

            .room-slug,
            .muted {
                color: #5e5e5e;
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
                background: #efefef;
                color: #000000;
            }

            .badge-occupied {
                background: #000000;
                color: #ffffff;
            }

            .badge-maintenance {
                background: #d9d9d9;
                color: #000000;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .empty-state {
                padding: 32px;
                background: #efefef;
            }

            .empty-state h2 {
                margin: 0 0 10px;
                font-size: 28px;
                line-height: 1.2;
            }

            .empty-state p {
                margin: 0 0 20px;
                max-width: 640px;
                color: #5e5e5e;
                line-height: 1.7;
            }

            .form-card {
                padding: 24px;
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
                background: #f3f3f3;
                color: #000000;
                padding: 14px 16px;
            }

            .input:focus,
            .select:focus,
            .textarea:focus,
            .file-input:focus {
                outline: none;
                border-color: #000000;
                background: #ffffff;
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
                color: #5e5e5e;
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

            .preview img {
                width: 100%;
                max-width: 320px;
                border-radius: 16px;
                object-fit: cover;
                background: #efefef;
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 8px;
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
    </head>
    <body>
        <header class="navbar">
            <div class="navbar-shell">
                <div class="brand">NATAKOS</div>

                <div class="nav-row">
                    <nav class="nav-links">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'is-active' : '' }}">Kamar</a>
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
