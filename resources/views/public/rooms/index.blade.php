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

        .rooms-content {
            flex: 1;
            min-width: 0;
        }

        /* ── Filter card ── */
        .filter-card {
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--ui-border);
        }

        .filter-header span {
            font-size: 14px;
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
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            background: #fff;
            color: var(--ui-ink);
            font: inherit;
            font-size: 13px;
            outline: none;
            transition: border-color .2s ease;
        }

        .filter-search-input:focus {
            border-color: var(--ui-accent);
            box-shadow: 0 0 0 3px rgba(74,124,89,.12);
        }

        .filter-search-input::placeholder {
            color: var(--ui-body);
        }

        .filter-price-row {
            display: flex;
            gap: 8px;
            align-items: center;
            min-width: 0;
        }

        .filter-price-input {
            flex: 1;
            min-width: 0;
            width: 100%;
            height: 36px;
            padding: 0 10px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            background: #fff;
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            transition: border-color .2s ease;
        }

        .filter-price-input:focus {
            border-color: var(--ui-accent);
            box-shadow: 0 0 0 3px rgba(74,124,89,.1);
        }

        .filter-price-input::placeholder {
            color: var(--ui-body);
        }

        .filter-price-sep {
            color: var(--ui-body);
            font-size: 13px;
            flex-shrink: 0;
        }

        .filter-select {
            width: 100%;
            height: 36px;
            padding: 0 28px 0 10px;
            border: 1px solid var(--ui-border);
            border-radius: 6px;
            background: #fff;
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            transition: border-color .2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235B7060' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .filter-select:focus {
            border-color: var(--ui-accent);
        }

        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
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
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid var(--ui-border);
            appearance: none;
            -webkit-appearance: none;
            background: #fff;
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
            gap: 8px;
            padding-top: 4px;
        }

        .filter-actions .button {
            min-height: 40px;
            font-size: 13px;
        }

        /* ── Toolbar ── */
        .rooms-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--ui-border);
            margin-bottom: 20px;
        }

        .rooms-toolbar-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .rooms-toolbar-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .rooms-toolbar-meta {
            margin: 0;
            font-size: 12px;
            color: var(--ui-body);
        }

        .rooms-sort {
            height: 36px;
            padding: 0 30px 0 12px;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            background: #fff;
            color: var(--ui-ink);
            font: inherit;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            transition: border-color .2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235B7060' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .rooms-sort:focus {
            border-color: var(--ui-accent);
        }

        /* ── Active filter chips ── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
            align-items: center;
        }

        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--ui-soft);
            border-radius: 999px;
            font-size: 12px;
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
            font-size: 16px;
            line-height: 1;
            color: var(--ui-body);
            transition: color .2s ease;
            text-decoration: none;
        }

        .active-filter-chip-remove:hover {
            color: var(--ui-ink);
        }

        .active-filter-reset {
            font-size: 12px;
            font-weight: 600;
            color: var(--ui-accent);
            cursor: pointer;
            border: 0;
            background: none;
            padding: 6px 8px;
            text-decoration: none;
        }

        .active-filter-reset:hover {
            color: var(--ui-accent-hover);
            text-decoration: underline;
        }

        /* ── Room grid ── */
        .room-grid-new {
            display: grid;
            gap: 20px;
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
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .room-card-new:hover {
            transform: translateY(-4px);
            box-shadow: var(--ui-shadow-strong);
        }

        .room-card-img-wrap {
            position: relative;
            overflow: hidden;
            height: 192px;
            background: var(--ui-soft);
        }

        .room-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .room-card-new:hover .room-card-img {
            transform: scale(1.06);
        }

        .room-card-img-placeholder {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            color: var(--ui-body);
            font-size: 13px;
            text-align: center;
            padding: 24px;
        }

        /* Status badges */
        .room-card-status {
            position: absolute;
            top: 10px;
            left: 10px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.3;
            backdrop-filter: blur(4px);
        }

        .room-card-status-available {
            background: rgba(209, 250, 229, .92);
            color: #065f46;
        }

        .room-card-status-occupied {
            background: rgba(255, 218, 214, .92);
            color: #b71c1c;
        }

        .room-card-status-maintenance {
            background: rgba(254, 243, 199, .92);
            color: #92400e;
        }

        .room-card-status .material-symbols-outlined {
            font-size: 14px;
        }

        /* Card body */
        .room-card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .room-card-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.3;
        }

        .room-card-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .room-card-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 6px;
            background: var(--ui-soft);
            color: var(--ui-body);
            font-size: 11px;
            font-weight: 600;
            line-height: 1.4;
        }

        .room-card-chip .material-symbols-outlined {
            font-size: 14px;
        }

        /* Facility grid */
        .room-card-facilities {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin: 0;
        }

        .room-card-facility {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ui-body);
            line-height: 1.3;
        }

        .room-card-facility .material-symbols-outlined {
            font-size: 14px;
            color: var(--ui-accent);
            flex-shrink: 0;
        }

        .room-card-facility-more {
            font-size: 11px;
            font-weight: 600;
            color: var(--ui-body);
            line-height: 1.3;
        }

        .room-card-divider {
            height: 1px;
            background: var(--ui-border);
            border: 0;
            margin: 0;
        }

        /* Price */
        .room-card-price-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--ui-body);
            margin: 0 0 2px;
        }

        .room-card-price {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--ui-ink);
        }

        .room-card-price-period {
            font-size: 13px;
            font-weight: 600;
            color: var(--ui-body);
        }

        /* Actions */
        .room-card-footer {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
            padding-top: 4px;
        }

        .room-card-action-wa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--ui-accent-soft);
            color: var(--ui-accent);
            border: 0;
            cursor: pointer;
            transition: all .2s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .room-card-action-wa:hover {
            background: var(--ui-accent);
            color: #fff;
        }

        .room-card-action-wa:active {
            transform: scale(.95);
        }

        .room-card-action-wa .material-symbols-outlined {
            font-size: 20px;
        }

        .room-card-action-wa-disabled {
            opacity: .4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .room-card-action-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 10px;
            background: var(--ui-accent);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            transition: all .2s ease;
            text-decoration: none;
        }

        .room-card-action-detail:hover {
            background: var(--ui-accent-hover);
        }

        .room-card-action-detail:active {
            transform: scale(.97);
        }

        /* ── Pagination ── */
        .rooms-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--ui-border);
        }

        .pagination-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid var(--ui-border);
            border-radius: 8px;
            background: var(--ui-canvas);
            color: var(--ui-body);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
            text-decoration: none;
        }

        .pagination-link:hover {
            border-color: var(--ui-accent);
            color: var(--ui-accent);
            background: var(--ui-accent-soft);
        }

        .pagination-link-active {
            background: var(--ui-accent);
            border-color: var(--ui-accent);
            color: #fff;
            cursor: default;
        }

        .pagination-link-active:hover {
            background: var(--ui-accent);
            border-color: var(--ui-accent);
            color: #fff;
        }

        .pagination-link-disabled {
            opacity: .35;
            cursor: not-allowed;
            pointer-events: none;
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
                        <form method="GET" action="{{ route('rooms.index') }}" class="filter-card" onsubmit="document.querySelectorAll('[data-price-format]').forEach(function(e){e.value=e.value.replaceAll('.','');});">
                            <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                            <div class="filter-header">
                                <span class="material-symbols-outlined" style="font-size:18px;">filter_list</span>
                                <h2>Filter</h2>
                            </div>

                            <div class="filter-group">
                                <label for="filter-q" class="filter-group-title">Cari kamar</label>
                                <input id="filter-q" name="q" type="search" value="{{ $filters['q'] }}" class="filter-search-input" placeholder="Nama kamar...">
                            </div>

                            <div class="filter-group">
                                <h3 class="filter-group-title">Rentang harga</h3>
                                <div class="filter-price-row">
                                    <input name="min_price" type="text" inputmode="numeric" value="{{ $filters['min_price'] !== null ? number_format((int) $filters['min_price'], 0, ',', '.') : '' }}" class="filter-price-input" placeholder="Min" data-price-format>
                                    <span class="filter-price-sep">–</span>
                                    <input name="max_price" type="text" inputmode="numeric" value="{{ $filters['max_price'] !== null ? number_format((int) $filters['max_price'], 0, ',', '.') : '' }}" class="filter-price-input" placeholder="Max" data-price-format>
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
                        <div class="rooms-toolbar-left">
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
                                    <span class="material-symbols-outlined" style="font-size:14px;">search</span>
                                    {{ $filters['q'] }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('q');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @if ($filters['min_price'] !== null || $filters['max_price'] !== null)
                                <span class="active-filter-chip">
                                    <span class="material-symbols-outlined" style="font-size:14px;">attach_money</span>
                                    Rp{{ number_format((int) ($filters['min_price'] ?: 0), 0, ',', '.') }}–Rp{{ number_format((int) ($filters['max_price'] ?: 999999999), 0, ',', '.') }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('min_price');u.searchParams.delete('max_price');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @if ($filters['status'])
                                <span class="active-filter-chip">
                                    <span class="material-symbols-outlined" style="font-size:14px;">lens</span>
                                    {{ $roomStatusLabels[$filters['status']] }}
                                    <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);u.searchParams.delete('status');window.location=u.toString();return false;">×</a>
                                </span>
                            @endif
                            @foreach ($selectedFacilityIds as $fid)
                                @php $fac = \App\Models\Facility::find($fid); @endphp
                                @if ($fac)
                                    <span class="active-filter-chip">
                                        <span class="material-symbols-outlined" style="font-size:14px;">check_circle</span>
                                        {{ $fac->name }}
                                        <a href="#" class="active-filter-chip-remove" onclick="var u=new URL(window.location);var f=u.searchParams.getAll('facilities');u.searchParams.delete('facilities');f.filter(function(v){return v!=='{{ $fid }}'}).forEach(function(v){u.searchParams.append('facilities',v)});window.location=u.toString();return false;">×</a>
                                    </span>
                                @endif
                            @endforeach
                            <a href="{{ route('rooms.index') }}" class="active-filter-reset">Hapus semua</a>
                        </div>
                    @endif

                    @php
                        $facilityIconMap = [
                            'ac' => 'ac_unit',
                            'kipas angin' => 'mode_fan',
                            'lemari' => 'inventory_2',
                            'tv' => 'tv',
                            'wi-fi' => 'wifi',
                            'wifi' => 'wifi',
                            'kamar mandi dalam' => 'shower',
                            'kamar mandi luar' => 'wc',
                            'kasur' => 'king_bed',
                            'meja' => 'table_restaurant',
                            'kursi' => 'chair',
                            'cctv' => 'videocam',
                            'parkir' => 'local_parking',
                            'dapur' => 'countertops',
                            'air' => 'water_drop',
                            'listrik' => 'bolt',
                            'tamasy' => 'nest_eco',
                            'taman' => 'nest_eco',
                        ];

                        function facilityShortLabel($name) {
                            $lower = strtolower($name);
                            if (str_contains($lower, 'kamar mandi dalam')) return 'KM Dalam';
                            if (str_contains($lower, 'kamar mandi luar')) return 'KM Luar';
                            if (str_contains($lower, 'kipas angin')) return 'Kipas';
                            if (str_contains($lower, 'parkir')) return 'Parkir';
                            if (str_contains($lower, 'tamasy')) return 'Tamasy';
                            return $name;
                        }
                    @endphp

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
                                        'Halo, saya tertarik dengan '.$room->name.' di IchiKOS. Apakah masih tersedia?'
                                    );
                                    $isOccupied = $room->status === 'occupied';
                                    $isMaintenance = $room->status === 'maintenance';
                                @endphp
                                <article class="room-card-new">
                                    <div class="room-card-img-wrap">
                                        @if ($coverPath)
                                            <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $room->name }}" class="room-card-img" loading="lazy">
                                        @else
                                            <div class="room-card-img-placeholder">
                                                <span class="material-symbols-outlined" style="font-size:32px;color:var(--ui-border);">image</span>
                                            </div>
                                        @endif

                                        @php
                                            $statusIcon = match($room->status) {
                                                'available' => 'check_circle',
                                                'occupied' => 'person',
                                                default => 'build',
                                            };
                                            $statusClass = match($room->status) {
                                                'available' => 'room-card-status-available',
                                                'occupied' => 'room-card-status-occupied',
                                                default => 'room-card-status-maintenance',
                                            };
                                        @endphp
                                        <span class="room-card-status {{ $statusClass }}">
                                            <span class="material-symbols-outlined">{{ $statusIcon }}</span>
                                            {{ $roomStatusLabels[$room->status] ?? $room->status }}
                                        </span>
                                    </div>

                                    <div class="room-card-body">
                                        <h3 class="room-card-title">{{ $room->name }}</h3>

                                        <div class="room-card-chips">
                                            @if ($room->size)
                                                <span class="room-card-chip">
                                                    <span class="material-symbols-outlined">aspect_ratio</span>
                                                    {{ $room->size }}
                                                </span>
                                            @endif
                                            @if ($room->floor)
                                                <span class="room-card-chip">
                                                    <span class="material-symbols-outlined">layers</span>
                                                    Lt. {{ $room->floor }}
                                                </span>
                                            @endif
                                        </div>

                                        @php
                                            $roomFacilities = $room->facilities->where('type', 'room');
                                            $displayFacilities = $roomFacilities->take(3);
                                            $extraCount = $roomFacilities->count() - 3;
                                        @endphp
                                        @if ($displayFacilities->isNotEmpty())
                                            <div class="room-card-facilities">
                                                @foreach ($displayFacilities as $facility)
                                                    @php
                                                        $fLower = strtolower($facility->name);
                                                        $icon = $facilityIconMap[$fLower] ?? $facilityIconMap[explode(' ', $fLower)[0]] ?? 'check_circle';
                                                    @endphp
                                                    <span class="room-card-facility">
                                                        <span class="material-symbols-outlined">{{ $icon }}</span>
                                                        {{ facilityShortLabel($facility->name) }}
                                                    </span>
                                                @endforeach
                                                @if ($extraCount > 0)
                                                    <span class="room-card-facility room-card-facility-more">
                                                        +{{ $extraCount }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <hr class="room-card-divider">

                                        <div>
                                            <p class="room-card-price-label">Harga Sewa</p>
                                            <span class="room-card-price">{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                                            <span class="room-card-price-period">/bln</span>
                                        </div>

                                        <div class="room-card-footer">
                                            <a href="{{ $isOccupied ? '#' : $roomWhatsappUrl }}"
                                               target="{{ $isOccupied ? '_self' : '_blank' }}"
                                               rel="{{ $isOccupied ? '' : 'noopener noreferrer' }}"
                                               class="room-card-action-wa {{ $isOccupied ? 'room-card-action-wa-disabled' : '' }}"
                                               @disabled($isOccupied)
                                               title="{{ $isOccupied ? 'Kamar sudah terisi' : 'Hubungi via WhatsApp' }}">
                                                <span class="material-symbols-outlined">chat</span>
                                            </a>
                                            <a href="{{ route('rooms.show', $room) }}" class="room-card-action-detail">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{ $rooms->links() }}
                    @endif
                </main>
            </div>
        </div>
    </section>
@endsection
