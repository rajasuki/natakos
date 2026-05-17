@extends('admin.layout')

@section('title', 'Pengaturan Kos')
@section('eyebrow', 'Admin Settings')
@section('page_title', 'Pengaturan profil kos')
@section('page_description', 'Atur identitas utama NATAKOS yang dipakai di halaman publik, tombol WhatsApp, dan informasi kontak penghuni.')

@push('styles')
    <style>
        .nearby-editor {
            display: grid;
            gap: 12px;
        }

        .nearby-editor-list {
            display: grid;
            gap: 12px;
        }

        .nearby-editor-row {
            display: grid;
            gap: 12px;
            padding: 16px;
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            background: var(--ui-softer);
        }

        .nearby-editor-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (min-width: 768px) {
            .nearby-editor-row {
                grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.72fr) minmax(0, 0.92fr) auto;
                align-items: end;
            }
        }
    </style>
@endpush

@section('page_actions')
    <a href="{{ route('home') }}" class="button button-secondary">Lihat homepage publik</a>
@endsection

@section('content')
    @php
        $errorBag = isset($errors) ? $errors : null;
        $nearbyPlacesData = is_array(old('nearby_places')) ? old('nearby_places') : $nearbyPlaces;
        $nearbyPlacesData = collect($nearbyPlacesData)
            ->map(fn ($place): array => [
                'name' => trim((string) data_get($place, 'name', '')),
                'estimate_value' => trim((string) data_get($place, 'estimate_value', '')),
                'estimate_unit' => trim((string) data_get($place, 'estimate_unit', '')),
                'travel_mode' => trim((string) data_get($place, 'travel_mode', '')),
            ])
            ->whenEmpty(fn ($collection) => $collection->push([
                'name' => '',
                'estimate_value' => '',
                'estimate_unit' => '',
                'travel_mode' => '',
            ]))
            ->values();
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
                            <div class="helper">Link ini dipakai untuk tombol <code>Buka di Google Maps</code> di halaman publik.</div>
                        </div>

                        <div class="field">
                            <label for="google_maps_embed_url">Google Maps Embed URL</label>
                            <input id="google_maps_embed_url" name="google_maps_embed_url" type="url" value="{{ old('google_maps_embed_url', $profile->google_maps_embed_url) }}" class="input" placeholder="https://www.google.com/maps/embed?...">
                            @if ($errorBag?->has('google_maps_embed_url'))
                                <div class="field-error">{{ $errorBag->first('google_maps_embed_url') }}</div>
                            @endif
                            <div class="helper">Di Google Maps buka <code>Share</code> lalu <code>Embed a map</code>, kemudian salin nilai <code>src</code>-nya.</div>
                        </div>

                        <div class="field field-full">
                            <label>Tempat sekitar kos</label>

                            <div class="nearby-editor">
                                <div class="nearby-editor-list" data-nearby-editor-list data-next-index="{{ $nearbyPlacesData->count() }}">
                                    @foreach ($nearbyPlacesData as $index => $place)
                                        <div class="nearby-editor-row" data-nearby-row>
                                            <div class="field">
                                                <label for="nearby_places_{{ $index }}_name">Nama tempat</label>
                                                <input id="nearby_places_{{ $index }}_name" name="nearby_places[{{ $index }}][name]" type="text" value="{{ $place['name'] }}" class="input" placeholder="Kampus ABC">
                                            </div>

                                            <div class="field">
                                                <label for="nearby_places_{{ $index }}_estimate">Estimasi</label>
                                                <input id="nearby_places_{{ $index }}_estimate" name="nearby_places[{{ $index }}][estimate_value]" type="text" value="{{ $place['estimate_value'] }}" class="input" placeholder="5 atau 300">
                                            </div>

                                            <div class="field">
                                                <label for="nearby_places_{{ $index }}_unit">Satuan dan mode</label>
                                                <div class="nearby-editor-actions">
                                                    <select id="nearby_places_{{ $index }}_unit" name="nearby_places[{{ $index }}][estimate_unit]" class="select">
                                                        <option value="">Pilih satuan</option>
                                                        @foreach ($estimateUnitOptions as $value => $label)
                                                            <option value="{{ $value }}" @selected($place['estimate_unit'] === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="nearby_places[{{ $index }}][travel_mode]" class="select">
                                                        <option value="">Tanpa mode</option>
                                                        @foreach ($travelModeOptions as $value => $label)
                                                            <option value="{{ $value }}" @selected($place['travel_mode'] === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="nearby-editor-actions">
                                                <button type="button" class="button button-danger" data-nearby-remove>Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="nearby-editor-actions">
                                    <button type="button" class="button button-secondary" data-nearby-add>Tambah tempat sekitar</button>
                                </div>
                            </div>

                            @if ($errorBag?->has('nearby_places'))
                                <div class="field-error">{{ $errorBag->first('nearby_places') }}</div>
                            @endif

                            <div class="helper">Setiap baris punya nama tempat, nilai estimasi, satuan, dan mode perjalanan. Contoh: <code>Kampus ABC</code>, <code>5</code>, <code>Menit</code>, <code>Jalan kaki</code>.</div>
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

@push('scripts')
    <template id="nearby-place-template">
        <div class="nearby-editor-row" data-nearby-row>
            <div class="field">
                <label for="nearby_places___INDEX___name">Nama tempat</label>
                <input id="nearby_places___INDEX___name" name="nearby_places[__INDEX__][name]" type="text" value="" class="input" placeholder="Kampus ABC">
            </div>

            <div class="field">
                <label for="nearby_places___INDEX___estimate">Estimasi</label>
                <input id="nearby_places___INDEX___estimate" name="nearby_places[__INDEX__][estimate_value]" type="text" value="" class="input" placeholder="5 atau 300">
            </div>

            <div class="field">
                <label for="nearby_places___INDEX___unit">Satuan dan mode</label>
                <div class="nearby-editor-actions">
                    <select id="nearby_places___INDEX___unit" name="nearby_places[__INDEX__][estimate_unit]" class="select">
                        <option value="">Pilih satuan</option>
                        @foreach ($estimateUnitOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="nearby_places[__INDEX__][travel_mode]" class="select">
                        <option value="">Tanpa mode</option>
                        @foreach ($travelModeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="nearby-editor-actions">
                <button type="button" class="button button-danger" data-nearby-remove>Hapus</button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.querySelector('[data-nearby-editor-list]');
            const addButton = document.querySelector('[data-nearby-add]');
            const template = document.getElementById('nearby-place-template');

            if (!list || !addButton || !template) {
                return;
            }

            addButton.addEventListener('click', () => {
                const nextIndex = Number(list.dataset.nextIndex || list.children.length);
                const markup = template.innerHTML.split('__INDEX__').join(String(nextIndex));

                list.insertAdjacentHTML('beforeend', markup);
                list.dataset.nextIndex = String(nextIndex + 1);
            });

            list.addEventListener('click', (event) => {
                const target = event.target;

                if (!(target instanceof HTMLElement)) {
                    return;
                }

                const removeButton = target.closest('[data-nearby-remove]');

                if (!removeButton) {
                    return;
                }

                const row = removeButton.closest('[data-nearby-row]');

                if (row instanceof HTMLElement) {
                    row.remove();
                }
            });
        });
    </script>
@endpush
