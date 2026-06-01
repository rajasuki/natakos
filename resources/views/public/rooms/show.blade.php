@extends('public.layout')

@section('title', $room->name.' | '.$profile['name'])

@php
    $coverPath = $room->main_image ?: $room->images->first()?->image_path;
    $statusIcon = match ($room->status) {
        'available' => 'check_circle',
        'occupied' => 'block',
        'maintenance' => 'engineering',
        default => 'info',
    };
    $totalImages = $room->images->count();

    $facilityIconMap = [
        'Kamar Mandi Dalam' => 'shower',
        'Kasur' => 'bed',
        'Lemari' => 'styler',
        'Meja Belajar' => 'desk',
        'Spring Bed' => 'bed',
        'AC' => 'ac_unit',
        'Kipas Angin' => 'mode_fan',
        'TV' => 'tv',
        'WiFi' => 'wifi',
        'Parkiran' => 'local_parking',
        'Parkir Motor' => 'local_parking',
        'Dapur Bersama' => 'countertops',
        'CCTV 24 Jam' => 'videocam',
    ];
@endphp

@push('styles')
<style>
.detail-bento-back {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
}

.detail-bento-back a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--ui-body);
    font-size: 14px;
    font-weight: 500;
    transition: color .2s;
}

.detail-bento-back a:hover {
    color: var(--ui-ink);
}

.detail-bento-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

@media (min-width: 1024px) {
    .detail-bento-grid {
        grid-template-columns: 2fr 1fr;
    }
}

/* ── Hero Card ── */
.detail-hero-card {
    background: #fff;
    border: 1px solid var(--ui-border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
}

.detail-hero-image {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    display: block;
    background: var(--ui-soft);
}

.detail-hero-placeholder {
    width: 100%;
    aspect-ratio: 16 / 9;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--ui-soft);
    color: var(--ui-body);
    font-size: 14px;
}

.detail-hero-status {
    position: absolute;
    top: 16px;
    right: 16px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
    pointer-events: none;
}

.detail-hero-status .material-symbols-outlined {
    font-size: 18px;
}

.detail-hero-status-available {
    background: #d1fae5;
    color: #065f46;
}

.detail-hero-status-occupied {
    background: #fee2e2;
    color: #991b1b;
}

.detail-hero-status-maintenance {
    background: #fef3c7;
    color: #92400e;
}

/* ── Info Card ── */
.detail-info-card {
    background: #fff;
    border: 1px solid var(--ui-border);
    border-radius: 16px;
    padding: 28px;
}

.detail-info-header {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--ui-border);
    margin-bottom: 24px;
}

.detail-info-header h1 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
    color: var(--ui-ink);
    letter-spacing: -0.3px;
}

.detail-info-header p {
    margin: 0;
    font-size: 14px;
    color: var(--ui-body);
}

.detail-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.detail-info-box {
    background: var(--ui-canvas);
    padding: 16px;
    border-radius: 12px;
}

.detail-info-box .material-symbols-outlined {
    font-size: 22px;
    color: var(--ui-body);
    margin-bottom: 8px;
    display: block;
}

.detail-info-box-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ui-body);
    margin: 0 0 4px;
}

.detail-info-box-value {
    font-size: 16px;
    font-weight: 600;
    color: var(--ui-ink);
    margin: 0;
}

.detail-description {
    color: var(--ui-body);
    font-size: 14px;
    line-height: 1.7;
    margin: 0;
}

/* ── Gallery ── */
.detail-gallery-card {
    background: #fff;
    border: 1px solid var(--ui-border);
    border-radius: 16px;
    padding: 28px;
}

.detail-gallery-card h3 {
    margin: 0 0 16px;
    font-size: 16px;
    font-weight: 700;
    color: var(--ui-ink);
}

.detail-gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

@media (min-width: 640px) {
    .detail-gallery-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.detail-gallery-item {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--ui-border);
}

.detail-gallery-item img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    display: block;
    background: var(--ui-soft);
}

.detail-gallery-more {
    border-radius: 10px;
    border: 1px solid var(--ui-border);
    background: var(--ui-soft);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: default;
    aspect-ratio: 4 / 3;
    color: var(--ui-body);
    transition: background .2s;
}

.detail-gallery-more:hover {
    background: var(--ui-border);
}

.detail-gallery-more .material-symbols-outlined {
    font-size: 28px;
}

.detail-gallery-more span:last-child {
    font-size: 13px;
    font-weight: 600;
}

/* ── Amenities ── */
.detail-amenities-card {
    background: #fff;
    border: 1px solid var(--ui-border);
    border-radius: 16px;
    padding: 28px;
}

.detail-amenities-card h3 {
    margin: 0 0 20px;
    font-size: 18px;
    font-weight: 700;
    color: var(--ui-ink);
}

.detail-amenities-group {
    margin-bottom: 20px;
}

.detail-amenities-group:last-child {
    margin-bottom: 0;
}

.detail-amenities-group h4 {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0 0 10px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ui-body);
}

.detail-amenities-group h4 .material-symbols-outlined {
    font-size: 16px;
}

.detail-amenities-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 6px;
}

@media (min-width: 640px) {
    .detail-amenities-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

.detail-amenities-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--ui-ink);
    line-height: 1.3;
}

.detail-amenities-item .material-symbols-outlined {
    font-size: 18px;
    flex-shrink: 0;
}

/* ── CTA ── */
.detail-cta-card {
    background: var(--ui-accent);
    border-radius: 16px;
    padding: 28px;
    color: #fff;
}

.detail-cta-card h3 {
    margin: 0 0 6px;
    font-size: 20px;
    font-weight: 700;
}

.detail-cta-card p {
    margin: 0 0 20px;
    font-size: 13px;
    line-height: 1.6;
    opacity: .85;
}

