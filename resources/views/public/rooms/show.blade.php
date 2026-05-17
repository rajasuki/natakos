@extends('public.layout')

@section('title', $room->name.' | '.$profile['name'])

@section('content')
    <div class="page-stack">
        <section class="page-section">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Detail kamar</p>
                        <h1 class="headline">{{ $room->name }}</h1>
                        <p class="lead">Lihat ringkasan detail kamar, status saat ini, fasilitas yang tersedia, dan hubungi pengelola jika Anda tertarik.</p>
                    </div>

                    <div class="section-actions">
                        <span class="chip">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                        <span class="chip">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        @php
                            $coverPath = $room->main_image ?: $room->images->first()?->image_path;
                        @endphp

                        @if ($coverPath)
                            <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="detail-media">
                        @else
                            <div class="detail-media media-placeholder">Foto utama kamar belum tersedia</div>
                        @endif
                    </div>

                    <article class="detail-card">
                        <div class="detail-body">
                            <div class="room-card-topbar room-card-topbar-spaced">
                                <span class="status-badge status-{{ $room->status }}">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                                <span class="detail-value">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                            </div>

                            <h2 class="detail-title">Informasi utama kamar</h2>
                            <p class="detail-copy">{{ $room->description ?: 'Belum ada deskripsi rinci untuk kamar ini. Silakan hubungi pengelola untuk mendapatkan informasi tambahan.' }}</p>

                            <div class="spec-grid spaced-top-lg">
                                <div class="detail-item">
                                    <div class="detail-label">Nama kamar</div>
                                    <div class="detail-value">{{ $room->name }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Ukuran</div>
                                    <div class="detail-value">{{ $room->size ?: '-' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Lantai</div>
                                    <div class="detail-value">{{ $room->floor ?: '-' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status kamar</div>
                                    <div class="detail-value">{{ $roomStatusLabels[$room->status] ?? $room->status }}</div>
                                </div>
                            </div>

                            <div class="section-actions">
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Tanya via WhatsApp</a>
                                <a href="{{ route('rooms.index') }}" class="button button-subtle">Kembali ke daftar kamar</a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="page-section">
            <div class="site-shell">
                <div class="section-split">
                    <div class="section-header section-header-tight">
                        <p class="eyebrow">Fasilitas kamar</p>
                        <h2 class="section-title">Fasilitas yang tersedia</h2>
                        <p class="section-copy">Fasilitas dibagi antara kebutuhan langsung di kamar dan fasilitas pendukung di area bersama.</p>
                    </div>

                    <div class="section-actions">
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanya soal fasilitas</a>
                    </div>
                </div>

                <div class="facilities-grid">
                    @foreach ($facilityTypeLabels as $type => $label)
                        <article class="feature-card">
                            <div class="feature-card-body">
                                <p class="eyebrow">{{ $type === 'room' ? 'Fasilitas utama' : 'Fasilitas pendukung' }}</p>
                                <h2 class="room-title">{{ $label }}</h2>
                                <p class="room-copy">{{ $type === 'room' ? 'Fasilitas yang tersedia langsung di kamar.' : 'Fasilitas bersama yang bisa mendukung keseharian penghuni.' }}</p>

                                <div class="chip-row spaced-top-sm">
                                    @forelse (($facilityGroups[$type] ?? collect()) as $facility)
                                        <span class="chip">{{ $facility->name }}</span>
                                    @empty
                                        <span class="muted">Belum ada fasilitas pada kelompok ini.</span>
                                    @endforelse
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        @if ($room->images->isNotEmpty())
            <section class="page-section">
                <div class="site-shell">
                    <div class="section-header">
                        <p class="eyebrow">Galeri tambahan</p>
                        <h2 class="section-title">Foto lain dari {{ $room->name }}</h2>
                        <p class="section-copy">Lihat beberapa sudut tambahan kamar yang sudah diunggah ke sistem.</p>
                    </div>

                    <div class="gallery-grid">
                        @foreach ($room->images as $image)
                            <article class="gallery-card">
                                <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->caption ?: $room->name }}" class="gallery-image">
                                <div class="gallery-card-body">
                                    <p class="room-copy">{{ $image->caption ?: 'Galeri tambahan kamar '.$room->name }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="page-section">
            <div class="site-shell">
                <section class="contact-band">
                    <p class="eyebrow">Kontak WhatsApp</p>
                    <h2 class="section-title section-title-tight">Tertarik dengan {{ $room->name }}?</h2>
                    <p class="section-copy section-copy-on-dark section-copy-compact">Hubungi pengelola {{ $profile['name'] }} langsung untuk menanyakan ketersediaan, fasilitas, dan detail kamar ini.</p>

                    <div class="button-row">
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanya via WhatsApp</a>
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Lihat kamar lain</a>
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
