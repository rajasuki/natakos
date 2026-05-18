@extends('public.layout')

@section('title', $profile['name'].' | Homepage')

@push('styles')
    <style>
        .landing-hero-copy {
            display: grid;
            gap: 18px;
            max-width: 760px;
            padding: 12px 0 8px;
        }

        .landing-hero-copy .headline {
            max-width: 11ch;
            margin-bottom: 0;
        }

        .reason-grid,
        .facility-columns {
            display: grid;
            gap: 16px;
        }

        .reason-card,
        .facility-column {
            display: grid;
            gap: 12px;
            padding: 22px;
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            box-shadow: var(--ui-shadow);
        }

        .reason-card {
            background: var(--ui-softer);
        }

        .facility-column {
            background: var(--ui-canvas);
        }

        .facility-column-alt {
            background: var(--ui-soft);
        }

        .reason-step,
        .facility-kicker,
        .facility-row-label {
            margin: 0;
            color: var(--ui-body);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .reason-card .room-title,
        .facility-column .room-title {
            font-size: 22px;
        }

        .facility-column-list {
            display: grid;
            gap: 12px;
            margin-top: 18px;
        }

        .facility-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 14px;
            align-items: start;
            padding-top: 12px;
            border-top: 1px solid var(--ui-border);
        }

        .facility-row:first-child {
            padding-top: 0;
            border-top: 0;
        }

        .facility-bullet {
            display: inline-flex;
            width: 10px;
            height: 10px;
            margin-top: 8px;
            border-radius: 999px;
            background: var(--ui-ink);
        }

        @media (max-width: 767px) {
            .reason-card,
            .facility-column {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (min-width: 768px) {
            .reason-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .facility-columns {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

@section('content')
    @php
        $roomFacilities = $facilityGroups['room'] ?? collect();
        $publicFacilities = $facilityGroups['public'] ?? collect();
        $reasons = [
            [
                'title' => 'Status kamar langsung terbaca',
                'copy' => $stats['available_rooms'].' kamar tersedia bisa dicek lebih dulu, jadi calon penghuni tidak perlu menebak-nebak ketersediaan saat menghubungi pengelola.',
            ],
            [
                'title' => 'Lokasi mudah dipastikan',
                'copy' => $profile['google_maps_url'] ? 'Alamat, peta, dan daftar nearby membantu calon penghuni memahami akses harian dengan lebih cepat.' : 'Alamat kos sudah tampil dan bisa dilengkapi peta agar akses menuju lokasi lebih mudah dipastikan.',
            ],
            [
                'title' => 'Fasilitas tidak terasa samar',
                'copy' => $stats['facility_total'].' fasilitas sudah didata agar informasi kamar dan area bersama tetap konsisten saat dilihat di halaman publik maupun saat ditanya lewat WhatsApp.',
            ],
        ];
    @endphp

    <div class="page-stack">
        <section class="page-section">
            <div class="site-shell">
                <div class="landing-hero-copy">
                    <p class="eyebrow">Hunian terkelola</p>
                    <h1 class="headline">Tinggal lebih tenang di kamar yang rapi dan statusnya jelas.</h1>
                    <p class="lead">{{ $profile['description'] }}</p>

                    <div class="section-actions">
                        <a href="{{ route('rooms.index') }}" class="button button-primary">Lihat kamar</a>
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanya via WhatsApp</a>
                    </div>

                    <p class="muted">Struktur halaman dibuat supaya calon penghuni bisa mengecek ketersediaan, fasilitas, dan lokasi sebelum memutuskan datang survey.</p>
                </div>
            </div>
        </section>

        <section class="page-section" id="kamar">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Kamar pilihan</p>
                        <h2 class="section-title">Beberapa kamar yang bisa dicek lebih dulu</h2>
                        <p class="section-copy">Susunan kartu dibuat ringkas agar calon penghuni cepat memahami status, harga, ukuran, dan fasilitas utama sebelum membuka detail penuh.</p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                    </div>
                </div>

                @if ($featuredRooms->isEmpty())
                    <section class="empty-state">
                        <h2>Belum ada kamar tersedia</h2>
                        <p>Saat ini belum ada kamar berstatus tersedia. Anda tetap bisa menghubungi pengelola untuk menanyakan update ketersediaan terbaru.</p>

                        <div class="section-actions">
                            <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Tanya via WhatsApp</a>
                            <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                        </div>
                    </section>
                @else
                    <div class="room-grid">
                        @foreach ($featuredRooms as $room)
                            @php
                                $coverPath = $room->main_image ?: $room->images->first()?->image_path;
                            @endphp
                            <article class="room-card">
                                @if ($coverPath)
                                    <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="room-card-media">
                                @else
                                    <div class="room-card-media media-placeholder">Foto kamar belum tersedia</div>
                                @endif

                                <div class="room-card-body">
                                    <div class="room-card-head">
                                        <div class="room-card-topbar">
                                            <span class="status-badge status-{{ $room->status }}">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                                            <span class="detail-value">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                                        </div>

                                        <div>
                                            <h3 class="room-title">{{ $room->name }}</h3>
                                            <p class="room-copy">{{ $room->description ?: 'Kamar ini sudah tercatat di NATAKOS dan siap dicek lebih lanjut melalui halaman detail.' }}</p>
                                        </div>
                                    </div>

                                    <div class="room-card-chips">
                                        <span class="chip">Ukuran {{ $room->size ?: '-' }}</span>
                                        <span class="chip">Lantai {{ $room->floor ?: '-' }}</span>
                                        @foreach ($room->facilities->take(2) as $facility)
                                            <span class="chip">{{ $facility->name }}</span>
                                        @endforeach
                                    </div>

                                    <div class="room-card-footer">
                                        <span class="muted">Cocok untuk Anda yang ingin mengecek detail sebelum menghubungi pemilik.</span>
                                        <a href="{{ route('rooms.show', $room) }}" class="button button-subtle">Lihat detail kamar</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="page-section">
            <div class="site-shell">
                <div class="section-header section-header-tight">
                    <p class="eyebrow">Kenapa pilih</p>
                    <h2 class="section-title">Informasi paling penting tentang kos ditaruh di depan</h2>
                    <p class="section-copy">Landing page ini dirapikan agar calon penghuni lebih cepat memahami ketersediaan, lokasi, dan fasilitas tanpa harus membaca terlalu banyak elemen tambahan.</p>
                </div>

                <div class="reason-grid spaced-top-lg">
                    @foreach ($reasons as $reason)
                        <article class="reason-card">
                            <p class="reason-step">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="room-title">{{ $reason['title'] }}</h3>
                            <p class="room-copy">{{ $reason['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="page-section" id="fasilitas">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Fasilitas</p>
                        <h2 class="section-title">Fasilitas dibuat sederhana dan lebih mudah dipindai</h2>
                        <p class="section-copy">Bagian ini dipisah menjadi dua daftar utama agar calon penghuni bisa cepat menangkap apa yang tersedia di dalam kamar dan apa yang tersedia di area bersama.</p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">Tanyakan fasilitas</a>
                    </div>
                </div>

                <div class="facility-columns">
                    <article class="facility-column">
                        <p class="facility-kicker">Di dalam kamar</p>
                        <h3 class="room-title">Fasilitas kamar</h3>
                        <p class="room-copy">Perlengkapan yang langsung melekat di ruang pribadi penghuni untuk menunjang kenyamanan harian.</p>

                        <div class="facility-column-list">
                            @forelse ($roomFacilities as $facility)
                                <div class="facility-row">
                                    <span class="facility-bullet"></span>

                                    <div class="detail-item">
                                        <span class="detail-value">{{ $facility->name }}</span>
                                        <span class="muted facility-row-label">Fasilitas kamar</span>
                                    </div>
                                </div>
                            @empty
                                <div class="facility-row">
                                    <span class="facility-bullet"></span>

                                    <div class="detail-item">
                                        <span class="detail-value">Belum ada data fasilitas kamar</span>
                                        <span class="muted">Tambahkan fasilitas kamar dari dashboard admin agar bagian ini lebih informatif.</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </article>

                    <article class="facility-column facility-column-alt">
                        <p class="facility-kicker">Area bersama</p>
                        <h3 class="room-title">Fasilitas umum</h3>
                        <p class="room-copy">Fasilitas bersama yang membantu penghuni menjalani rutinitas dengan lebih praktis di luar ruang kamar.</p>

                        <div class="facility-column-list">
                            @forelse ($publicFacilities as $facility)
                                <div class="facility-row">
                                    <span class="facility-bullet"></span>

                                    <div class="detail-item">
                                        <span class="detail-value">{{ $facility->name }}</span>
                                        <span class="muted facility-row-label">Fasilitas umum</span>
                                    </div>
                                </div>
                            @empty
                                <div class="facility-row">
                                    <span class="facility-bullet"></span>

                                    <div class="detail-item">
                                        <span class="detail-value">Belum ada data fasilitas umum</span>
                                        <span class="muted">Tambahkan fasilitas umum dari dashboard admin agar calon penghuni bisa melihat nilai tambah kos ini.</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="page-section" id="lokasi">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Lokasi</p>
                        <h2 class="section-title">Lokasi & sekitar kos</h2>
                        <p class="section-copy">Calon penghuni bisa langsung lihat posisi kos dan gambaran akses cepat ke tempat yang sering dicari.</p>
                    </div>

                    <div class="section-actions">
                        @if ($profile['google_maps_url'])
                            <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">Buka di Google Maps</a>
                        @endif
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Tanya lokasi</a>
                    </div>
                </div>

                <div class="detail-grid">
                    <article class="detail-card">
                        @if ($profile['google_maps_embed_url'])
                            <iframe
                                src="{{ $profile['google_maps_embed_url'] }}"
                                class="map-embed"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi {{ $profile['name'] }} di Google Maps"
                            ></iframe>
                        @else
                            <div class="map-placeholder">
                                <div>
                                    <p class="eyebrow">Google Maps</p>
                                    <h3 class="room-title">Map embed belum diatur</h3>
                                    <p class="room-copy">Tambahkan Google Maps Embed URL dari panel admin supaya peta tampil langsung di halaman ini.</p>
                                </div>
                            </div>
                        @endif

                        <div class="detail-body">
                            <div class="detail-item">
                                <span class="detail-label">Alamat kos</span>
                                <span class="detail-value">{{ $profile['address'] }}</span>
                            </div>

                            <p class="detail-copy">Bagian ini membantu calon penghuni lebih cepat paham posisi kos dan akses ke tempat penting di sekitar.</p>

                            <div class="section-actions">
                                @if ($profile['google_maps_url'])
                                    <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Buka peta penuh</a>
                                @endif
                                <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">Hubungi pengelola</a>
                            </div>
                        </div>
                    </article>

                    <article class="feature-card">
                        <div class="feature-card-body">
                            <p class="eyebrow">Dekat ke mana aja</p>
                            <h3 class="room-title">Tempat sekitar yang sering ditanya</h3>
                            <p class="room-copy">Daftar ini membantu calon penghuni menilai akses harian tanpa harus bertanya ulang satu per satu.</p>

                            <div class="nearby-list spaced-top-md">
                                @forelse ($profile['nearby_places'] as $place)
                                    <div class="nearby-item">
                                        <span class="nearby-marker">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                                        <div class="nearby-item-copy">
                                            <div class="detail-item">
                                                <span class="detail-label">Nama tempat</span>
                                                <span class="detail-value">{{ $place['name'] }}</span>
                                            </div>

                                            @if ($place['estimate_label'] !== '')
                                                <div class="detail-item">
                                                    <span class="detail-label">Estimasi</span>
                                                    <span class="nearby-estimate">{{ $place['estimate_label'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="nearby-empty">
                                        <p class="muted">Belum ada daftar tempat sekitar yang ditampilkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="page-section" id="kontak">
            <div class="site-shell">
                <section class="contact-band">
                    <p class="eyebrow">Kontak cepat</p>
                    <h2 class="section-title section-title-tight">Butuh info kamar yang cocok untuk budget Anda?</h2>
                    <p class="section-copy section-copy-on-dark section-copy-compact">Hubungi pengelola langsung melalui WhatsApp untuk menanyakan detail kamar, fasilitas, dan ketersediaan terbaru tanpa harus menebak-nebak informasi dasarnya.</p>

                    <div class="button-row">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Hubungi via WhatsApp</a>
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
