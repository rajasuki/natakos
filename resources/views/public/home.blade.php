@extends('public.layout')

@section('title', $profile['name'] . ' | Homepage')

@push('styles')
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            padding-top: 32px;
            padding-bottom: 32px;
            text-align: center;
        }

        .hero-content {
            max-width: 720px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border: 1px solid var(--ui-border);
            border-radius: 999px;
            background: var(--ui-soft);
            color: var(--ui-body);
            font-size: 13px;
            font-weight: 600;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .hero-heading {
            margin: 0;
            font-size: clamp(36px, 6vw, 60px);
            font-weight: 800;
            line-height: 1.12;
            color: var(--ui-ink);
        }

        .hero-desc {
            margin: 0;
            font-size: 16px;
            color: var(--ui-body);
            line-height: 1.7;
            max-width: 560px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 14px 28px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 600;
            transition: all .2s ease;
            cursor: pointer;
        }

        .hero-btn-primary {
            background: var(--ui-accent);
            color: #fff;
            border: 0;
            box-shadow: 0 0 24px rgba(74, 124, 89, .35);
        }

        .hero-btn-primary:hover {
            background: var(--ui-accent-hover);
            box-shadow: 0 0 36px rgba(74, 124, 89, .5);
        }

        .hero-btn-secondary {
            background: transparent;
            color: var(--ui-ink);
            border: 1.5px solid var(--ui-border);
            box-shadow: 0 0 20px rgba(74, 124, 89, .12);
        }

        .hero-btn-secondary:hover {
            background: var(--ui-soft);
            border-color: var(--ui-body);
            box-shadow: 0 0 28px rgba(74, 124, 89, .28);
        }

        .section-dark {
            background: var(--ui-canvas) !important;
            color: var(--ui-ink);
        }

        .section-dark .eyebrow,
        .section-dark .section-copy-on-dark {
            color: var(--ui-body) !important;
        }

        .contact-band {
            background: var(--ui-soft) !important;
            color: var(--ui-ink);
        }

        .contact-band .eyebrow {
            color: var(--ui-body) !important;
        }

        /* ── Floating testimonial cards ── */
        .hero-floating {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .float-card {
            position: absolute;
            top: 50%;
            left: 50%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #fff;
            border: 1px solid var(--ui-border);
            border-radius: 999px;
            box-shadow: var(--ui-shadow);
            opacity: 0;
            white-space: nowrap;
            font-size: 13px;
            animation: floatCard 18s ease-in-out var(--delay) infinite;
            transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(.88);
        }

        .float-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            flex-shrink: 0;
            color: #fff;
        }

        .float-card:nth-child(1) .float-avatar { background: var(--ui-accent); }
        .float-card:nth-child(2) .float-avatar { background: var(--ui-ink); }
        .float-card:nth-child(3) .float-avatar { background: #C8D8C9; color: var(--ui-ink); }
        .float-card:nth-child(4) .float-avatar { background: var(--ui-accent); }
        .float-card:nth-child(5) .float-avatar { background: var(--ui-ink); }
        .float-card:nth-child(6) .float-avatar { background: #C8D8C9; color: var(--ui-ink); }

        .float-text {
            color: var(--ui-ink);
            font-weight: 500;
        }

        @keyframes floatCard {
            0%, 3%   { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(.88); }
            6%       { opacity: 1; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1.05); }
            9%, 20%  { opacity: 1; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1); }
            23%, 25% { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y) - 12px)) scale(.88); }
            100%     { opacity: 0; transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y) - 12px)) scale(.88); }
        }

        @media (max-width: 1023px) {
            .hero-floating {
                display: none;
            }
        }

        /* ── Map section redesign ──────────────── */
        #lokasi .detail-grid {
            max-width: 640px;
            margin: 0 auto;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        #lokasi .detail-card {
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 20px;
            overflow: hidden;
        }

        #lokasi .map-embed,
        #lokasi .map-placeholder {
            width: 100%;
            min-height: 320px;
            border: 0;
            display: block;
            background: var(--ui-soft);
        }

        #lokasi .map-placeholder {
            display: grid;
            align-items: center;
            padding: 32px;
        }

        #lokasi .detail-body {
            padding: 20px 24px;
        }

        #lokasi .detail-address {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        #lokasi .detail-address svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            margin-top: 3px;
            color: var(--ui-accent);
        }

        #lokasi .detail-address-text {
            font-size: 14px;
            line-height: 1.7;
            color: var(--ui-body);
        }

        #lokasi .feature-card {
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 20px;
            padding: 24px;
        }

        #lokasi .feature-card .eyebrow {
            margin-bottom: 6px;
        }

        #lokasi .feature-card .room-title {
            font-size: 18px;
            margin-bottom: 0;
        }

        #lokasi .nearby-list {
            display: grid;
            gap: 10px;
            margin-top: 20px;
        }

        #lokasi .nearby-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            background: var(--ui-soft);
            border-radius: 12px;
        }

        #lokasi .nearby-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }

        #lokasi .nearby-marker {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: var(--ui-accent);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        #lokasi .nearby-name {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
            color: var(--ui-ink);
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        #lokasi .nearby-estimate {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--ui-ink);
            color: var(--ui-canvas);
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }

        #lokasi .nearby-empty {
            padding: 24px;
            text-align: center;
            background: var(--ui-soft);
            border-radius: 12px;
        }

        #lokasi .nearby-empty .muted {
            margin: 0;
        }

        #lokasi .detail-copy {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-stack">

        <section class="hero-section">
            <div class="hero-content">
                <span class="hero-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Hunian terkelola
                </span>

                <h1 class="hero-heading">
                    Tinggal lebih tenang<br>dengan kamar yang rapi
                </h1>

                <p class="hero-desc">{{ $profile['description'] }}</p>

                <div class="hero-actions">
                    <a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-primary">Lihat kamar</a>
                    <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="hero-btn hero-btn-secondary">Tanya via WhatsApp</a>
                </div>

                <div class="hero-floating" aria-hidden="true">
                    <div class="float-card" style="--delay: 0s; --x: -600px; --y: -80px;">
                        <span class="float-avatar">A</span>
                        <span class="float-text">"Kosnya nyaman banget!"</span>
                    </div>
                    <div class="float-card" style="--delay: 3s; --x: 600px; --y: -40px;">
                        <span class="float-avatar">R</span>
                        <span class="float-text">"Lokasi strategis 👍"</span>
                    </div>
                    <div class="float-card" style="--delay: 6s; --x: -680px; --y: 60px;">
                        <span class="float-avatar">D</span>
                        <span class="float-text">"Kamarnya bersih rapi"</span>
                    </div>
                    <div class="float-card" style="--delay: 9s; --x: 680px; --y: 80px;">
                        <span class="float-avatar">S</span>
                        <span class="float-text">"Murah meriah!"</span>
                    </div>
                    <div class="float-card" style="--delay: 12s; --x: -540px; --y: 140px;">
                        <span class="float-avatar">F</span>
                        <span class="float-text">"Suasananya adem"</span>
                    </div>
                    <div class="float-card" style="--delay: 15s; --x: 540px; --y: 130px;">
                        <span class="float-avatar">T</span>
                        <span class="float-text">"Fasilitas lengkap"</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section" id="lokasi">
            <div class="site-shell">

                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Lokasi</p>
                        <h2 class="section-title">Lokasi & sekitar kos</h2>
                        <p class="section-copy">
                            Calon penghuni bisa langsung lihat posisi kos dan gambaran
                            akses ke tempat yang sering dicari.
                        </p>
                    </div>

                    <div class="section-actions">
                        @if ($profile['google_maps_url'])
                            <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-subtle">
                                Buka di Google Maps
                            </a>
                        @endif
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">
                            Tanya lokasi
                        </a>
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
                                    <p class="room-copy">
                                        Tambahkan Google Maps Embed URL lewat file
                                        supaya peta tampil langsung di halaman ini.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="detail-body">
                            <div class="detail-address">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="detail-address-text">{{ $profile['address'] }}</span>
                            </div>
                        </div>
                    </article>

                    <article class="feature-card">
                        <p class="eyebrow">Dekat ke mana aja</p>
                        <h3 class="room-title">Tempat sekitar</h3>

                        <div class="nearby-list">
                            @forelse ($profile['nearby_places'] as $place)
                                <div class="nearby-item">
                                    <div class="nearby-item-left">
                                        <span class="nearby-marker">
                                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="nearby-name">{{ $place['name'] }}</span>
                                    </div>

                                    @if ($place['estimate_label'] !== '')
                                        <span class="nearby-estimate">{{ $place['estimate_label'] }}</span>
                                    @endif
                                </div>
                            @empty
                                <div class="nearby-empty">
                                    <p class="muted">Belum ada daftar tempat sekitar yang ditampilkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </article>

                </div>
            </div>
        </section>

        <section class="page-section" id="kamar">
            <div class="site-shell">

                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Kamar pilihan</p>
                        <h2 class="section-title">Beberapa kamar tersedia</h2>
                        <p class="section-copy">
                            Cek kamar yang sedang tersedia dan lihat detail lengkapnya
                            sebelum menghubungi pengelola.
                        </p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                    </div>
                </div>

                @if ($featuredRooms->isEmpty())
                    <section class="empty-state">
                        <h2>Belum ada kamar tersedia</h2>
                        <p>
                            Saat ini belum ada kamar berstatus tersedia. Anda tetap bisa
                            menghubungi pengelola untuk update ketersediaan terbaru.
                        </p>

                        <div class="section-actions">
                            <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">
                                Tanya via WhatsApp
                            </a>
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
                                    <img
                                        src="{{ asset('storage/' . $coverPath) }}"
                                        alt="{{ $room->name }}"
                                        class="room-card-media"
                                    >
                                @else
                                    <div class="room-card-media media-placeholder">Foto kamar belum tersedia</div>
                                @endif

                                <div class="room-card-body">
                                    <div class="room-card-head">
                                        <div class="room-card-topbar">
                                            <span class="status-badge status-{{ $room->status }}">
                                                {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                            </span>
                                            <span class="detail-value">
                                                {{ \App\Support\UiFormatter::currency($room->price) }}
                                            </span>
                                        </div>

                                        <div>
                                            <h3 class="room-title">{{ $room->name }}</h3>
                                            <p class="room-copy">
                                                {{ $room->description ?: 'Kamar ini sudah tercatat di IchiKOS dan siap Anda cek lebih lanjut melalui halaman detail.' }}
                                            </p>
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
                                        <span class="muted">Cek detail sebelum menghubungi pemilik.</span>
                                        <a href="{{ route('rooms.show', $room) }}" class="button button-subtle">
                                            Lihat detail kamar
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>

        <section class="page-section section-dark" id="fasilitas">
            <div class="site-shell">

                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Fasilitas</p>
                        <h2 class="section-title">Fasilitas yang membuat tinggal lebih nyaman</h2>
                        <p class="section-copy section-copy-on-dark">
                            IchiKOS mengelola kombinasi fasilitas kamar dan fasilitas umum
                            agar kebutuhan harian penghuni tetap praktis.
                        </p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                            Tanyakan fasilitas
                        </a>
                    </div>
                </div>

                <div class="feature-grid">
                    @foreach ($facilityTypeLabels as $type => $label)
                        <article class="feature-card">
                            <div class="feature-card-body">
                                <p class="eyebrow">
                                    {{ $type === 'room' ? 'Di dalam kamar' : 'Area bersama' }}
                                </p>
                                <h3 class="room-title">{{ $label }}</h3>
                                <p class="room-copy">
                                    {{ $type === 'room'
                                        ? 'Perlengkapan yang melekat langsung di kamar.'
                                        : 'Fasilitas bersama yang mendukung aktivitas sehari-hari.' }}
                                </p>

                                <div class="chip-row spaced-top-md">
                                    @forelse (($facilityGroups[$type] ?? collect()) as $facility)
                                        <span class="chip">{{ $facility->name }}</span>
                                    @empty
                                        <span class="muted">Belum ada data fasilitas.</span>
                                    @endforelse
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

            </div>
        </section>

        <section class="page-section" id="kontak">
            <div class="site-shell">
                <section class="contact-band">
                    <p class="eyebrow">Kontak cepat</p>
                    <h2 class="section-title section-title-tight">Butuh informasi kamar lebih cepat?</h2>
                    <p class="section-copy section-copy-on-dark section-copy-compact">
                        Hubungi pengelola langsung melalui WhatsApp untuk menanyakan
                        detail kamar, fasilitas, dan ketersediaan terbaru.
                    </p>

                    <div class="button-row">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                            Hubungi via WhatsApp
                        </a>
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                    </div>
                </section>
            </div>
        </section>

    </div>
@endsection


