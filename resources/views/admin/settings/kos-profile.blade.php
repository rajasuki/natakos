@extends('admin.layout')

@section('title', 'Pengaturan Kos')
@section('eyebrow', 'Admin Settings')
@section('page_title', 'Pengaturan profil kos')
@section('page_description', 'Atur identitas utama NATAKOS yang dipakai di halaman publik, tombol WhatsApp, dan informasi kontak penghuni.')

@section('page_actions')
    <a href="{{ route('home') }}" class="button button-secondary">Lihat homepage publik</a>
@endsection

@section('content')
    @php
        $errorBag = isset($errors) ? $errors : null;
    @endphp

    <div class="content-stack">
        <section class="card form-card">
            <form method="POST" action="{{ route('admin.settings.kos-profile.update') }}" enctype="multipart/form-data" class="form-layout">
                @csrf
                @method('PUT')

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Informasi utama kos</h2>
                        <p class="form-section-copy">Ubah nama kos, deskripsi singkat, dan alamat yang akan tampil di halaman publik NATAKOS.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field field-full">
                            <label for="name">Nama kos</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $profile->name) }}" class="input" required>
                            @if ($errorBag?->has('name'))
                                <div class="field-error">{{ $errorBag->first('name') }}</div>
                            @endif
                        </div>

                        <div class="field field-full">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" class="textarea" placeholder="Tulis deskripsi singkat kos...">{{ old('description', $profile->description) }}</textarea>
                            @if ($errorBag?->has('description'))
                                <div class="field-error">{{ $errorBag->first('description') }}</div>
                            @endif
                        </div>

                        <div class="field field-full">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" class="textarea" placeholder="Tulis alamat kos...">{{ old('address', $profile->address) }}</textarea>
                            @if ($errorBag?->has('address'))
                                <div class="field-error">{{ $errorBag->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Kontak dan lokasi</h2>
                        <p class="form-section-copy">Nomor WhatsApp akan dinormalisasi otomatis ke format internasional Indonesia dan dipakai di halaman publik serta dashboard tenant.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field">
                            <label for="whatsapp_number">Nomor WhatsApp</label>
                            <input id="whatsapp_number" name="whatsapp_number" type="text" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}" class="input" required>
                            @if ($errorBag?->has('whatsapp_number'))
                                <div class="field-error">{{ $errorBag->first('whatsapp_number') }}</div>
                            @endif
                            <div class="helper">Contoh input: <code>085217430009</code>. Sistem akan menyimpannya menjadi <code>6285217430009</code>.</div>
                        </div>

                        <div class="field">
                            <label for="google_maps_url">Google Maps URL</label>
                            <input id="google_maps_url" name="google_maps_url" type="url" value="{{ old('google_maps_url', $profile->google_maps_url) }}" class="input" placeholder="https://maps.google.com/...">
                            @if ($errorBag?->has('google_maps_url'))
                                <div class="field-error">{{ $errorBag->first('google_maps_url') }}</div>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Identitas visual</h2>
                        <p class="form-section-copy">Logo kos bersifat opsional. Jika diunggah, file akan disimpan ke storage Laravel pada folder <code>kos</code>.</p>
                    </div>

                    <div class="grid">
                        <div class="field field-full">
                            <label for="logo">Logo</label>
                            <input id="logo" name="logo" type="file" accept="image/*" class="file-input">
                            @if ($errorBag?->has('logo'))
                                <div class="field-error">{{ $errorBag->first('logo') }}</div>
                            @endif

                            @if ($profile->logo)
                                <div class="preview-frame preview-frame-spaced">
                                    <div class="preview">
                                        <img src="{{ asset('storage/'.$profile->logo) }}" alt="Logo {{ $profile->name }}">
                                        <div class="preview-meta">Path saat ini: <strong>{{ $profile->logo }}</strong></div>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state-actions">
                                    <span class="muted">Belum ada logo yang diunggah.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">Simpan pengaturan kos</button>
                    <a href="{{ route('admin.dashboard') }}" class="button button-secondary">Kembali ke dashboard</a>
                </div>
            </form>
        </section>
    </div>
@endsection
