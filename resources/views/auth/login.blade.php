@extends('public.layout')

@section('title', 'Login | '.$profile['name'])

@push('styles')
    <style>
        .auth-page {
            position: relative;
            overflow: hidden;
            padding: 40px 0 60px;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-page::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(74,124,89,.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(74,124,89,.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-shell {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            position: relative;
            width: 100%;
            max-width: 460px;
            padding: 48px 40px 40px;
            border: 1px solid rgba(229,231,235,.6);
            border-radius: 24px;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 40px rgba(0,0,0,.06), 0 1px 3px rgba(0,0,0,.04);
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--ui-accent), #6d9d7a, var(--ui-accent));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%,100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .auth-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--ui-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-brand-icon .material-symbols-outlined {
            font-size: 20px;
            font-variation-settings: 'FILL' 1;
        }

        .auth-brand-text {
            display: grid;
            gap: 2px;
        }

        .auth-brand-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--ui-ink);
            line-height: 1.2;
        }

        .auth-brand-tagline {
            font-size: 12px;
            color: var(--ui-body);
            line-height: 1.3;
        }

        .auth-header {
            display: grid;
            gap: 6px;
            margin-bottom: 28px;
        }

                    .auth-header-icon {
                        margin-bottom: 8px;
                    }
                    .auth-header-icon svg {
                        display: block;
                    }

        .auth-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            letter-spacing: -.02em;
            color: var(--ui-ink);
        }

        .auth-subtitle {
            margin: 0;
            color: var(--ui-body);
            font-size: 14px;
            line-height: 1.6;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 18px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .alert svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-layout {
            display: grid;
            gap: 18px;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        .field-label {
            color: var(--gray-700);
            font-size: 13px;
            font-weight: 600;
        }

        .input-wrap {
            position: relative;
        }

        .input {
            width: 100%;
            height: 50px;
            border: 1.5px solid var(--ui-border);
            border-radius: 14px;
            background: #fff;
            color: var(--ui-ink);
            font: inherit;
            font-size: 15px;
            padding: 0 16px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .input.with-button {
            padding-right: 50px;
        }

        .input::placeholder {
            color: var(--gray-400);
            opacity: 0.5;
        }

        .input:focus {
            outline: none;
            border-color: var(--ui-accent);
            box-shadow: 0 0 0 3px rgba(74,124,89,.1);
        }

        .input-action {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--gray-400);
            cursor: pointer;
            transition: color .2s ease, background .2s ease;
        }

        .input-action:hover {
            color: var(--gray-600);
            background: var(--gray-50);
        }

        .password-icon {
            font-size: 20px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--ui-body);
            font-size: 13px;
        }

        .remember-row input[type='checkbox'] {
            appearance: none;
            width: 18px;
            height: 18px;
            margin: 0;
            border: 1.5px solid var(--gray-300);
            border-radius: 5px;
            background: #fff;
            cursor: pointer;
            position: relative;
            transition: background-color .2s ease, border-color .2s ease;
        }

        .remember-row input[type='checkbox']:checked {
            background: var(--ui-accent);
            border-color: var(--ui-accent);
        }

        .remember-row input[type='checkbox']:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: 2px solid #ffffff;
            border-top: 0;
            border-left: 0;
            transform: rotate(45deg);
        }

        .remember-row label {
            cursor: pointer;
        }

        .submit-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 50px;
            padding: 14px 22px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--ui-accent), #3d6a4a);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(74,124,89,.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(74,124,89,.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .register-row {
            text-align: center;
            color: var(--ui-body);
            font-size: 13px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--ui-border);
        }

        .register-row a {
            color: var(--ui-accent);
            font-weight: 700;
            transition: color .15s;
        }

        .register-row a:hover {
            color: var(--ui-accent-hover);
        }

        /* ── responsive ── */
        @media (max-width: 767px) {
            .auth-page {
                padding-top: 0;
                padding-bottom: 0;
                min-height: 100dvh;
            }

            .auth-card {
                padding: 32px 24px 28px;
                border-radius: 20px;
                border-left: 0;
                border-right: 0;
                border-bottom: 0;
                max-width: 100%;
                box-shadow: none;
                background: #fff;
            }

            .auth-blob { display: none; }
        }
    </style>
@endpush

@section('content')
    <section class="auth-page">
        <div class="site-shell">
            <div class="auth-shell">
                <div class="auth-card">
                    <div class="auth-brand">
                        <div class="auth-brand-icon">
                            <span class="material-symbols-outlined">home</span>
                        </div>
                        <div class="auth-brand-text">
                            <div class="auth-brand-name">{{ $profile['name'] }}</div>
                            <div class="auth-brand-tagline">Sistem Manajemen Kos</div>
                        </div>
                    </div>

                    <div class="auth-header">
                        <div class="auth-header-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ui-accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h1 class="auth-title">Masuk</h1>
                        <p class="auth-subtitle">Masukkan email dan password untuk melanjutkan.</p>
                    </div>

                    @if (isset($errors) && $errors->any())
                        <div class="alert">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="8" cy="8" r="7"/>
                                <path d="M8 5v3M8 11v.5"/>
                            </svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="form-layout">
                        @csrf

                        <div class="field">
                            <label class="field-label" for="email">Email</label>
                            <div class="input-wrap">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                    autofocus
                                    class="input"
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label" for="password">Password</label>
                            <div class="input-wrap">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Masukkan password Anda"
                                    required
                                    class="input with-button"
                                >

                                <button
                                    type="button"
                                    class="input-action"
                                    data-password-toggle
                                    data-target="password"
                                    aria-label="Tampilkan password"
                                >
                                    <span class="material-symbols-outlined password-icon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="remember-row">
                            <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                            <label for="remember">Ingat saya di perangkat ini</label>
                        </div>

                        <button type="submit" class="submit-button">Masuk</button>
                    </form>

                    <div class="register-row">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('click', function (event) {
                const button = event.target.closest('[data-password-toggle]');
                if (!button) return;
                const input = document.getElementById(button.getAttribute('data-target'));
                if (!input) return;
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                const icon = button.querySelector('.password-icon');
                if (icon) icon.textContent = isHidden ? 'visibility_off' : 'visibility';
            });
        </script>
    </section>
@endsection
