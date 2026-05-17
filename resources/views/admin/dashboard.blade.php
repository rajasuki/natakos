<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard Admin NATAKOS</title>

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

            .page {
                min-height: 100vh;
                padding: 40px 24px;
                display: flex;
                align-items: center;
            }

            .shell {
                width: 100%;
                max-width: 960px;
                margin: 0 auto;
            }

            .card {
                display: flex;
                flex-direction: column;
                gap: 24px;
                background: #efefef;
                border-radius: 16px;
                padding: 32px;
            }

            .eyebrow {
                margin: 0 0 8px;
                color: #5e5e5e;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            h1 {
                margin: 0 0 8px;
                font-size: 40px;
                line-height: 1.15;
            }

            .copy {
                margin: 0;
                color: #5e5e5e;
                font-size: 14px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 999px;
                background: #000000;
                color: #ffffff;
                padding: 14px 18px;
                font: inherit;
                font-weight: 600;
                cursor: pointer;
            }

            .button:hover {
                background: #282828;
            }

            @media (min-width: 640px) {
                .card {
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="shell">
                <div class="card">
                    <div>
                        <p class="eyebrow">Admin Area</p>
                        <h1>Dashboard Admin NATAKOS</h1>
                        <p class="copy">Login sebagai {{ auth()->user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button">
                            Logout
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
