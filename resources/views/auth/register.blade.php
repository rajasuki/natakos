@extends('public.layout')

@section('title', 'Sign Up | '.$profile['name'])

@push('styles')
    <style>
        .auth-page {
            position: relative;
            overflow: hidden;
            padding: 40px 0 60px;
        }

        .auth-shell {
            position: relative;
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            position: relative;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .auth-card-content {
            position: relative;
            display: grid;
            gap: 24px;
        }

        .auth-header {
            display: grid;
            gap: 6px;
            text-align: left;
        }

        .auth-title {
            margin: 0;
            font-size: clamp(24px, 3.5vw, 32px);
            line-height: 1.15;
            letter-spacing: -.03em;
            color: #111827;
        }

        .auth-subtitle {
            margin: 0;
            color: #6b7280;
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
        }

        .alert svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-layout {
            display: grid;
            gap: 20px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field-label {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
        }

        .input-wrap {
            position: relative;
        }

        .input {
            width: 100%;
            height: 52px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            background: #ffffff;
            color: #111827;
            font: inherit;
            font-size: 15px;
            padding: 0 16px;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        .input.with-button {
            padding-right: 52px;
        }

        .input::placeholder {
            color: #9ca3af;
        }

        .input:focus {
            outline: none;
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17,24,39,.06);
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
            color: #9ca3af;
            cursor: pointer;
            transition: color .2s ease;
        }

        .input-action:hover {
            color: #111827;
        }

        .password-icon[hidden] {
            display: none;
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
            width: 100%;
            min-height: 52px;
            padding: 16px 22px;
            border: 0;
            border-radius: 14px;
            background: #111827;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(17,24,39,.12);
            transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        .submit-button:hover {
            background: #1f2937;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(17,24,39,.16);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .login-row {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .login-row a {
            color: #111827;
            font-weight: 700;
        }

        .login-row a:hover {
            color: #374151;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 520px) {
            .field-row {
                grid-template-columns: 1fr;
            }
        }

        .password-reqs {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 12px;
            font-size: 12px;
            color: #9ca3af;
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
            color: #d1d5db;
        }

        .req-item.met {
            color: #16a34a;
        }

        .req-item.met-all {
            color: #16a34a;
        }

        .field-error {
            font-size: 12px;
            color: #dc2626;
        }

        @media (max-width: 767px) {
            .auth-page {
                padding-top: 24px;
                padding-bottom: 40px;
            }

            .auth-shell {
                min-height: auto;
            }

            .auth-card {
                padding: 24px;
                border-radius: 16px;
            }

            .auth-card-content {
                gap: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="auth-page">
        <div class="site-shell">
            <div class="auth-shell">
                <div class="auth-card">
                    <div class="auth-card-content">
                        <div class="auth-header">
                            <h1 class="auth-title">Sign Up</h1>
                            <p class="auth-subtitle">
                                Create your account to get started!
                            </p>
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

                            <div class="field-row">
                                <div class="field">
                                    <label class="field-label" for="name">Name</label>
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
                                    <label class="field-label" for="phone">Phone</label>
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
                                        placeholder="Min. 8 characters"
                                        required
                                        class="input with-button {{ $errors->has('password') ? 'is-error' : '' }}"
                                    >
                                    <button
                                        type="button"
                                        class="input-action"
                                        data-password-toggle
                                        data-target="password"
                                        aria-label="Tampilkan password"
                                        aria-pressed="false"
                                    >
                                        <svg data-icon="hidden" class="password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M12 5C7.27273 5 3.25616 7.74026 1.5 12C3.25616 16.2597 7.27273 19 12 19C16.7273 19 20.7438 16.2597 22.5 12C20.7438 7.74026 16.7273 5 12 5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <svg data-icon="shown" class="password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" hidden>
                                            <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M12 5C7.27273 5 3.25616 7.74026 1.5 12C3.25616 16.2597 7.27273 19 12 19C16.7273 19 20.7438 16.2597 22.5 12C20.7438 7.74026 16.7273 5 12 5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
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
                                <label class="field-label" for="password_confirmation">Confirm Password</label>
                                <div class="input-wrap">
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        placeholder="Repeat your password"
                                        required
                                        class="input with-button"
                                    >
                                    <button
                                        type="button"
                                        class="input-action"
                                        data-password-toggle
                                        data-target="password_confirmation"
                                        aria-label="Tampilkan password"
                                        aria-pressed="false"
                                    >
                                        <svg data-icon="hidden" class="password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M12 5C7.27273 5 3.25616 7.74026 1.5 12C3.25616 16.2597 7.27273 19 12 19C16.7273 19 20.7438 16.2597 22.5 12C20.7438 7.74026 16.7273 5 12 5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <svg data-icon="shown" class="password-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" hidden>
                                            <path d="M3 3L21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M12 5C7.27273 5 3.25616 7.74026 1.5 12C3.25616 16.2597 7.27273 19 12 19C16.7273 19 20.7438 16.2597 22.5 12C20.7438 7.74026 16.7273 5 12 5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="confirm-indicator" id="confirmIndicator"></div>
                            </div>

                            <button type="submit" class="submit-button">Create Account</button>
                        </form>

                        <div class="login-row">
                            Already have an account?
                            <a href="{{ route('login') }}">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('click', function (event) {
                const button = event.target.closest('[data-password-toggle]');

                if (!button) {
                    return;
                }

                const input = document.getElementById(button.getAttribute('data-target'));

                if (!input) {
                    return;
                }

                const shouldShow = input.type === 'password';
                input.type = shouldShow ? 'text' : 'password';

                button.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
                button.setAttribute('aria-label', shouldShow ? 'Sembunyikan password' : 'Tampilkan password');

                const hiddenIcon = button.querySelector('[data-icon="hidden"]');
                const shownIcon = button.querySelector('[data-icon="shown"]');

                if (hiddenIcon && shownIcon) {
                    hiddenIcon.hidden = shouldShow;
                    shownIcon.hidden = !shouldShow;
                }
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
                    confirmIndicator.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> Passwords match';
                    confirmIndicator.style.color = '#16a34a';
                } else {
                    confirmIndicator.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg> Passwords do not match';
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