@extends('public.layout')

@section('title', 'Daftar Kamar | '.$profile['name'])

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="section-header">
                <p class="eyebrow">Daftar kamar</p>
                <h1 class="headline">Semua kamar di {{ $profile['name'] }}</h1>
                <p class="lead">Lihat seluruh kamar yang tercatat, cek status saat ini, lalu buka detail kamar untuk melihat fasilitas dan menghubungi pengelola.</p>
            </div>

            @if ($rooms->isEmpty())
                <section class="empty-state">
                    <h2>Belum ada kamar</h2>
                    <p>Saat ini belum ada kamar yang ditampilkan. Silakan cek kembali nanti atau hubungi pengelola melalui WhatsApp.</p>
                </section>
            @else
                <div class="room-grid">
                    @foreach ($rooms as $room)
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
                                <div class="button-row" style="justify-content: space-between; margin-bottom: 12px;">
                                    <span class="status-badge status-{{ $room->status }}">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                                    <span class="detail-value">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
                                </div>

                                <h2 class="room-title">{{ $room->name }}</h2>
                                <p class="room-copy">{{ $room->description ?: 'Belum ada deskripsi rinci untuk kamar ini. Buka detail kamar untuk melihat informasi lebih lengkap.' }}</p>

                                <div class="room-card-chips" style="margin: 16px 0;">
                                    <span class="chip">Ukuran {{ $room->size ?: '-' }}</span>
                                    <span class="chip">Lantai {{ $room->floor ?: '-' }}</span>
                                    @foreach ($room->facilities->take(3) as $facility)
                                        <span class="chip">{{ $facility->name }}</span>
                                    @endforeach
                                </div>

                                <div class="detail-actions">
                                    <a href="{{ route('rooms.show', $room) }}" class="button button-primary">Lihat detail</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
