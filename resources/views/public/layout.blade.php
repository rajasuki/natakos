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

            .site-shell {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding-left: 24px;
                padding-right: 24px;
            }

            .site-header {
                border-bottom: 1px solid #e2e2e2;
                background: #ffffff;
            }

            .header-row {
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
                padding: 12px 18px;
                background: #efefef;
            }

            .nav-link.is-active {
                background: #000000;
                color: #ffffff;
            }

            .button {
                border: 0;
                cursor: pointer;
                padding: 14px 18px;
            }

            .button-primary {
                background: #000000;
                color: #ffffff;
            }

            .button-secondary {
                background: #ffffff;
                color: #000000;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 8px 0px;
            }

            .button-subtle {
                background: #efefef;
                color: #000000;
            }

            .button-subtle:hover {
                background: #e2e2e2;
            }

            .page-section {
                padding-top: 32px;
                padding-bottom: 32px;
            }

            .hero {
                display: grid;
                gap: 20px;
                align-items: stretch;
            }

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
                background: #ffffff;
                padding: 32px;
                border: 1px solid #e2e2e2;
            }

            .hero-card,
            .feature-card,
            .room-card,
            .detail-card,
            .gallery-card {
                background: #ffffff;
                border: 1px solid #e2e2e2;
                overflow: hidden;
            }

            .hero-card {
                background: #efefef;
                padding: 24px;
            }

            .section-dark {
                background: #000000;
                color: #ffffff;
            }

            .contact-band {
                background: #000000;
                color: #ffffff;
                padding: 28px;
            }

            .eyebrow {
                margin: 0 0 12px;
                color: #5e5e5e;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            .section-dark .eyebrow,
            .contact-band .eyebrow {
                color: #afafaf;
            }

            .headline,
            .section-title,
            .room-title,
            .detail-title {
                margin: 0;
                font-weight: 700;
                line-height: 1.18;
            }

            .headline {
                font-size: 40px;
                max-width: 620px;
            }

            .section-title {
                font-size: 32px;
                margin-bottom: 10px;
            }

            .room-title,
            .detail-title {
                font-size: 24px;
            }

            .lead,
            .section-copy,
            .muted,
            .room-copy,
            .detail-copy,
            .footer-copy {
                color: #5e5e5e;
                line-height: 1.7;
            }

            .lead {
                margin: 16px 0 0;
                max-width: 560px;
                font-size: 16px;
            }

            .section-copy,
            .detail-copy,
            .room-copy,
            .footer-copy,
            .muted {
                font-size: 14px;
            }

            .hero-stats,
            .room-grid,
            .feature-grid,
            .detail-grid,
            .gallery-grid,
            .facilities-grid {
                display: grid;
                gap: 16px;
            }

            .hero-stat {
                display: grid;
                gap: 6px;
                padding: 16px;
                background: #ffffff;
                border-radius: 16px;
            }

            .hero-stat-value {
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
            }

            .hero-stat-label {
                color: #5e5e5e;
                font-size: 13px;
                line-height: 1.5;
            }

            .section-header {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-bottom: 20px;
            }

            .room-card-media,
            .detail-media,
            .gallery-image {
                width: 100%;
                background: #efefef;
                object-fit: cover;
                display: block;
            }

            .room-card-media,
            .detail-media {
                aspect-ratio: 4 / 3;
            }

            .gallery-image {
                aspect-ratio: 4 / 3;
            }

            .media-placeholder {
                display: grid;
                place-items: center;
                color: #5e5e5e;
                text-align: center;
                padding: 24px;
            }

            .room-card-body,
            .detail-body,
            .feature-card-body,
            .gallery-card-body {
                padding: 22px;
            }

            .status-badge,
            .chip {
                padding: 8px 12px;
                font-size: 12px;
            }

            .status-available {
                background: #000000;
                color: #ffffff;
            }

            .status-occupied {
                background: #efefef;
                color: #000000;
            }

            .status-maintenance {
                background: #d9d9d9;
                color: #000000;
            }

            .chip {
                background: #efefef;
                color: #000000;
            }

            .detail-list {
                display: grid;
                gap: 14px;
                margin: 20px 0;
            }

            .detail-item {
                display: grid;
                gap: 6px;
            }

            .detail-label {
                color: #5e5e5e;
                font-size: 13px;
            }

            .detail-value {
                font-size: 15px;
                line-height: 1.6;
                font-weight: 600;
            }

            .empty-state {
                background: #efefef;
                padding: 28px;
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

            .footer {
                background: #000000;
                color: #ffffff;
                margin-top: 32px;
            }

            .footer-shell {
                display: grid;
                gap: 16px;
                padding-top: 32px;
                padding-bottom: 32px;
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
            }

            .footer-links a {
                color: #ffffff;
                font-size: 14px;
            }

            @media (min-width: 768px) {
                .header-row {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }

                .nav-row {
                    flex-direction: row;
                    align-items: center;
                }

                .hero,
                .detail-grid {
                    grid-template-columns: 1.15fr 0.85fr;
                }

                .room-grid,
                .gallery-grid,
                .facilities-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .feature-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (min-width: 1024px) {
                .headline {
                    font-size: 52px;
                }

                .room-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }

                .gallery-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
        </style>

        @stack('styles')
    </head>
    <body>
        <header class="site-header">
            <div class="site-shell header-row">
                <a href="{{ route('home') }}" class="brand">{{ $profile['name'] }}</a>

                <div class="nav-row">
                    <nav class="nav-links">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                        <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}">Kamar</a>
                    </nav>

                    <div class="button-row">
                        <a href="{{ route('login') }}" class="button button-primary">Login</a>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')

        <footer class="footer">
            <div class="site-shell footer-shell">
                <div>
                    <div class="brand">{{ $profile['name'] }}</div>
                    <p class="footer-copy">{{ $profile['description'] }}</p>
                </div>

                <div class="footer-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('rooms.index') }}">Kamar</a>
                    <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noreferrer">WhatsApp</a>
                </div>

                <p class="footer-copy">{{ $profile['address'] }}</p>
            </div>
        </footer>
    </body>
</html>
