@extends('public.layout')

@section('title', 'Daftar Kamar | '.$profile['name'])

@section('content')
    <section class="page-section">
        <div class="site-shell">
            @php
                $roomCounts = [
                    'Total' => $rooms->count(),
                    'Tersedia' => $rooms->where('status', 'available')->count(),
                    'Terisi' => $rooms->where('status', 'occupied')->count(),
                    'Perbaikan' => $rooms->where('status', 'maintenance')->count(),
                ];
            @endphp

            <div class="section-split">
                <div class="section-header section-header-tight">
                    <p class="eyebrow">Daftar kamar</p>
                    <h1 class="headline">Semua kamar di {{ $profile['name'] }}</h1>
                    <p class="lead">Lihat seluruh kamar yang tercatat, cek status saat ini, lalu buka detail kamar untuk melihat fasilitas dan menghubungi pengelola.</p>
                </div>

                <div class="section-actions">
                    @foreach ($roomCounts as $label => $total)
                        <span class="chip">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                    @endforeach
                </div>
            </div>

            @if ($rooms->isEmpty())
                <section class="empty-state">
                    <h2>Belum ada kamar</h2>
                    <p>Saat ini belum ada kamar yang ditampilkan. Silakan cek kembali nanti atau hubungi pengelola melalui WhatsApp.</p>

                    <div class="section-actions">
                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button button-primary">Hubungi via WhatsApp</a>
                        <a href="{{ route('home') }}" class="button button-subtle">Kembali ke homepage</a>
                    </div>
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
                                <div class="room-card-head">
                                    <div class="room-card-topbar">
                                        <span class="status-badge status-{{ $room->status }}">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                                        <span class="detail-value">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                                    </div>

                                    <div>
                                        <h2 class="room-title">{{ $room->name }}</h2>
                                        <p class="room-copy">{{ $room->description ?: 'Belum ada deskripsi rinci untuk kamar ini. Buka detail kamar untuk melihat informasi lebih lengkap.' }}</p>
                                    </div>
                                </div>

                                <div class="room-card-chips">
                                    <span class="chip">Ukuran {{ $room->size ?: '-' }}</span>
                                    <span class="chip">Lantai {{ $room->floor ?: '-' }}</span>
                                    @foreach ($room->facilities->take(3) as $facility)
                                        <span class="chip">{{ $facility->name }}</span>
                                    @endforeach
                                </div>

                                <div class="room-card-footer">
                                    <span class="muted">Klik detail untuk melihat deskripsi lengkap dan tombol WhatsApp khusus kamar ini.</span>
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
