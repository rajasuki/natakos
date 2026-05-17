@extends('public.layout')

@section('title', $profile['name'].' | Homepage')

@section('content')
    <div class="page-stack">
        <section class="page-section">
            <div class="site-shell hero">
                <div class="hero-band">
                    <p class="eyebrow">Hunian terkelola</p>
                    <h1 class="headline">Tinggal lebih tenang dengan kamar yang rapi, jelas statusnya, dan mudah dicek sebelum datang.</h1>
                    <p class="lead">{{ $profile['description'] }}</p>

                    <div class="chip-row spaced-top-lg">
                        <span class="chip">{{ $stats['available_rooms'] }} kamar tersedia</span>
                        <span class="chip">{{ $stats['facility_total'] }} fasilitas</span>
                        <span class="chip">Alamat: {{ $profile['address'] }}</span>
                    </div>

                    <div class="section-actions">
                        <a href="{{ route('rooms.index') }}" class="button button-primary">Lihat kamar</a>
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanya via WhatsApp</a>
                    </div>
                </div>

                <aside class="hero-card">
                    <p class="eyebrow">Ringkasan cepat</p>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-value">{{ number_format($stats['available_rooms'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Kamar tersedia saat ini</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value">{{ number_format($stats['total_rooms'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Total kamar terdaftar</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value">{{ number_format($stats['facility_total'], 0, ',', '.') }}</div>
                            <div class="hero-stat-label">Fasilitas yang dikelola</div>
                        </div>
                    </div>

                    <p class="muted">Hubungi pengelola untuk menanyakan kamar yang paling sesuai dengan kebutuhan harian Anda.</p>
                </aside>
            </div>
        </section>

        <section class="page-section">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Kamar pilihan</p>
                        <h2 class="section-title">Beberapa kamar tersedia</h2>
                        <p class="section-copy">Cek kamar yang sedang tersedia dan lihat detail lengkapnya sebelum menghubungi pengelola.</p>
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
                                            <p class="room-copy">{{ $room->description ?: 'Kamar ini sudah tercatat di NATAKOS dan siap Anda cek lebih lanjut melalui halaman detail.' }}</p>
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

        <section class="page-section section-dark">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Fasilitas</p>
                        <h2 class="section-title">Fasilitas yang membuat tinggal lebih nyaman</h2>
                        <p class="section-copy section-copy-on-dark">NATAKOS mengelola kombinasi fasilitas kamar dan fasilitas umum agar kebutuhan harian penghuni tetap praktis.</p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanyakan fasilitas</a>
                    </div>
                </div>

                <div class="feature-grid">
                    @foreach ($facilityTypeLabels as $type => $label)
                        <article class="feature-card">
                            <div class="feature-card-body">
                                <p class="eyebrow">{{ $type === 'room' ? 'Di dalam kamar' : 'Area bersama' }}</p>
                                <h3 class="room-title">{{ $label }}</h3>
                                <p class="room-copy">{{ $type === 'room' ? 'Perlengkapan yang melekat langsung di kamar.' : 'Fasilitas bersama yang mendukung aktivitas sehari-hari.' }}</p>

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

        <section class="page-section">
            <div class="site-shell">
                <section class="contact-band">
                    <p class="eyebrow">Kontak cepat</p>
                    <h2 class="section-title section-title-tight">Butuh informasi kamar lebih cepat?</h2>
                    <p class="section-copy section-copy-on-dark section-copy-compact">Hubungi pengelola langsung melalui WhatsApp untuk menanyakan detail kamar, fasilitas, dan ketersediaan terbaru.</p>

                    <div class="button-row">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Hubungi via WhatsApp</a>
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat semua kamar</a>
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
