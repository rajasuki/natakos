@extends('admin.layout')

@section('title', 'Pengaturan Profil')
@section('eyebrow', 'Admin Pengaturan')
@section('page_title', 'Profil Kos')
@section('page_description', 'Kelola informasi profil kos dan daftar fasilitas terdekat.')

@push('styles')
<style>
    .nearby-row {
        display: grid;
        grid-template-columns: 1fr 80px 100px 100px auto;
        gap: 10px;
        align-items: end;
        padding: 12px 14px;
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: var(--radius-md);
    }

    .nearby-row + .nearby-row {
        margin-top: 8px;
    }

    .nearby-row .field {
        gap: 4px;
    }

    .nearby-row .field label {
        font-size: 11px;
    }

    .nearby-row .input,
    .nearby-row .select {
        padding: 8px 10px;
        font-size: 13px;
        min-height: 36px;
    }

    .nearby-remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid var(--ui-danger-border);
        border-radius: var(--radius-md);
        background: #fff;
        color: #9f1239;
        cursor: pointer;
        transition: all .15s;
        flex-shrink: 0;
        margin-bottom: 0;
    }

    .nearby-remove:hover {
        background: var(--ui-danger);
    }

    @media (max-width: 767px) {
        .nearby-row {
            grid-template-columns: 1fr 1fr;
        }
        .nearby-row .field-full {
            grid-column: 1 / -1;
        }
    }
</style>
@endpush

