@extends('public.layout')

@section('title', 'Daftar Kamar | '.$profile['name'])

@push('styles')
    <style>
        /* ── Layout ── */
        .rooms-layout {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 24px;
        }

        @media (min-width: 768px) {
            .rooms-layout {
                flex-direction: row;
                align-items: flex-start;
            }
        }

        /* ── Sidebar ── */
        .rooms-sidebar {
            width: 100%;
            flex-shrink: 0;
        }

        .rooms-sidebar-inner {
            position: sticky;
            top: 100px;
        }

        @media (max-width: 767px) {
            .rooms-sidebar-inner {
                position: static;
            }
        }

        @media (min-width: 768px) {
            .rooms-sidebar {
                width: 272px;
            }
        }

        .filter-card {
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: var(--ui-shadow);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--ui-border);
        }

        .filter-header-icon {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            color: var(--ui-body);
        }

        .filter-header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--ui-body);
            margin: 0;
        }

        .filter-search-input {
            width: 100%;
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            background: var(--ui-canvas);
            color: var(--ui-ink);
            font: inherit;
            font-size: 13px;
            outline: none;
            transition: border-color .2s ease;
        }

        .filter-search-input:focus {
            border-color: var(--ui-ink);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, .04);
        }

        .filter-search-input::placeholder {
            color: var(--ui-body);
        }

        .filter-price-row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .filter-price-input {
            flex: 1;
            height: 32px;
            padding: 0 8px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            background: var(--ui-canvas);
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            transition: border-color .2s ease;
        }

        .filter-price-input:focus {
            border-color: var(--ui-ink);
        }

        .filter-price-input::placeholder {
            color: var(--ui-body);
        }

        .filter-price-sep {
            color: var(--ui-body);
            font-size: 12px;
            flex-shrink: 0;
        }

        .filter-select {
            width: 100%;
            height: 32px;
            padding: 0 24px 0 8px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            background: var(--ui-canvas);
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            transition: border-color .2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%235B7060' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .filter-select:focus {
            border-color: var(--ui-ink);
        }

        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--ui-ink);
            transition: color .2s ease;
            user-select: none;
        }

        .filter-checkbox:hover {
            color: var(--ui-accent);
        }

        .filter-checkbox input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid var(--ui-border);
            appearance: none;
            -webkit-appearance: none;
            background: var(--ui-canvas);
            cursor: pointer;
            flex-shrink: 0;
            margin: 0;
            transition: all .2s ease;
        }

        .filter-checkbox input:checked {
            background: var(--ui-accent);
            border-color: var(--ui-accent);
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 01-1.06 0L2.22 9.28a.75.75 0 011.06-1.06L6 10.94l6.72-6.72a.75.75 0 011.06 0z'/%3E%3C/svg%3E");
            background-size: 12px;
            background-position: center;
            background-repeat: no-repeat;
        }

        .filter-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-top: 2px;
        }

        .filter-actions .button {
            min-height: 36px;
            font-size: 13px;
        }

        /* ── Toolbar ── */
        .rooms-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .rooms-toolbar-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .rooms-toolbar-meta {
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--ui-body);
        }

        .rooms-sort {
            height: 32px;
            padding: 0 28px 0 10px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            background: var(--ui-canvas);
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            transition: border-color .2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%235B7060' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .rooms-sort:focus {
            border-color: var(--ui-ink);
        }

        /* ── Active filter chips ── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
            align-items: center;
        }

        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: var(--ui-soft);
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ui-body);
        }

        .active-filter-chip-remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 15px;
            line-height: 1;
            color: var(--ui-body);
            transition: color .2s ease;
            text-decoration: none;
        }

        .active-filter-chip-remove:hover {
            color: var(--ui-ink);
        }

        .active-filter-reset {
            font-size: 13px;
            font-weight: 600;
            color: var(--ui-accent);
            cursor: pointer;
            border: 0;
            background: none;
            padding: 4px 8px;
            text-decoration: none;
        }

        .active-filter-reset:hover {
            color: var(--ui-accent-hover);
        }

        /* ── Room grid ── */
        .room-grid-new {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
        }

        @media (min-width: 640px) {
            .room-grid-new {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .room-grid-new {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ── Room card ── */
        .room-card-new {
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .room-card-new:hover {
            transform: translateY(-3px);
            box-shadow: var(--ui-shadow-strong);
        }

        .room-card-img-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16 / 9;
            background: var(--ui-soft);
        }

        .room-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .room-card-new:hover .room-card-img {
            transform: scale(1.05);
        }

        .room-card-img-placeholder {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            color: var(--ui-body);
            font-size: 13px;
            text-align: center;
            padding: 16px;
        }

        .room-card-status {
            position: absolute;
            top: 8px;
            left: 8px;
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            line-height: 1.3;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(4px);
            color: var(--ui-ink);
        }

        .room-card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .room-card-title {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
        }

        .room-card-desc {
            display: none;
        }

        .room-card-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .room-card-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 999px;
            background: var(--ui-soft);
            color: var(--ui-body);
            font-size: 10px;
            font-weight: 600;
            line-height: 1.4;
        }

        .room-card-divider {
            height: 1px;
            background: var(--ui-border);
            margin: 2px 0;
        }

        .room-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: auto;
        }

        .room-card-price {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--ui-ink);
        }

        .room-card-actions {
            display: flex;
            gap: 6px;
        }

        .room-card-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            transition: all .2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .room-card-action-primary {
            background: var(--ui-accent);
            color: #fff;
            border: 0;
        }

        .room-card-action-primary:hover {
            background: var(--ui-accent-hover);
            color: #fff;
        }

        .room-card-action-secondary {
            background: transparent;
            color: var(--ui-body);
            border: 1px solid var(--ui-border);
        }

        .room-card-action-secondary:hover {
            border-color: var(--ui-ink);
            color: var(--ui-ink);
        }
    </style>
