<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login NATAKOS</title>

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
                max-width: 1120px;
                margin: 0 auto;
                display: grid;
                gap: 32px;
            }

            .eyebrow {
                margin: 0 0 16px;
                color: #5e5e5e;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
            }

            .title {
                margin: 0;
                max-width: 640px;
                font-size: 40px;
                line-height: 1.15;
            }

            .lead {
                margin: 20px 0 0;
                max-width: 520px;
                color: #5e5e5e;
                font-size: 16px;
                line-height: 1.7;
            }

            .card {
                background: #efefef;
                border-radius: 16px;
                padding: 28px;
            }

            .card-title {
                margin: 0 0 8px;
                font-size: 28px;
            }

            .card-copy {
                margin: 0 0 24px;
                color: #5e5e5e;
                font-size: 14px;
                line-height: 1.6;
            }

            .alert {
                margin-bottom: 16px;
                padding: 14px 16px;
                border: 1px solid #000000;
                border-radius: 16px;
                background: #ffffff;
                font-size: 14px;
            }

            .form-row {
                margin-bottom: 16px;
            }

            label {
                display: block;
                margin-bottom: 8px;
                font-size: 14px;
                font-weight: 600;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                border: 1px solid #cfcfcf;
                background: #ffffff;
                padding: 14px 16px;
                font: inherit;
            }

            input[type="email"]:focus,
            input[type="password"]:focus {
                outline: none;
                border-color: #000000;
            }

            .remember {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 0 0 20px;
                color: #3f3f3f;
                font-size: 14px;
            }

            .remember label {
                margin: 0;
                font-weight: 500;
            }

            .button {
                width: 100%;
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

            code {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            }

            @media (min-width: 1024px) {
                .page {
                    padding: 40px 32px;
                }

                .shell {
                    grid-template-columns: 1.1fr 0.9fr;
                    align-items: center;
                }

                .title {
                    font-size: 52px;
                }

                .card {
                    padding: 32px;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <div class="shell">
                <section>
                    <p class="eyebrow">NATAKOS</p>
                    <h1 class="title">
                        Login untuk mengakses dashboard admin atau penghuni.
                    </h1>
                    <p class="lead">
                        Gunakan akun yang sudah terdaftar di database `natakos`. Sistem akan mengarahkan Anda ke dashboard sesuai role.
                    </p>
                </section>

                <section class="card">
                    <div>
                        <h2 class="card-title">Masuk</h2>
                        <p class="card-copy">Masukkan email dan password akun NATAKOS.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-row">
                            <label for="email">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-row">
                            <label for="password">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                            >
                        </div>

                        <div class="remember">
                            <input id="remember" type="checkbox" name="remember" value="1">
                            <label for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="button">
                            Login
                        </button>
                    </form>
                </section>
            </div>
        </main>
    </body>
</html>
