@extends('tenant.layout')

@section('title', 'Edit Profil')

@push('styles')
<style>
    .page-header { display:none; }
    .form-wrap { max-width:560px; margin:0 auto; padding-top:24px; }
    .form-wrap h1 { margin:0 0 8px; font-size:22px; font-weight:700; color:var(--ui-ink); }
    .form-wrap p { margin:0 0 24px; color:var(--ui-body); font-size:14px; }
    .form-card { background:#fff; border:1px solid var(--ui-border); border-radius:var(--radius-lg); padding:24px; }
    .field { display:grid; gap:6px; margin-bottom:16px; }
    .field label { font-size:13px; font-weight:600; color:var(--gray-600); }
    .input { width:100%; border:1px solid var(--ui-border); background:#fff; color:var(--ui-ink); padding:10px 14px; border-radius:var(--radius-md); font-size:14px; transition:border-color .15s; }
    .input:focus { outline:none; border-color:var(--ui-accent); box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .field-error { color:#be123c; font-size:12px; font-weight:600; }
    .helper { font-size:12px; color:var(--ui-body); margin-top:4px; }
    .form-sep { height:1px; background:var(--ui-border); border:0; margin:24px 0; }
</style>
@endpush

@section('content')
    <div class="form-wrap">
        <h1>Edit Profil</h1>
        <p>Perbarui data diri dan password akun Anda.</p>

        <div class="form-card">
            <form method="POST" action="{{ route('tenant.profile.update') }}">
                @csrf @method('PUT')

                <div class="field">
                    <label for="name">Nama Lengkap <span class="muted">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="input" required>
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">Email <span class="muted">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="input" required>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="phone">Nomor Telepon</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="input" placeholder="08xxxxxxxxxx">
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <hr class="form-sep">

                <p style="font-size:13px;font-weight:600;color:var(--ui-body);margin:0 0 16px;">Ganti Password (kosongkan jika tidak ingin mengganti)</p>

                <div class="field">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" class="input" autocomplete="current-password">
                    @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="new_password">Password Baru</label>
                    <input id="new_password" name="new_password" type="password" class="input" minlength="8" autocomplete="new-password">
                    <div class="helper">Minimal 8 karakter.</div>
                    @error('new_password') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <a href="{{ route('tenant.dashboard') }}" class="button button-secondary">Kembali</a>
                    <button type="submit" class="button button-primary">Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
@endsection
