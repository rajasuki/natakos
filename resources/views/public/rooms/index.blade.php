@extends('public.layout')

@section('title', 'Daftar Kamar | '.$profile['name'])

@push('styles')
    <style>
        .filter-panel {
            padding: 24px;
            background: var(--ui-soft);
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            box-shadow: var(--ui-shadow);
            margin-bottom: 20px;
        }

        .filter-form,
        .filter-group,
        .filter-checkbox-group,
        .filter-checkbox-list {
            display: grid;
            gap: 16px;
        }

        .filter-grid,
        .filter-facility-grid {
            display: grid;
            gap: 16px;
        }

        .filter-label,
        .filter-checkbox-title {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .filter-copy,
        .filter-helper,
        .results-copy {
            margin: 0;
            color: var(--ui-body);
            font-size: 14px;
            line-height: 1.6;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            min-height: 48px;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            background: var(--ui-canvas);
            color: var(--ui-ink);
            padding: 14px 16px;
            font: inherit;
        }

        .filter-input:focus,
        .filter-select:focus,
        .filter-input:focus-visible,
        .filter-select:focus-visible {
            outline: none;
            border-color: var(--ui-ink);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.08);
        }

        .filter-checkbox-group {
            padding: 18px;
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 16px;
        }

        .filter-checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            background: var(--ui-softer);
            border: 1px solid var(--ui-border);
            border-radius: 12px;
        }

        .filter-checkbox-item input {
            margin-top: 2px;
        }

        .filter-checkbox-copy {
            display: grid;
            gap: 4px;
        }

        .filter-actions,
        .results-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .results-bar {
            margin-bottom: 20px;
        }

        .results-meta {
            display: grid;
            gap: 8px;
        }

        .results-title {
            margin: 0;
            font-size: 20px;
            line-height: 1.3;
        }

        @media (max-width: 767px) {
            .filter-panel {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (min-width: 768px) {
            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-group-full {
                grid-column: 1 / -1;
            }

            .filter-facility-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

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
                $selectedFacilityIds = $filters['facilities'] ?? [];
            @endphp

            <div class="section-split">
                <div class="section-header section-header-tight">
                    <p class="eyebrow">Daftar kamar</p>
                    <h1 class="headline">Semua kamar di {{ $profile['name'] }}</h1>
                    <p class="lead">Cari kamar berdasarkan nama, harga, status, dan fasilitas agar lebih cepat menemukan kamar yang sesuai kebutuhan.</p>
                </div>

                <div class="section-actions">
                    @foreach ($roomCounts as $label => $total)
                        <span class="chip">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                    @endforeach
                </div>
            </div>

            <section class="filter-panel">
                <form method="GET" action="{{ route('rooms.index') }}" class="filter-form">
                    <div class="filter-grid">
                        <div class="filter-group filter-group-full">
                            <label for="q" class="filter-label">Cari kamar</label>
                            <input id="q" name="q" type="search" value="{{ $filters['q'] }}" class="filter-input" placeholder="Cari nama kamar, ukuran, lantai, atau deskripsi...">
                        </div>

                        <div class="filter-group">
                            <label for="min_price" class="filter-label">Harga minimum</label>
                            <input id="min_price" name="min_price" type="number" min="0" step="1" value="{{ $filters['min_price'] }}" class="filter-input" placeholder="Contoh: 500000">
                        </div>

                        <div class="filter-group">
                            <label for="max_price" class="filter-label">Harga maksimum</label>
                            <input id="max_price" name="max_price" type="number" min="0" step="1" value="{{ $filters['max_price'] }}" class="filter-input" placeholder="Contoh: 1000000">
                        </div>

                        <div class="filter-group filter-group-full">
                            <label for="status" class="filter-label">Status kamar</label>
                            <select id="status" name="status" class="filter-select">
                                <option value="">Semua status</option>
                                @foreach ($roomStatusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group filter-group-full">
                            <div>
                                <p class="filter-label">Filter fasilitas kamar</p>
                                <p class="filter-copy">Pilih satu atau beberapa fasilitas. Hasil akan menampilkan kamar yang memiliki semua fasilitas yang dipilih.</p>
                            </div>

                            <div class="filter-facility-grid">
                                @foreach ($facilityTypeLabels as $type => $label)
                                    @php
                                        $facilities = $facilityGroups[$type] ?? collect();
                                    @endphp

                                    <section class="filter-checkbox-group">
                                        <h2 class="filter-checkbox-title">{{ $label }}</h2>

                                        @if ($facilities->isEmpty())
                                            <p class="filter-helper">Belum ada fasilitas pada kelompok ini.</p>
                                        @else
                                            <div class="filter-checkbox-list">
                                                @foreach ($facilities as $facility)
                                                    <label class="filter-checkbox-item" for="facility_{{ $facility->id }}">
                                                        <input
                                                            id="facility_{{ $facility->id }}"
                                                            type="checkbox"
                                                            name="facilities[]"
                                                            value="{{ $facility->id }}"
                                                            @checked(in_array($facility->id, $selectedFacilityIds, true))
                                                        >

                                                        <span class="filter-checkbox-copy">
                                                            <strong>{{ $facility->name }}</strong>
                                                            <span class="muted">{{ $type === 'room' ? 'Fasilitas di dalam kamar' : 'Fasilitas area bersama' }}</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </section>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="button button-primary">Terapkan Filter</button>
                        <a href="{{ route('rooms.index') }}" class="button button-subtle">Reset Filter</a>
                    </div>
                </form>
            </section>

            <div class="results-bar">
                <div class="results-meta">
                    <h2 class="results-title">Menampilkan {{ number_format($rooms->count(), 0, ',', '.') }} kamar</h2>
                    <p class="results-copy">{{ $hasActiveFilters ? 'Hasil sudah disesuaikan dengan filter yang sedang aktif.' : 'Gunakan filter di atas untuk mempersempit pencarian kamar.' }}</p>
                </div>

                @if ($hasActiveFilters)
                    <div class="section-actions">
                        <span class="chip">Filter aktif</span>
                    </div>
                @endif
            </div>

            @if ($rooms->isEmpty())
                <section class="empty-state">
                    <h2>{{ $hasActiveFilters ? 'Tidak ada kamar yang cocok' : 'Belum ada kamar' }}</h2>
                    <p>
                        {{ $hasActiveFilters
                            ? 'Coba ubah kata kunci atau kombinasi filter harga, status, dan fasilitas agar hasil kamar yang tampil lebih sesuai.'
                            : 'Saat ini belum ada kamar yang ditampilkan. Silakan cek kembali nanti atau hubungi pengelola melalui WhatsApp.' }}
                    </p>

                    <div class="section-actions">
                        @if ($hasActiveFilters)
                            <a href="{{ route('rooms.index') }}" class="button button-primary">Reset Filter</a>
                        @endif

                        <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button {{ $hasActiveFilters ? 'button-secondary' : 'button-primary' }}">Hubungi via WhatsApp</a>
                        <a href="{{ route('home') }}" class="button button-subtle">Kembali ke homepage</a>
                    </div>
                </section>
            @else
                <div class="room-grid">
                    @foreach ($rooms as $room)
                        @php
                            $coverPath = $room->main_image ?: $room->images->first()?->image_path;
                            $roomWhatsappUrl = \App\Support\WhatsappLink::build(
                                $profile['whatsapp_number'],
                                'Halo, saya tertarik dengan '.$room->name.' di '.$kosName.'. Apakah masih tersedia?'
                            );
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
                                    <span class="muted">Hubungi pengelola langsung via WhatsApp atau buka detail kamar untuk melihat informasi lebih lengkap.</span>

                                    <div class="button-row">
                                        <a href="{{ $roomWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="button button-secondary">Tanya via WhatsApp</a>
                                        <a href="{{ route('rooms.show', $room) }}" class="button button-primary">Lihat detail</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