@endpush

@section('content')
    <section class="page-section">
        <div class="site-shell">
            @php
                $selectedFacilityIds = $filters['facilities'] ?? [];
            @endphp

            <div class="section-split">
                <div class="section-header section-header-tight">
                    <p class="eyebrow">Daftar kamar</p>
                    <h1 class="headline">Semua kamar di {{ $profile['name'] }}</h1>
                    <p class="lead">Cari kamar berdasarkan nama, harga, status, dan fasilitas agar lebih cepat menemukan kamar yang sesuai kebutuhan.</p>
                </div>
            </div>

            <div class="rooms-layout">
                <aside class="rooms-sidebar">
                    <div class="rooms-sidebar-inner">
                        <form method="GET" action="{{ route('rooms.index') }}" class="filter-card">
                            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                            <div class="filter-header">
                                <svg class="filter-header-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <h2>Filter</h2>
                            </div>

                            <div class="filter-group">
                                <label for="filter-q" class="filter-group-title">Cari kamar</label>
                                <input id="filter-q" name="q" type="search" value="{{ $filters['q'] }}" class="filter-search-input" placeholder="Nama kamar...">
                            </div>

                            <div class="filter-group">
                                <h3 class="filter-group-title">Rentang harga</h3>
                                <div class="filter-price-row">
                                    <input name="min_price" type="number" min="0" step="1" value="{{ $filters['min_price'] }}" class="filter-price-input" placeholder="Min">
                                    <span class="filter-price-sep">–</span>
                                    <input name="max_price" type="number" min="0" step="1" value="{{ $filters['max_price'] }}" class="filter-price-input" placeholder="Max">
                                </div>
                            </div>

                            <div class="filter-group">
                                <h3 class="filter-group-title">Status kamar</h3>
                                <select name="status" class="filter-select">
                                    <option value="">Semua status</option>
                                    @foreach ($roomStatusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @foreach ($facilityTypeLabels as $type => $typeLabel)
                                @php $groupedFacilities = $facilityGroups[$type] ?? collect(); @endphp
                                @if ($groupedFacilities->isNotEmpty())
                                    <div class="filter-group">
                                        <h3 class="filter-group-title">{{ $typeLabel }}</h3>
                                        @foreach ($groupedFacilities as $facility)
                                            <label class="filter-checkbox">
                                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" @checked(in_array($facility->id, $selectedFacilityIds, true))>
                                                {{ $facility->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach

                            <div class="filter-actions">
                                <button type="submit" class="button button-primary">Terapkan filter</button>
                                <a href="{{ route('rooms.index') }}" class="button button-subtle">Reset</a>
                            </div>
                        </form>
                    </div>
                </aside>

                <main class="rooms-content">
                    <div class="rooms-toolbar">
                        <div>
                            <h2 class="rooms-toolbar-title">Menampilkan {{ number_format($rooms->count(), 0, ',', '.') }} kamar</h2>
                            @if ($hasActiveFilters)
                                <p class="rooms-toolbar-meta">Hasil sudah disesuaikan dengan filter yang aktif.</p>
                            @endif
                        </div>
                        <select class="rooms-sort" onchange="var u=new URL(window.location);u.searchParams.set('sort',this.value||'');window.location=u.toString();">
                            <option value="">Default</option>
                            <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Harga terendah</option>
                            <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Harga tertinggi</option>
                        </select>
                    </div>

                    @if ($hasActiveFilters)
                        <div class="active-filters">
                            @if ($filters['q'])
                                <span class="active-filter-chip">
                                    {{ $filters['q'] }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('q');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @if ($filters['min_price'] !== null || $filters['max_price'] !== null)
                                <span class="active-filter-chip">
                                    Rp{{ number_format((int) ($filters['min_price'] ?: 0), 0, ',', '.') }}–Rp{{ number_format((int) ($filters['max_price'] ?: 999999999), 0, ',', '.') }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('min_price');u.searchParams.delete('max_price');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @if ($filters['status'])
                                <span class="active-filter-chip">
                                    {{ $roomStatusLabels[$filters['status']] }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('status');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @foreach ($selectedFacilityIds as $fid)
                                @php $fac = \App\Models\Facility::find($fid); @endphp
                                @if ($fac)
                                    <span class="active-filter-chip">
                                        {{ $fac->name }}
                                        <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);var f=u.searchParams.getAll('facilities');u.searchParams.delete('facilities');f.filter(function(v){return v!=='{{ $fid }}'}).forEach(function(v){u.searchParams.append('facilities',v)});window.location=u.toString();return false;">×</a>
                                    </span>
                                @endif
                            @endforeach
                            <a href="{{ route('rooms.index') }}" class="active-filter-reset">Hapus semua</a>
                        </div>
                    @endif

                    @if ($rooms->isEmpty())
                        <div class="empty-state">
                            <h2>{{ $hasActiveFilters ? 'Tidak ada kamar yang cocok' : 'Belum ada kamar' }}</h2>
                            <p>
                                {{ $hasActiveFilters
                                    ? 'Coba ubah kata kunci atau kombinasi filter harga, status, dan fasilitas agar hasil kamar yang tampil lebih sesuai.'
                                    : 'Saat ini belum ada kamar yang ditampilkan. Silakan cek kembali nanti atau hubungi pengelola melalui WhatsApp.' }}
                            </p>

                            <div class="section-actions" style="justify-content:center;">
                                @if ($hasActiveFilters)
                                    <a href="{{ route('rooms.index') }}" class="button button-primary">Reset filter</a>
                                @endif
                                <a href="{{ $profile['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="button {{ $hasActiveFilters ? 'button-secondary' : 'button-primary' }}">Hubungi via WhatsApp</a>
                                <a href="{{ route('home') }}" class="button button-subtle">Kembali ke homepage</a>
                            </div>
                        </div>
                    @else
                        <div class="room-grid-new">
                            @foreach ($rooms as $room)
                                @php
                                    $coverPath = $room->main_image ?: $room->images->first()?->image_path;
                                    $roomWhatsappUrl = \App\Support\WhatsappLink::build(
                                        $profile['whatsapp_number'],
                                        'Halo, saya tertarik dengan '.$room->name.' di NATAKOS. Apakah masih tersedia?'
                                    );
                                @endphp
                                <article class="room-card-new">
                                    <div class="room-card-img-wrap">
                                        @if ($coverPath)
                                            <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="room-card-img" loading="lazy">
                                        @else
                                            <div class="room-card-img-placeholder">Foto belum tersedia</div>
                                        @endif
                                        <span class="room-card-status">{{ $roomStatusLabels[$room->status] ?? $room->status }}</span>
                                    </div>

                                    <div class="room-card-body">
                                        <h3 class="room-card-title">{{ $room->name }}</h3>

                                        <div class="room-card-chips">
                                            @if ($room->size)
                                                <span class="room-card-chip">{{ $room->size }} m²</span>
                                            @endif
                                            @if ($room->floor)
                                                <span class="room-card-chip">Lt {{ $room->floor }}</span>
                                            @endif
                                            @foreach ($room->facilities->take(3) as $facility)
                                                <span class="room-card-chip">{{ $facility->name }}</span>
                                            @endforeach
                                        </div>

                                        <div class="room-card-divider"></div>

                                        <div class="room-card-footer">
                                            <span class="room-card-price">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                                            <div class="room-card-actions">
                                                <a href="{{ $roomWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="room-card-action room-card-action-secondary">WA</a>
                                                <a href="{{ route('rooms.show', $room) }}" class="room-card-action room-card-action-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </section>
@endsection
