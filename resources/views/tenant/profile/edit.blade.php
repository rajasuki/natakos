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
    .input, .textarea { width:100%; border:1px solid var(--ui-border); background:#fff; color:var(--ui-ink); padding:10px 14px; border-radius:var(--radius-md); font-size:14px; transition:border-color .15s; font-family:inherit; }
    .input:focus, .textarea:focus { outline:none; border-color:var(--ui-accent); box-shadow:0 0 0 3px rgba(74,124,89,.12); }
    .textarea { resize:vertical; min-height:80px; }
    .field-error { color:#be123c; font-size:12px; font-weight:600; }
    .helper { font-size:12px; color:var(--ui-body); margin-top:4px; }
    .form-sep { height:1px; background:var(--ui-border); border:0; margin:24px 0; }
    .media-upload { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
    .media-preview { width:72px; height:72px; border-radius:50%; object-fit:cover; border:2px solid var(--ui-border); background:var(--ui-soft); flex-shrink:0; }
    .media-preview-placeholder { width:72px; height:72px; border-radius:50%; background:var(--ui-accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; border:2px solid var(--ui-accent); flex-shrink:0; }
    .bg-preview { width:160px; height:90px; border-radius:var(--radius-md); object-fit:cover; border:1px solid var(--ui-border); background:var(--ui-soft); flex-shrink:0; }
    .bg-preview-empty { width:160px; height:90px; border-radius:var(--radius-md); border:2px dashed var(--ui-border); display:flex; align-items:center; justify-content:center; font-size:12px; color:var(--ui-body); flex-shrink:0; }
    .checkbox-label { display:flex; align-items:center; gap:8px; font-size:14px; font-weight:500; cursor:pointer; }
    .checkbox-label input[type=checkbox] { width:18px; height:18px; accent-color:var(--ui-accent); }
</style>
@endpush

@section('content')
    <div class="form-wrap">
        <h1>Edit Profil</h1>
        <p>Perbarui foto, bio, latar profil, dan data akun Anda.</p>

        <div class="form-card">
            <form method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="field">
                    <label>Foto Profil</label>
                    <div class="media-upload">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="media-preview">
                        @else
                            <div class="media-preview-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <label for="avatar" class="button button-subtle" style="cursor:pointer;">Pilih Gambar</label>
                            <input id="avatar" name="avatar" type="file" accept="image/*" hidden>
                            <div class="helper">Format: JPG, JPEG, PNG, WEBP, GIF. Maks. 5MB.</div>
                            @error('avatar') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Latar Profil</label>
                    <div class="media-upload">
                        @if ($user->profile_bg)
                            <img src="{{ asset('storage/'.$user->profile_bg) }}" alt="Latar profil" class="bg-preview">
                        @else
                            <div class="bg-preview-empty">Belum ada latar</div>
                        @endif
                        <div>
                            <label for="profile_bg" class="button button-subtle" style="cursor:pointer;">Pilih Gambar</label>
                            <input id="profile_bg" name="profile_bg" type="file" accept="image/*" hidden>
                            <div class="helper">Gambar latar profil. GIF didukung dan akan berjalan otomatis. Maks. 5MB.</div>
                            @error('profile_bg') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

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

                <div class="field">
                    <label for="bio">Tentang Saya</label>
                    <textarea id="bio" name="bio" class="textarea" maxlength="1000" placeholder="Ceritakan tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <div class="field-error">{{ $message }}</div> @enderror
                    <div class="helper">Penghuni lain bisa melihat bio ini saat melihat profil Anda di obrolan.</div>
                </div>

                <div class="field">
                    <label class="checkbox-label">
                        <input type="hidden" name="show_room" value="0">
                        <input type="checkbox" name="show_room" value="1" @checked(old('show_room', $user->show_room ?? true))>
                        <span>Tampilkan kamar saya ke penghuni lain</span>
                    </label>
                    @error('show_room') <div class="field-error">{{ $message }}</div> @enderror
                    <div class="helper">Jika dimatikan, penghuni lain tidak akan melihat kamar Anda di profil. Admin tetap bisa melihatnya.</div>
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