@section('content')
    @php
        $profile = $profile ?? new \App\Models\KosProfile();
    @endphp

    <form method="POST" action="{{ route('admin.kos-profile.update') }}" class="content-stack" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── Informasi Dasar ── --}}
        <section class="card">
            <div class="card-head">
                <h2 class="card-title">Informasi Dasar</h2>
                <p class="card-copy">Nama kos, deskripsi, dan kontak pengelola.</p>
            </div>
            <div class="card-body">
                <div class="form-layout grid-two">
                    <div class="field">
                        <label for="name">Nama Kos</label>
                        <input id="name" name="name" type="text" class="input" value="{{ old('name', $profile->name) }}">
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="owner_name">Nama Pemilik</label>
                        <input id="owner_name" name="owner_name" type="text" class="input" value="{{ old('owner_name', $profile->owner_name) }}">
                        @error('owner_name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="whatsapp_number">Nomor WhatsApp</label>
                        <input id="whatsapp_number" name="whatsapp_number" type="text" class="input" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}" placeholder="628xxxxxx">
                        @error('whatsapp_number') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Contoh input: <code>085217430009</code>. Sistem akan menyimpannya menjadi <code>6285217430009</code>.</div>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" class="input" value="{{ old('email', $profile->email) }}">
                        @error('email') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field field-full">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="textarea" style="min-height:100px;">{{ old('description', $profile->description) }}</textarea>
                        @error('description') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field field-full">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" class="textarea" style="min-height:80px;">{{ old('address', $profile->address) }}</textarea>
                        @error('address') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Kontak dan Lokasi ── --}}
        <section class="card">
            <div class="card-head">
                <h2 class="card-title">Kontak dan Lokasi</h2>
                <p class="card-copy">Google Maps URL dan embed untuk peta lokasi di halaman publik.</p>
            </div>
            <div class="card-body">
                <div class="form-layout grid-two">
                    <div class="field">
                        <label for="google_maps_url">Google Maps URL</label>
                        <input id="google_maps_url" name="google_maps_url" type="url" class="input" value="{{ old('google_maps_url', $profile->google_maps_url) }}" placeholder="https://maps.google.com/...">
                        @error('google_maps_url') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Link ini dipakai untuk tombol <code>Buka di Google Maps</code> di halaman publik.</div>
                    </div>

                    <div class="field">
                        <label for="google_maps_embed_url">Google Maps Embed URL</label>
                        <input id="google_maps_embed_url" name="google_maps_embed_url" type="text" class="input" value="{{ old('google_maps_embed_url', $profile->google_maps_embed_url) }}" placeholder="https://www.google.com/maps/embed?pb=...">
                        @error('google_maps_embed_url') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Di Google Maps buka <code>Share</code> lalu <code>Embed a map</code>, kemudian salin nilai <code>src</code>-nya.</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Identitas Visual ── --}}
        <section class="card">
            <div class="card-head">
                <h2 class="card-title">Identitas Visual</h2>
                <p class="card-copy">Logo kos bersifat opsional. Jika diunggah, file akan disimpan ke storage.</p>
            </div>
            <div class="card-body">
                <div class="field">
                    <label for="logo">Logo</label>
                    <input id="logo" name="logo" type="file" accept="image/*" class="file-input">
                    @error('logo') <span class="field-error">{{ $message }}</span> @enderror

                    @if ($profile->logo)
                        <div class="preview-frame preview-frame-spaced" style="margin-top:12px;">
                            <div class="preview">
                                <img src="{{ asset('storage/'.$profile->logo) }}" alt="Logo {{ $profile->name }}">
                                <div class="preview-meta">Path saat ini: <strong>{{ $profile->logo }}</strong></div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state-actions" style="margin-top:12px;">
                            <span class="muted">Belum ada logo yang diunggah.</span>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ── Denda Keterlambatan ── --}}
        <section class="card">
            <div class="card-head">
                <h2 class="card-title">Denda Keterlambatan</h2>
                <p class="card-copy">Atur denda otomatis untuk pembayaran sewa yang lewat jatuh tempo. Kosongkan atau isi 0 untuk menonaktifkan.</p>
            </div>
            <div class="card-body">
                <div class="form-layout grid-two">
                    <div class="field">
                        <label for="late_fee_per_day">Denda per hari (Rp)</label>
                        <input id="late_fee_per_day" name="late_fee_per_day" type="text" inputmode="numeric" pattern="[0-9.]*" value="{{ old('late_fee_per_day', $profile->late_fee_per_day ?? 0) }}" class="input" data-format-number>
                        @error('late_fee_per_day') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Jumlah denda yang dikenakan setiap hari keterlambatan. Contoh: Rp1.000/hari.</div>
                    </div>
                    <div class="field">
                        <label for="max_late_fee">Maksimal denda (Rp) <span class="muted">(opsional)</span></label>
                        <input id="max_late_fee" name="max_late_fee" type="text" inputmode="numeric" pattern="[0-9.]*" value="{{ old('max_late_fee', $profile->max_late_fee) }}" class="input" data-format-number placeholder="Kosongkan jika tanpa batas">
                        @error('max_late_fee') <span class="field-error">{{ $message }}</span> @enderror
                        <div class="helper">Batas maksimal akumulasi denda. Misal: maksimal Rp50.000 meskipun sudah telat 60 hari.</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Fasilitas Terdekat ── --}}
        <section class="card">
            <div class="card-head">
                <h2 class="card-title">Fasilitas Terdekat</h2>
                <p class="card-copy">Daftar tempat atau fasilitas umum di sekitar kos.</p>
            </div>
            <div class="card-body">
                <div id="nearby-list">
                    @forelse ($nearbyPlaces as $i => $place)
                        <div class="nearby-row">
                            <div class="field field-full">
                                <label>Nama tempat</label>
                                <input name="nearby_name[]" type="text" class="input" value="{{ $place['name'] }}" placeholder="Masjid, pasar, dll.">
                            </div>
                            <div class="field">
                                <label>Jarak</label>
                                <input name="nearby_value[]" type="text" class="input" value="{{ $place['estimate_value'] }}" placeholder="5">
                            </div>
                            <div class="field">
                                <label>Satuan</label>
                                <select name="nearby_unit[]" class="select">
                                    <option value="">Pilih</option>
                                    @foreach ($estimateUnits as $val => $label)
                                        <option value="{{ $val }}" @selected($place['estimate_unit'] === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Moda</label>
                                <select name="nearby_mode[]" class="select">
                                    <option value="">Pilih</option>
                                    @foreach ($travelModes as $val => $label)
                                        <option value="{{ $val }}" @selected($place['travel_mode'] === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="nearby-remove" onclick="this.closest('.nearby-row').remove()">
                                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                            </button>
                        </div>
                    @empty
                        <div class="nearby-row">
                            <div class="field field-full">
                                <label>Nama tempat</label>
                                <input name="nearby_name[]" type="text" class="input" placeholder="Masjid, pasar, dll.">
                            </div>
                            <div class="field">
                                <label>Jarak</label>
                                <input name="nearby_value[]" type="text" class="input" placeholder="5">
                            </div>
                            <div class="field">
                                <label>Satuan</label>
                                <select name="nearby_unit[]" class="select">
                                    <option value="">Pilih</option>
                                    @foreach ($estimateUnits as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Moda</label>
                                <select name="nearby_mode[]" class="select">
                                    <option value="">Pilih</option>
                                    @foreach ($travelModes as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="nearby-remove" onclick="this.closest('.nearby-row').remove()">
                                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                            </button>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="button button-secondary button-sm" style="margin-top:12px;" onclick="addNearbyRow()">
                    <span class="material-symbols-outlined" style="font-size:16px;">add</span>
                    Tambah tempat
                </button>
            </div>
        </section>

        {{-- ── Actions ── --}}
        <div class="form-actions">
            <button type="submit" class="button button-primary">Simpan perubahan</button>
        </div>
    </form>

    <script>
        function addNearbyRow() {
            var list = document.getElementById('nearby-list');
            var template = document.createElement('div');
            template.className = 'nearby-row';
            template.innerHTML =
                '<div class="field field-full">' +
                    '<label>Nama tempat</label>' +
                    '<input name="nearby_name[]" type="text" class="input" placeholder="Masjid, pasar, dll.">' +
                '</div>' +
                '<div class="field">' +
                    '<label>Jarak</label>' +
                    '<input name="nearby_value[]" type="text" class="input" placeholder="5">' +
                '</div>' +
                '<div class="field">' +
                    '<label>Satuan</label>' +
                    '<select name="nearby_unit[]" class="select">' +
                        '<option value="">Pilih</option>' +
                        '@foreach ($estimateUnits as $val => $label)' +
                            '<option value="{{ $val }}">{{ $label }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</div>' +
                '<div class="field">' +
                    '<label>Moda</label>' +
                    '<select name="nearby_mode[]" class="select">' +
                        '<option value="">Pilih</option>' +
                        '@foreach ($travelModes as $val => $label)' +
                            '<option value="{{ $val }}">{{ $label }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</div>' +
                '<button type="button" class="nearby-remove" onclick="this.closest(\'.nearby-row\').remove()">' +
                    '<span class="material-symbols-outlined" style="font-size:18px;">close</span>' +
                '</button>';
            list.appendChild(template);
        }
    </script>
@endsection
