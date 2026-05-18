@extends('public.layout')

@section('title', $profile['name'].' | Homepage')

@section('content')

{{-- ============================================================
     INLINE STYLES — scoped to this page only
     ============================================================ --}}
<style>
  /* ── Tokens ─────────────────────────────────────────── */
  :root {
    --c-cream:   #F9F6F1;
    --c-ink:     #1A1714;
    --c-ink-2:   #4A4540;
    --c-ink-3:   #8C8480;
    --c-accent:  #C4622D;
    --c-accent-2:#E8845A;
    --c-line:    rgba(26,23,20,.10);
    --c-line-2:  rgba(26,23,20,.06);
    --c-surface: #FFFFFF;
    --r-sm:  6px;
    --r-md: 12px;
    --r-lg: 20px;
    --r-xl: 28px;
    --shell: clamp(1.25rem, 5vw, 5rem);
    --font-display: 'DM Serif Display', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
    --ease: cubic-bezier(.22,.68,0,1.2);
  }

  /* ── Base ────────────────────────────────────────────── */
  .nk-page { font-family: var(--font-body); color: var(--c-ink); background: var(--c-cream); }
  .nk-shell { max-width: 1180px; margin: 0 auto; padding-inline: var(--shell); }
  .nk-page * { box-sizing: border-box; }

  /* ── Section rhythm ──────────────────────────────────── */
  .nk-section        { padding-block: clamp(4rem, 8vw, 7rem); }
  .nk-section-dark   { background: var(--c-ink); color: #FBF9F7; }
  .nk-section-white  { background: var(--c-surface); }

  /* ── Typography ──────────────────────────────────────── */
  .nk-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .75rem; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: var(--c-accent);
    margin-bottom: .9rem;
  }
  .nk-eyebrow::before {
    content: ''; width: 20px; height: 2px;
    background: var(--c-accent); display: block;
  }
  .nk-h1 {
    font-family: var(--font-display);
    font-size: clamp(2.4rem, 5.5vw, 4.2rem);
    font-weight: 400; line-height: 1.1;
    letter-spacing: -.02em; color: var(--c-ink);
    margin: 0 0 1.25rem;
  }
  .nk-h2 {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    font-weight: 400; line-height: 1.2;
    color: var(--c-ink); margin: 0 0 .75rem;
  }
  .nk-h2-on-dark { color: #FBF9F7; }
  .nk-h3 {
    font-family: var(--font-display);
    font-size: 1.3rem; font-weight: 400;
    color: var(--c-ink); margin: 0 0 .5rem;
  }
  .nk-lead {
    font-size: 1.05rem; line-height: 1.7;
    color: var(--c-ink-2); max-width: 52ch;
    margin: 0 0 2rem;
  }
  .nk-copy {
    font-size: .925rem; line-height: 1.65;
    color: var(--c-ink-2); margin: 0;
  }
  .nk-muted { font-size: .825rem; color: var(--c-ink-3); }

  /* ── Buttons ─────────────────────────────────────────── */
  .nk-btn {
    display: inline-flex; align-items: center; gap: .45rem;
    font-family: var(--font-body); font-size: .875rem;
    font-weight: 500; text-decoration: none;
    padding: .7rem 1.4rem; border-radius: 100px;
    border: 1.5px solid transparent;
    transition: transform .15s var(--ease), background .15s, color .15s, border-color .15s;
    white-space: nowrap; cursor: pointer;
  }
  .nk-btn:hover { transform: translateY(-1px); }
  .nk-btn:active { transform: scale(.98); }
  .nk-btn-primary {
    background: var(--c-accent); color: #fff;
    border-color: var(--c-accent);
  }
  .nk-btn-primary:hover { background: var(--c-accent-2); border-color: var(--c-accent-2); }
  .nk-btn-outline {
    background: transparent; color: var(--c-ink);
    border-color: rgba(26,23,20,.25);
  }
  .nk-btn-outline:hover { border-color: var(--c-ink); }
  .nk-btn-ghost {
    background: transparent; color: #FBF9F7;
    border-color: rgba(255,255,255,.3);
  }
  .nk-btn-ghost:hover { border-color: rgba(255,255,255,.7); }
  .nk-btn-sm { font-size: .8rem; padding: .5rem 1rem; }

  .nk-btn-row {
    display: flex; flex-wrap: wrap; gap: .75rem;
    align-items: center; margin-top: 2rem;
  }

  /* ── Chips ───────────────────────────────────────────── */
  .nk-chip {
    display: inline-flex; align-items: center;
    font-size: .78rem; font-weight: 500;
    padding: .35rem .85rem; border-radius: 100px;
    background: rgba(26,23,20,.06);
    color: var(--c-ink-2); border: 1px solid var(--c-line);
    white-space: nowrap;
  }
  .nk-chip-row { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1.25rem; }

  /* ── HERO ────────────────────────────────────────────── */
  .hero-wrap {
    display: grid;
    grid-template-columns: 1fr minmax(0,380px);
    gap: 4rem; align-items: start;
    padding-block: clamp(4rem,9vw,8rem);
  }
  @media(max-width:860px){
    .hero-wrap { grid-template-columns: 1fr; gap: 2.5rem; }
  }

  .hero-band { max-width: 600px; }

  /* Stat card in hero */
  .hero-card {
    background: var(--c-surface);
    border-radius: var(--r-xl);
    border: 1px solid var(--c-line);
    padding: 2rem;
    position: sticky; top: 2rem;
  }
  .hero-card .nk-eyebrow { margin-bottom: 1.5rem; }
  .hero-stats { display: flex; flex-direction: column; gap: 0; }
  .hero-stat {
    padding: 1rem 0;
    border-bottom: 1px solid var(--c-line-2);
  }
  .hero-stat:last-child { border-bottom: none; }
  .hero-stat-val {
    font-family: var(--font-display);
    font-size: 2.4rem; font-weight: 400;
    color: var(--c-ink); line-height: 1;
    margin-bottom: .2rem;
  }
  .hero-stat-label { font-size: .8rem; color: var(--c-ink-3); }

  /* decorative number bar */
  .hero-card-foot {
    margin-top: 1.5rem;
    padding: 1rem;
    background: var(--c-cream);
    border-radius: var(--r-md);
    font-size: .825rem; color: var(--c-ink-2); line-height: 1.55;
  }

  /* ── SECTION HEADER ──────────────────────────────────── */
  .nk-section-head {
    display: flex; justify-content: space-between;
    align-items: flex-end; gap: 2rem;
    margin-bottom: 3rem; flex-wrap: wrap;
  }
  .nk-section-head-text { max-width: 500px; }

  /* ── LOCATION ────────────────────────────────────────── */
  .location-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  @media(max-width:720px){ .location-grid { grid-template-columns: 1fr; } }

  .nk-card {
    background: var(--c-surface);
    border-radius: var(--r-lg);
    border: 1px solid var(--c-line);
    overflow: hidden;
  }

  .map-embed {
    width: 100%; height: 260px;
    display: block; border: none;
  }
  .map-placeholder {
    width: 100%; height: 260px;
    background: var(--c-cream);
    display: flex; align-items: center;
    justify-content: center; text-align: center;
    padding: 2rem;
  }
  .card-body { padding: 1.5rem; }

  .detail-row { display: flex; flex-direction: column; gap: .75rem; }
  .detail-item { display: flex; flex-direction: column; gap: .2rem; }
  .detail-label { font-size: .7rem; font-weight: 600; letter-spacing: .07em; text-transform: uppercase; color: var(--c-ink-3); }
  .detail-value { font-size: .9rem; color: var(--c-ink); }

  /* Nearby list */
  .nearby-card { background: var(--c-surface); border-radius: var(--r-lg); border: 1px solid var(--c-line); padding: 1.75rem; height: 100%; }
  .nearby-list { display: flex; flex-direction: column; gap: 0; margin-top: 1.25rem; }
  .nearby-item {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: .9rem 0; border-bottom: 1px solid var(--c-line-2);
  }
  .nearby-item:last-child { border-bottom: none; padding-bottom: 0; }
  .nearby-num {
    font-family: var(--font-display);
    font-size: 1.1rem; color: var(--c-accent);
    min-width: 28px; padding-top: .05rem;
  }
  .nearby-name { font-size: .875rem; font-weight: 500; color: var(--c-ink); margin-bottom: .15rem; }
  .nearby-est { font-size: .775rem; color: var(--c-ink-3); }
  .nearby-empty { padding: 1rem 0; }

  /* ── ROOM GRID ───────────────────────────────────────── */
  .room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
  }

  .room-card {
    background: var(--c-surface);
    border-radius: var(--r-lg); border: 1px solid var(--c-line);
    overflow: hidden;
    transition: transform .2s var(--ease), box-shadow .2s;
    display: flex; flex-direction: column;
  }
  .room-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(26,23,20,.08);
  }

  .room-card-img {
    width: 100%; aspect-ratio: 16/10;
    object-fit: cover; display: block;
  }
  .room-card-placeholder {
    width: 100%; aspect-ratio: 16/10;
    background: var(--c-cream);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; color: var(--c-ink-3);
  }
  .room-card-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .9rem; flex: 1; }
  .room-card-topbar { display: flex; justify-content: space-between; align-items: center; gap: .5rem; }
  .room-card-title { font-family: var(--font-display); font-size: 1.15rem; font-weight: 400; color: var(--c-ink); margin: 0; }
  .room-card-desc { font-size: .85rem; color: var(--c-ink-2); line-height: 1.55; margin: 0; }
  .room-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: .5rem; }
  .room-price { font-family: var(--font-display); font-size: 1.2rem; color: var(--c-ink); }

  /* Status badges */
  .nk-badge {
    display: inline-flex; align-items: center;
    font-size: .7rem; font-weight: 600; letter-spacing: .05em;
    text-transform: uppercase; padding: .25rem .7rem;
    border-radius: 100px;
  }
  .badge-available { background: #EAF3DE; color: #3B6D11; }
  .badge-occupied  { background: #FAECE7; color: #993C1D; }
  .badge-booked    { background: #FAEEDA; color: #854F0B; }
  .badge-maintenance { background: #F1EFE8; color: #5F5E5A; }

  /* Empty state */
  .nk-empty {
    text-align: center; padding: 4rem 2rem;
    border: 1.5px dashed var(--c-line);
    border-radius: var(--r-xl);
  }
  .nk-empty h2 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 400; margin: 0 0 .5rem; }
  .nk-empty p  { font-size: .9rem; color: var(--c-ink-2); max-width: 40ch; margin: 0 auto 1.5rem; }

  /* ── FACILITIES ──────────────────────────────────────── */
  .facility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
  }
  .facility-card {
    border: 1px solid rgba(255,255,255,.12);
    border-radius: var(--r-lg);
    padding: 1.75rem;
  }
  .facility-eyebrow {
    font-size: .7rem; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: var(--c-accent-2);
    margin-bottom: .75rem;
  }
  .facility-title { font-family: var(--font-display); font-size: 1.2rem; font-weight: 400; color: #FBF9F7; margin: 0 0 .5rem; }
  .facility-copy  { font-size: .825rem; color: rgba(251,249,247,.5); margin: 0 0 1.25rem; }

  .fac-chip {
    display: inline-flex; align-items: center;
    font-size: .775rem; font-weight: 500;
    padding: .3rem .8rem; border-radius: 100px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    color: rgba(251,249,247,.8);
    margin: .25rem .25rem 0 0;
  }

  /* ── CTA BAND ────────────────────────────────────────── */
  .cta-band {
    border-radius: var(--r-xl);
    background: var(--c-accent);
    padding: clamp(2.5rem,5vw,4rem);
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem; align-items: center;
  }
  @media(max-width:640px){ .cta-band { grid-template-columns: 1fr; } }
  .cta-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 400; color: #fff; margin: 0 0 .5rem;
  }
  .cta-copy { font-size: .9rem; color: rgba(255,255,255,.75); margin: 0; max-width: 48ch; }
  .cta-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: flex-end; }

  /* ── Divider ornament ────────────────────────────────── */
  .nk-divider {
    width: 40px; height: 2px;
    background: var(--c-accent); border-radius: 2px;
    margin: 1.25rem 0;
  }

  /* ── Fade-in animation ───────────────────────────────── */
  @keyframes nk-rise {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .nk-animate { animation: nk-rise .55s var(--ease) both; }
  .nk-delay-1 { animation-delay: .1s; }
  .nk-delay-2 { animation-delay: .2s; }
  .nk-delay-3 { animation-delay: .3s; }
  .nk-delay-4 { animation-delay: .4s; }
</style>

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<div class="nk-page">

    {{-- ══════════════════════════════════════
         HERO
    ══════════════════════════════════════ --}}
    <section class="nk-section" style="padding-bottom: 0;">
        <div class="nk-shell">
            <div class="hero-wrap">

                {{-- Left: Copy --}}
                <div class="hero-band">
                    <p class="nk-eyebrow nk-animate">Hunian terkelola</p>
                    <h1 class="nk-h1 nk-animate nk-delay-1">Tinggal lebih tenang, kamar rapi dan jelas statusnya.</h1>
                    <p class="nk-lead nk-animate nk-delay-2">{{ $profile['description'] }}</p>

                    <div class="nk-chip-row nk-animate nk-delay-2">
                        <span class="nk-chip">{{ $stats['available_rooms'] }} kamar tersedia</span>
                        <span class="nk-chip">{{ $stats['facility_total'] }} fasilitas</span>
                        <span class="nk-chip">{{ $profile['address'] }}</span>
                    </div>

                    <div class="nk-btn-row nk-animate nk-delay-3">
                        <a href="{{ route('rooms.index') }}" class="nk-btn nk-btn-primary">Lihat kamar</a>
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-outline">Tanya via WhatsApp</a>
                    </div>
                </div>

                {{-- Right: Stat card --}}
                <aside class="hero-card nk-animate nk-delay-4" aria-label="Ringkasan kos">
                    <p class="nk-eyebrow">Ringkasan cepat</p>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-val">{{ number_format($stats['available_rooms'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Kamar tersedia saat ini</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-val">{{ number_format($stats['total_rooms'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Total kamar terdaftar</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-val">{{ number_format($stats['facility_total'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Fasilitas yang dikelola</div>
                        </div>
                    </div>
                    <div class="hero-card-foot">
                        Hubungi pengelola untuk menemukan kamar yang paling sesuai kebutuhan harian Anda.
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         LOCATION
    ══════════════════════════════════════ --}}
    <section class="nk-section nk-section-white">
        <div class="nk-shell">

            <div class="nk-section-head">
                <div class="nk-section-head-text">
                    <p class="nk-eyebrow">Lokasi</p>
                    <h2 class="nk-h2">Lokasi &amp; sekitar kos</h2>
                    <p class="nk-copy">Calon penghuni bisa langsung lihat posisi kos dan gambaran akses cepat ke tempat yang sering dicari.</p>
                </div>
                <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                    @if ($profile['google_maps_url'])
                        <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-outline nk-btn-sm">Buka Google Maps</a>
                    @endif
                    <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-primary nk-btn-sm">Tanya lokasi</a>
                </div>
            </div>

            <div class="location-grid">

                {{-- Map card --}}
                <div class="nk-card">
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
                                <p class="nk-eyebrow" style="justify-content:center;">Google Maps</p>
                                <p class="nk-copy" style="margin-top:.5rem;">Map embed belum diatur. Tambahkan URL dari panel admin.</p>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="detail-row">
                            <div class="detail-item">
                                <span class="detail-label">Alamat kos</span>
                                <span class="detail-value">{{ $profile['address'] }}</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:1.25rem;">
                            @if ($profile['google_maps_url'])
                                <a href="{{ $profile['google_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-outline nk-btn-sm">Buka peta penuh</a>
                            @endif
                            <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-primary nk-btn-sm">Hubungi pengelola</a>
                        </div>
                    </div>
                </div>

                {{-- Nearby card --}}
                <div class="nearby-card">
                    <p class="nk-eyebrow">Dekat ke mana saja</p>
                    <h3 class="nk-h3">Tempat sekitar yang sering ditanya</h3>
                    <p class="nk-copy">Kampus, warung, laundry, tempat ibadah, atau transportasi terdekat.</p>

                    <div class="nearby-list">
                        @forelse ($profile['nearby_places'] as $place)
                            <div class="nearby-item">
                                <span class="nearby-num">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div style="flex:1;">
                                    <div class="nearby-name">{{ $place['name'] }}</div>
                                    @if ($place['estimate_label'] !== '')
                                        <div class="nearby-est">{{ $place['estimate_label'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="nearby-empty">
                                <p class="nk-muted">Belum ada daftar tempat sekitar yang ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         ROOMS
    ══════════════════════════════════════ --}}
    <section class="nk-section">
        <div class="nk-shell">

            <div class="nk-section-head">
                <div class="nk-section-head-text">
                    <p class="nk-eyebrow">Kamar pilihan</p>
                    <h2 class="nk-h2">Beberapa kamar tersedia</h2>
                    <p class="nk-copy">Cek kamar yang sedang tersedia dan lihat detail lengkapnya sebelum menghubungi pengelola.</p>
                </div>
                <a href="{{ route('rooms.index') }}" class="nk-btn nk-btn-outline nk-btn-sm">Lihat semua kamar</a>
            </div>

            @if ($featuredRooms->isEmpty())
                <div class="nk-empty">
                    <h2>Belum ada kamar tersedia</h2>
                    <p>Saat ini belum ada kamar berstatus tersedia. Hubungi pengelola untuk update terbaru.</p>
                    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-primary">Tanya via WhatsApp</a>
                        <a href="{{ route('rooms.index') }}" class="nk-btn nk-btn-outline">Lihat semua kamar</a>
                    </div>
                </div>
            @else
                <div class="room-grid">
                    @foreach ($featuredRooms as $room)
                        @php $coverPath = $room->main_image ?: $room->images->first()?->image_path; @endphp
                        <article class="room-card">
                            @if ($coverPath)
                                <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="room-card-img">
                            @else
                                <div class="room-card-placeholder">Foto kamar belum tersedia</div>
                            @endif

                            <div class="room-card-body">
                                <div class="room-card-topbar">
                                    @php
                                        $badgeMap = [
                                            'available'   => 'badge-available',
                                            'occupied'    => 'badge-occupied',
                                            'booked'      => 'badge-booked',
                                            'maintenance' => 'badge-maintenance',
                                        ];
                                    @endphp
                                    <span class="nk-badge {{ $badgeMap[$room->status] ?? 'badge-maintenance' }}">
                                        {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                    </span>
                                    <span class="room-price">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                                </div>

                                <div>
                                    <h3 class="room-card-title">{{ $room->name }}</h3>
                                    <p class="room-card-desc">{{ $room->description ?: 'Kamar ini sudah tercatat dan siap Anda cek lebih lanjut melalui halaman detail.' }}</p>
                                </div>

                                <div class="nk-chip-row" style="margin-top:0;">
                                    <span class="nk-chip">{{ $room->size ?: '-' }}</span>
                                    <span class="nk-chip">Lantai {{ $room->floor ?: '-' }}</span>
                                    @foreach ($room->facilities->take(2) as $facility)
                                        <span class="nk-chip">{{ $facility->name }}</span>
                                    @endforeach
                                </div>

                                <div class="room-card-footer">
                                    <a href="{{ route('rooms.show', $room) }}" class="nk-btn nk-btn-outline nk-btn-sm">Lihat detail kamar</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════
         FACILITIES (dark)
    ══════════════════════════════════════ --}}
    <section class="nk-section nk-section-dark">
        <div class="nk-shell">

            <div class="nk-section-head" style="margin-bottom:3rem;">
                <div class="nk-section-head-text">
                    <p class="nk-eyebrow">Fasilitas</p>
                    <h2 class="nk-h2 nk-h2-on-dark">Fasilitas yang membuat tinggal lebih nyaman</h2>
                    <p class="nk-copy" style="color:rgba(251,249,247,.55);">Kombinasi fasilitas kamar dan fasilitas umum untuk kebutuhan harian yang praktis.</p>
                </div>
                <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="nk-btn nk-btn-ghost nk-btn-sm">Tanyakan fasilitas</a>
            </div>

            <div class="facility-grid">
                @foreach ($facilityTypeLabels as $type => $label)
                    <article class="facility-card">
                        <p class="facility-eyebrow">{{ $type === 'room' ? 'Di dalam kamar' : 'Area bersama' }}</p>
                        <h3 class="facility-title">{{ $label }}</h3>
                        <p class="facility-copy">{{ $type === 'room' ? 'Perlengkapan yang melekat langsung di kamar.' : 'Fasilitas bersama untuk aktivitas sehari-hari.' }}</p>
                        <div>
                            @forelse (($facilityGroups[$type] ?? collect()) as $facility)
                                <span class="fac-chip">{{ $facility->name }}</span>
                            @empty
                                <p class="nk-muted" style="color:rgba(251,249,247,.4);margin:0;">Belum ada data.</p>
                            @endforelse
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CTA BAND
    ══════════════════════════════════════ --}}
    <section class="nk-section nk-section-white">
        <div class="nk-shell">
            <div class="cta-band">
                <div>
                    <h2 class="cta-title">Butuh informasi kamar lebih cepat?</h2>
                    <p class="cta-copy">Hubungi pengelola langsung via WhatsApp — tanyakan detail kamar, fasilitas, dan ketersediaan terbaru.</p>
                </div>
                <div class="cta-actions">
                    <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer"
                       class="nk-btn" style="background:#fff;color:var(--c-accent);border-color:#fff;font-weight:600;">
                        Hubungi via WhatsApp
                    </a>
                    <a href="{{ route('rooms.index') }}"
                       class="nk-btn" style="background:transparent;color:#fff;border-color:rgba(255,255,255,.4);">
                        Lihat semua kamar
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection