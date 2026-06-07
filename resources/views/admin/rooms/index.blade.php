@extends('admin.layout')

@section('title', 'Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Kelola Kamar')
@section('page_description', 'Manajemen inventaris dan status kamar kos.')

@section('page_actions')
    <a href="{{ route('admin.rooms.create') }}" class="button button-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <span class="material-symbols-outlined" style="font-size:20px;">add</span>
        Tambah Kamar
    </a>
@endsection

@push('styles')
<style>
    .material-symbols-outlined.fill {
        font-variation-settings: 'FILL' 1;
    }

    .room-index-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (min-width: 1024px) {
        .room-index-stats {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .room-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 18px;
    }

    .room-stat-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .room-stat-header .material-symbols-outlined {
        font-size: 20px;
    }

    .room-stat-header h3 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--ui-body);
    }

    .room-stat-value {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: var(--ui-ink);
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .room-stat-color-primary { color: var(--ui-accent); }
    .room-stat-color-success { color: #059669; }
    .room-stat-color-warning { color: #d97706; }

    /* ── Main card ── */
    .room-index-table-wrap {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .room-index-filter {
        padding: 16px 20px;
        border-bottom: 1px solid var(--ui-border);
        background: var(--gray-50);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (min-width: 768px) {
        .room-index-filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .room-index-filter-left {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .room-index-filter-left {
            flex-direction: row;
            align-items: center;
            width: auto;
        }
    }

    .room-index-filter-left .field {
        margin: 0;
    }

    .room-search-wrap {
        position: relative;
        width: 100%;
    }

    @media (min-width: 640px) {
        .room-search-wrap {
            width: 240px;
        }
    }

    .room-search-wrap .material-symbols-outlined {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: var(--ui-body);
        pointer-events: none;
    }

    .room-search-wrap input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
    }

    .room-search-wrap input:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .room-status-select {
        padding: 8px 12px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
        min-width: 160px;
    }

    .room-status-select:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .room-index-filter-right {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .room-index-filter-right {
            width: auto;
        }
    }

    .room-index-filter-right .button {
        flex: 1;
    }

    @media (min-width: 768px) {
        .room-index-filter-right .button {
            flex: none;
        }
    }

    .btn-filter-apply {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
        border: 1px solid transparent;
        font-weight: 600;
    }

    .btn-filter-apply:hover {
        background: var(--ui-accent);
        color: #fff;
    }

    /* ── Table ── */
    .room-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .room-table thead {
        background: var(--gray-50);
    }

    .room-table th {
        padding: 11px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
        text-align: left;
        border-bottom: 1px solid var(--ui-border);
    }

    .room-table th:last-child {
        text-align: right;
    }

    .room-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ui-ink);
        border-bottom: 1px solid var(--ui-border);
        vertical-align: middle;
    }

    .room-table tbody tr:last-child td {
        border-bottom: none;
    }

    .room-table tbody tr {
        transition: background .1s;
    }

    .room-table tbody tr:hover {
        background: var(--ui-canvas);
    }

    .room-table-photo {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--ui-border);
        background: var(--ui-soft);
        flex-shrink: 0;
    }

    .room-table-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .room-table-photo-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        border: 1px solid var(--ui-border);
        background: var(--ui-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ui-body);
    }

    .room-table-photo-placeholder .material-symbols-outlined {
        font-size: 18px;
        opacity: .4;
    }

    .room-table-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        margin: 0 0 2px;
    }

    .room-table-slug {
        font-size: 12px;
        color: var(--ui-body);
    }

    .room-table-price {
        font-weight: 600;
        white-space: nowrap;
    }

    .room-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
    }

    .room-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .room-badge-available {
        background: #d1fae5;
        color: #065f46;
    }

    .room-badge-available .room-badge-dot {
        background: #059669;
    }

    .room-badge-occupied {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
    }

    .room-badge-occupied .room-badge-dot {
        background: var(--ui-accent);
    }

    .room-badge-maintenance {
        background: #fef3c7;
        color: #92400e;
    }

    .room-badge-maintenance .room-badge-dot {
        background: #d97706;
    }

    .room-table-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
    }

    .room-table-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--ui-body);
        cursor: pointer;
        transition: background .15s, color .15s;
    }

    .room-table-btn .material-symbols-outlined {
        font-size: 18px;
    }

    .room-table-btn:hover {
        background: var(--ui-soft);
    }

    .room-table-btn-edit:hover {
        color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    .room-table-btn-delete:hover {
        color: #dc2626;
        background: #fee2e2;
    }

    /* ── Pagination ── */
    .room-index-pagination {
        padding: 14px 20px;
        border-top: 1px solid var(--ui-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 13px;
        color: var(--ui-body);
    }

    .room-pagination-arrows {
        display: flex;
        gap: 4px;
    }

    .room-pagination-arrows a,
    .room-pagination-arrows span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--ui-border);
        color: var(--ui-body);
        transition: background .15s, color .15s;
    }

    .room-pagination-arrows a:hover {
        background: var(--ui-soft);
        color: var(--ui-ink);
    }

    .room-pagination-arrows span[disabled],
    .room-pagination-arrows span.disabled {
        opacity: .4;
        cursor: default;
    }

    .room-table td[data-label] {
        display: table-cell;
    }

    @media (max-width: 767px) {
        .room-table thead { display: none; }
        .room-table,
        .room-table tbody,
        .room-table tr,
        .room-table td { display: block; }
        .room-table tr { padding: 12px 16px; border-bottom: 1px solid var(--ui-border); }
        .room-table tr:last-child { border-bottom: none; }
        .room-table td {
            padding: 4px 0;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .room-table td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ui-body);
            min-width: 80px;
            flex-shrink: 0;
        }
        .room-table td:first-child { padding-top: 0; }
        .room-table td:last-child { padding-bottom: 0; }
        .room-table td[data-label="Foto"]::before { display: none; }
        .room-table td[data-label="Aksi"] { justify-content: flex-start; }
        .room-table td[data-label="Aksi"]::before { content: 'Aksi'; }
        .room-table-actions { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
    @if ($rooms->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada kamar yang cocok' : 'Belum ada kamar' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat kamar lain yang terdaftar di IchiKOS.' : 'Mulai dengan menambahkan kamar pertama agar admin dapat mengelola harga, status, dan foto utama kamar dari dashboard ini.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar sekarang</a>
                @endif
            </div>
        </section>
    @else
        {{-- Stats Cards --}}
        <section class="room-index-stats">
            @foreach ([
                ['label' => 'Total Kamar', 'icon' => 'bed', 'count' => $roomCounts['Total'] ?? 0, 'color' => 'room-stat-color-primary'],
                ['label' => 'Tersedia', 'icon' => 'check_circle', 'count' => $roomCounts['Tersedia'] ?? 0, 'color' => 'room-stat-color-success'],
                ['label' => 'Terisi', 'icon' => 'group', 'count' => $roomCounts['Terisi'] ?? 0, 'color' => 'room-stat-color-primary'],
                ['label' => 'Perbaikan', 'icon' => 'build', 'count' => $roomCounts['Perbaikan'] ?? 0, 'color' => 'room-stat-color-warning'],
            ] as $stat)
                <div class="room-stat-card">
                    <div class="room-stat-header">
                        <span class="material-symbols-outlined fill {{ $stat['color'] }}">{{ $stat['icon'] }}</span>
                        <h3>{{ $stat['label'] }}</h3>
                    </div>
                    <p class="room-stat-value">{{ $stat['count'] }}</p>
                </div>
            @endforeach
        </section>

        {{-- Main Table Card --}}
        <div class="room-index-table-wrap">
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.rooms.index') }}" class="room-index-filter">
                <div class="room-index-filter-left">
                    <div class="room-search-wrap">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari nama kamar, slug...">
                    </div>
                    <select name="status" class="room-status-select">
                        <option value="">Semua Status</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="room-index-filter-right">
                    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button btn-filter-apply">Terapkan Filter</button>
                    <a href="{{ route('admin.rooms.export', request()->query()) }}" class="button button-subtle" title="Export PDF">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                    </a>
                </div>
            </form>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table class="room-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Kamar</th>
                            <th>Harga / Bulan</th>
                            <th>Kapasitas</th>
                            <th>Ukuran</th>
                            <th>Lantai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr>
                                <td data-label="Foto">
                                    @if ($room->main_image)
                                        <div class="room-table-photo">
                                            <img src="{{ asset('storage/'.$room->main_image) }}" alt="{{ $room->name }}">
                                        </div>
                                    @else
                                        <div class="room-table-photo-placeholder">
                                            <span class="material-symbols-outlined">image</span>
                                        </div>
                                    @endif
                                </td>
                                <td data-label="Kamar">
                                    <div class="room-table-name">{{ $room->name }}</div>
                                    <div class="room-table-slug">/{{ $room->slug }}</div>
                                </td>
                                <td data-label="Harga" class="room-table-price">{{ \App\Support\UiFormatter::currency($room->price) }}</td>
                                <td data-label="Kapasitas">{{ $room->capacity }} org</td>
                                <td data-label="Ukuran">{{ $room->size ?: '-' }}</td>
                                <td data-label="Lantai">{{ $room->floor ?: '-' }}</td>
                                <td data-label="Status">
                                    <span class="room-badge room-badge-{{ $room->status }}">
                                        <span class="room-badge-dot"></span>
                                        {{ $statusLabels[$room->status] ?? $room->status }}
                                    </span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="room-table-actions">
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="room-table-btn room-table-btn-edit" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="room-table-btn room-table-btn-delete" title="Hapus">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="room-index-pagination">
                <span>
                    Menampilkan {{ $rooms->firstItem() }}-{{ $rooms->lastItem() }} dari {{ $rooms->total() }} kamar
                </span>
                <div class="room-pagination-arrows">
                    @if ($rooms->onFirstPage())
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></span>
                    @else
                        <a href="{{ $rooms->previousPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></a>
                    @endif

                    @if ($rooms->hasMorePages())
                        <a href="{{ $rooms->nextPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></a>
                    @else
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></span>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