.detail-cta-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.detail-cta-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background .2s, opacity .2s;
    text-decoration: none;
}

.detail-cta-button-primary {
    background: #fff;
    color: var(--ui-accent);
}

.detail-cta-button-primary:hover {
    background: #f0f0f0;
}

.detail-cta-button-secondary {
    background: rgba(255,255,255,.15);
    color: #fff;
}

.detail-cta-button-secondary:hover {
    background: rgba(255,255,255,.25);
}

@media (max-width: 1023px) {
    .detail-bento-back {
        margin-bottom: 16px;
    }

    .detail-info-card,
    .detail-gallery-card,
    .detail-amenities-card,
    .detail-cta-card {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
    <div class="site-shell" style="padding-top: 24px; padding-bottom: 48px;">
        {{-- Back Navigation --}}
        <nav class="detail-bento-back">
            <a href="{{ route('rooms.index') }}">
                <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                Kembali ke daftar kamar
            </a>
        </nav>

        <div class="detail-bento-grid">
            {{-- LEFT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Hero Image --}}
                <div class="detail-hero-card">
                    @if ($coverPath)
                        <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="detail-hero-image">
                    @else
                        <div class="detail-hero-placeholder">
                            <span class="material-symbols-outlined" style="font-size:48px;opacity:.3;">image</span>
                        </div>
                    @endif
                    <div class="detail-hero-status detail-hero-status-{{ $room->status }}">
                        <span class="material-symbols-outlined">{{ $statusIcon }}</span>
                        <span>{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="detail-info-card">
                    <div class="detail-info-header">
                        <h1>{{ $room->name }}</h1>
                        <p>{{ $profile['name'] }} &middot; {{ \App\Support\UiFormatter::currency($room->price) }} /Bln</p>
                    </div>

                    <div class="detail-info-grid">
                        <div class="detail-info-box">
                            <span class="material-symbols-outlined">straighten</span>
                            <p class="detail-info-box-label">Ukuran</p>
                            <p class="detail-info-box-value">{{ $room->size ?: '-' }}</p>
                        </div>
                        <div class="detail-info-box">
                            <span class="material-symbols-outlined">layers</span>
                            <p class="detail-info-box-label">Lantai</p>
                            <p class="detail-info-box-value">{{ $room->floor ?: '-' }}</p>
                        </div>
                        <div class="detail-info-box">
                            <span class="material-symbols-outlined">event_available</span>
                            <p class="detail-info-box-label">Tersedia Sejak</p>
                            <p class="detail-info-box-value">{{ \App\Support\UiFormatter::date($room->created_at) }}</p>
                        </div>
                        <div class="detail-info-box">
                            <span class="material-symbols-outlined">bolt</span>
                            <p class="detail-info-box-label">Listrik</p>
                            <p class="detail-info-box-value">Token</p>
                        </div>
                    </div>

                    <p class="detail-description">{{ $room->description ?: 'Belum ada deskripsi rinci untuk kamar ini. Silakan hubungi pengelola untuk mendapatkan informasi tambahan.' }}</p>
                </div>

                {{-- Gallery --}}
                @if ($totalImages > 0)
                    <div class="detail-gallery-card">
                        <h3>Foto Tambahan</h3>
                        <div class="detail-gallery-grid">
                            @foreach ($room->images as $i => $image)
                                @if ($totalImages === 1 || $i < $totalImages - 1)
                                    <div class="detail-gallery-item">
                                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->caption ?: $room->name }}">
                                    </div>
                                @endif
                            @endforeach
                            @if ($totalImages > 1)
                                <div class="detail-gallery-more">
                                    <span class="material-symbols-outlined">add_photo_alternate</span>
                                    <span>Lihat Semua ({{ $totalImages }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Amenities --}}
                <div class="detail-amenities-card">
                    <h3>Fasilitas</h3>

                    @foreach (['room' => 'Di Kamar', 'public' => 'Bersama'] as $type => $groupLabel)
                        @php $facilities = $facilityGroups[$type] ?? collect(); @endphp
                        @if ($facilities->isNotEmpty())
                            <div class="detail-amenities-group">
                                <h4>
                                    <span class="material-symbols-outlined">{{ $type === 'room' ? 'bed' : 'groups' }}</span>
                                    {{ $groupLabel }}
                                </h4>
                                <div class="detail-amenities-list">
                                    @foreach ($facilities as $facility)
                                        @php
                                            $icon = $facilityIconMap[$facility->name] ?? $facility->icon ?? 'check_circle';
                                        @endphp
                                        <div class="detail-amenities-item">
                                            <span class="material-symbols-outlined" style="color:{{ $type === 'room' ? 'var(--ui-accent)' : 'var(--ui-body)' }};">{{ $icon }}</span>
                                            <span>{{ $facility->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($facilityGroups['room']->isEmpty() && $facilityGroups['public']->isEmpty())
                        <p style="color:var(--ui-body);font-size:14px;margin:0;">Belum ada fasilitas yang tercatat.</p>
                    @endif
                </div>

                {{-- CTA --}}
                <div class="detail-cta-card">
                    <h3>Tertarik dengan kamar ini?</h3>
                    <p>Hubungi pengelola {{ $profile['name'] }} untuk menanyakan ketersediaan, fasilitas, dan detail lainnya.</p>
                    <div class="detail-cta-actions">
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="detail-cta-button detail-cta-button-primary">
                            <span class="material-symbols-outlined" style="font-size:18px;">chat</span>
                            Tanya via WhatsApp
                        </a>
                        <a href="{{ route('rooms.index') }}" class="detail-cta-button detail-cta-button-secondary">
                            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                            Lihat kamar lain
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
