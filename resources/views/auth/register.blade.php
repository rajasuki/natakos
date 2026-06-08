@extends('public.layout')

@section('title', 'Daftar | '.$profile['name'])

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
            max-width: 500px;
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

        .input.is-error {
            border-color: #fca5a5;
        }

        .input.is-error:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,.12);
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

        .confirm-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            margin-top: 4px;
            min-height: 1.2em;
            transition: color .3s ease;
        }

        .confirm-indicator svg {
            flex-shrink: 0;
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

        .login-row {
            text-align: center;
            color: var(--ui-body);
            font-size: 13px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--ui-border);
        }

        .login-row a {
            color: var(--ui-accent);
            font-weight: 700;
            transition: color .15s;
        }

        .login-row a:hover {
            color: var(--ui-accent-hover);
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .password-reqs {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 12px;
            font-size: 12px;
            color: var(--gray-400);
            margin-top: 8px;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height .25s ease, opacity .2s ease, margin .2s ease;
        }

        .password-reqs.show {
            max-height: 40px;
            opacity: 1;
        }

        .req-item {
            transition: color .2s ease;
        }

        .req-item.sep {
            color: var(--ui-border);
        }

        .req-item.met {
            color: #16a34a;
        }

        .field-error {
            font-size: 12px;
            color: #dc2626;
        }

        @media (max-width: 520px) {
            .field-row {
                grid-template-columns: 1fr;
            }
        }

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
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                        </div>
                        <h1 class="auth-title">Daftar Akun Baru</h1>
                        <p class="auth-subtitle">Isi data diri untuk mendaftar sebagai penghuni.</p>
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

                    <form method="POST" action="{{ route('register') }}" class="form-layout">
                        @csrf
                        @if ($roomSlug ?? false)
                            <input type="hidden" name="room_slug" value="{{ $roomSlug }}">
                        @endif

                        <div class="field-row">
                            <div class="field">
                                <label class="field-label" for="name">Nama</label>
                                <div class="input-wrap">
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="{{ old('name') }}"
                                        placeholder="Nama lengkap"
                                        required
                                        autofocus
                                        class="input {{ $errors->has('name') ? 'is-error' : '' }}"
                                    >
                                </div>
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="field">
                                <label class="field-label" for="phone">Telepon</label>
                                <div class="input-wrap">
                                    <input
                                        id="phone"
                                        name="phone"
                                        type="tel"
                                        value="{{ old('phone') }}"
                                        placeholder="08xxxxxxxxxx"
                                        required
                                        class="input {{ $errors->has('phone') ? 'is-error' : '' }}"
                                    >
                                </div>
                                @error('phone')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

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
                                    class="input {{ $errors->has('email') ? 'is-error' : '' }}"
                                >
                            </div>
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="password">Password</label>
                            <div class="input-wrap">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="input with-button {{ $errors->has('password') ? 'is-error' : '' }}"
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
                            <div class="password-reqs" id="passwordReqs">
                                <span class="req-item" data-req="length">8 karakter</span>
                                <span class="req-item sep">·</span>
                                <span class="req-item" data-req="upper">1 huruf kapital</span>
                                <span class="req-item sep">·</span>
                                <span class="req-item" data-req="number">1 angka</span>
                            </div>
                            @error('password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-wrap">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Ulangi password"
                                    required
                                    class="input with-button"
                                >
                                <button
                                    type="button"
                                    class="input-action"
                                    data-password-toggle
                                    data-target="password_confirmation"
                                    aria-label="Tampilkan password"
                                >
                                    <span class="material-symbols-outlined password-icon">visibility</span>
                                </button>
                            </div>
                            <div class="confirm-indicator" id="confirmIndicator"></div>
                        </div>

                        <button type="submit" class="submit-button">Daftar</button>
                    </form>

                    <div class="login-row">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
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

            const passwordInput = document.getElementById('password');
            const passwordReqs = document.getElementById('passwordReqs');
            const confirmInput = document.getElementById('password_confirmation');
            const confirmIndicator = document.getElementById('confirmIndicator');

            function updateReqs() {
                const val = passwordInput.value;
                document.querySelectorAll('.req-item[data-req]').forEach(function (item) {
                    var req = item.getAttribute('data-req');
                    var isMet = false;
                    if (req === 'length') isMet = val.length >= 8;
                    else if (req === 'upper') isMet = /[A-Z]/.test(val);
                    else if (req === 'number') isMet = /[0-9]/.test(val);
                    item.classList.toggle('met', isMet);
                });
            }

            function updateConfirm() {
                const pw = passwordInput.value;
                const val = confirmInput.value;
                if (val.length === 0) {
                    confirmIndicator.textContent = '';
                    confirmIndicator.style.color = 'transparent';
                    return;
                }
                if (val === pw) {
                    confirmIndicator.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> Password cocok';
                    confirmIndicator.style.color = '#16a34a';
                } else {
                    confirmIndicator.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg> Password tidak cocok';
                    confirmIndicator.style.color = '#dc2626';
                }
            }

            passwordInput.addEventListener('focus', function () {
                passwordReqs.classList.add('show');
                updateReqs();
            });

            passwordInput.addEventListener('blur', function () {
                passwordReqs.classList.remove('show');
            });

            passwordInput.addEventListener('input', function () {
                updateReqs();
                if (confirmInput.value.length > 0) updateConfirm();
            });

            confirmInput.addEventListener('input', updateConfirm);
        </script>
    </section>
@endsection
