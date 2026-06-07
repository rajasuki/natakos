@extends('admin.layout')

@section('title', 'Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Kelola penghuni')
@section('page_description', 'Atur akun penghuni, kamar yang ditempati, masa tinggal, dan status penghuni dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.tenants.history') }}" class="button button-secondary">
        <span class="material-symbols-outlined" style="font-size:16px;">history</span>
        Riwayat
    </a>
    <a href="{{ route('admin.tenants.create-existing') }}" class="button button-subtle">
        <span class="material-symbols-outlined" style="font-size:16px;">meeting_room</span>
        Assign kamar
    </a>
    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">person_add</span>
        Penghuni baru
    </a>
@endsection

@push('styles')
<style>
    .material-symbols-outlined.fill {
        font-variation-settings: 'FILL' 1;
    }

    .tenant-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (min-width: 640px) {
        .tenant-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .tenant-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .tenant-stat-icon {
        position: absolute;
        top: 12px;
        right: 12px;
        opacity: .12;
        font-size: 48px;
        transition: opacity .2s;
    }

    .tenant-stat-card:hover .tenant-stat-icon {
        opacity: .22;
    }

    .tenant-stat-label {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--ui-body);
    }

    .tenant-stat-value {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.1;
        color: var(--ui-ink);
    }

    .tenant-stat-hint {
        margin: 6px 0 0;
        font-size: 12px;
        color: var(--ui-body);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Main card ── */
    .tenant-table-wrap {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .tenant-filter {
        padding: 16px 20px;
        border-bottom: 1px solid var(--ui-border);
        background: var(--gray-50);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    @media (min-width: 768px) {
        .tenant-filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .tenant-filter-left {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .tenant-filter-left {
            flex-direction: row;
            align-items: center;
            width: auto;
        }
    }

    .tenant-filter-left .field {
        margin: 0;
    }

    .tenant-search-wrap {
        position: relative;
        width: 100%;
    }

    @media (min-width: 640px) {
        .tenant-search-wrap {
            width: 240px;
        }
    }

    .tenant-search-wrap .material-symbols-outlined {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: var(--ui-body);
        pointer-events: none;
    }

    .tenant-search-wrap input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
    }

    .tenant-search-wrap input:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .tenant-room-select {
        padding: 8px 12px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: #fff;
        color: var(--ui-ink);
        outline: none;
        min-width: 160px;
    }

    .tenant-room-select:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
    }

    .tenant-filter-right {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .tenant-filter-right {
            width: auto;
        }
    }

    .tenant-filter-right .button {
        flex: 1;
    }

    @media (min-width: 768px) {
        .tenant-filter-right .button {
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
    .tenant-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .tenant-table thead {
        background: var(--gray-50);
    }

    .tenant-table th {
        padding: 11px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
        text-align: left;
        border-bottom: 1px solid var(--ui-border);
    }

    .tenant-table th:last-child {
        text-align: right;
    }

    .tenant-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ui-ink);
        border-bottom: 1px solid var(--ui-border);
        vertical-align: middle;
    }

    .tenant-table tbody tr:last-child td {
        border-bottom: none;
    }

    .tenant-table tbody tr {
        transition: background .1s;
    }

    .tenant-table tbody tr:hover {
        background: var(--ui-canvas);
    }

    .tenant-table-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        margin: 0 0 2px;
    }

    .tenant-table-id {
        font-size: 11px;
        color: var(--ui-body);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .tenant-room-tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 10px;
        background: var(--gray-50);
        border: 1px solid var(--ui-border);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--ui-ink);
    }

    .tenant-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
    }

    .tenant-status-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .tenant-status-badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .tenant-status-badge-active .tenant-status-badge-dot {
        background: #059669;
    }

    .tenant-status-badge-inactive {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .tenant-status-badge-inactive .tenant-status-badge-dot {
        background: var(--gray-400);
    }

    .tenant-status-badge-moved_out {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .tenant-status-badge-moved_out .tenant-status-badge-dot {
        background: var(--gray-400);
    }

    .tenant-table-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
    }

    .tenant-table-btn {
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

    .tenant-table-btn .material-symbols-outlined {
        font-size: 18px;
    }

    .tenant-table-btn:hover {
        background: var(--ui-soft);
    }

    .tenant-table-btn-edit:hover {
        color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    .tenant-table-btn-checkout:hover {
        color: var(--gray-600);
        background: var(--gray-100);
    }

    .tenant-table-btn-delete:hover {
        color: #dc2626;
        background: #fee2e2;
    }

    /* ── Pagination ── */
    .tenant-pagination {
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

    .tenant-pagination-arrows {
        display: flex;
        gap: 4px;
    }

    .tenant-pagination-arrows a,
    .tenant-pagination-arrows span {
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

    .tenant-pagination-arrows a:hover {
        background: var(--ui-soft);
        color: var(--ui-ink);
    }

    .tenant-pagination-arrows span[disabled],
    .tenant-pagination-arrows span.disabled {
        opacity: .4;
        cursor: default;
    }

    @media (max-width: 767px) {
        .tenant-table thead { display: none; }
        .tenant-table,
        .tenant-table tbody,
        .tenant-table tr,
        .tenant-table td { display: block; }
        .tenant-table tr { padding: 12px 16px; border-bottom: 1px solid var(--ui-border); }
        .tenant-table tr:last-child { border-bottom: none; }
        .tenant-table td {
            padding: 4px 0;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tenant-table td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ui-body);
            min-width: 80px;
            flex-shrink: 0;
        }
        .tenant-table td:first-child { padding-top: 0; }
        .tenant-table td:last-child { padding-bottom: 0; }
        .tenant-table td[data-label="Aksi"] { justify-content: flex-start; }
        .tenant-table-actions { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
    @if ($tenants->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada penghuni yang cocok' : 'Belum ada penghuni aktif' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat penghuni lain yang tercatat di IchiKOS.' : 'Tambahkan penghuni aktif pertama untuk membuat akun tenant, menghubungkannya ke kamar, dan mengatur masa tinggal secara rapi.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni sekarang</a>
                @endif
            </div>
        </section>
    @else
        {{-- Stats Cards --}}
        <section class="tenant-stats">
            <div class="tenant-stat-card">
                <span class="material-symbols-outlined fill tenant-stat-icon" style="color:var(--ui-accent);">group</span>
                <h3 class="tenant-stat-label">Total Penghuni Aktif</h3>
                <p class="tenant-stat-value">{{ $tenantCounts['Penghuni aktif'] ?? 0 }}</p>
            </div>
            <div class="tenant-stat-card">
                <span class="material-symbols-outlined fill tenant-stat-icon" style="color:#d97706;">event_busy</span>
                <h3 class="tenant-stat-label">Masa Tinggal Berakhir Bulan Ini</h3>
                <p class="tenant-stat-value">
                    @php
                        $endingThisMonth = $tenants->filter(fn ($t) => $t->status === 'active' && $t->end_date !== null && $t->end_date->isCurrentMonth())->count();
                    @endphp
                    {{ $endingThisMonth }}
                </p>
                <p class="tenant-stat-hint">Perlu konfirmasi perpanjangan</p>
            </div>
        </section>

        {{-- Main Table Card --}}
        <div class="tenant-table-wrap">
            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.tenants.index') }}" class="tenant-filter">
                <div class="tenant-filter-left">
                    <div class="tenant-search-wrap">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari penghuni...">
                    </div>
                    <select name="room_id" class="tenant-room-select">
                        <option value="">Semua kamar</option>
                        @foreach ($filterRooms as $room)
                            <option value="{{ $room->id }}" @selected((string) $filters['room_id'] === (string) $room->id)>{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tenant-filter-right">
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button btn-filter-apply">Terapkan filter</button>
                    <a href="{{ route('admin.tenants.export', request()->query()) }}" class="button button-subtle" title="Export PDF">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                    </a>
                </div>
            </form>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table class="tenant-table">
                    <thead>
                        <tr>
                            <th>Nama penghuni</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Kamar</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                            <tr>
                                <td data-label="Nama penghuni">
                                    <div class="tenant-table-name">{{ $tenant->user?->name ?: 'User tidak tersedia' }}</div>
                                    <div class="tenant-table-id">ID: T-{{ str_pad((string) $tenant->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td data-label="Email">{{ $tenant->user?->email ?: '-' }}</td>
                                <td data-label="Nomor HP">{{ $tenant->user?->phone ?: '-' }}</td>
                                <td data-label="Kamar">
                                    <span class="tenant-room-tag">{{ $tenant->room?->name ?: '-' }}</span>
                                </td>
                                <td data-label="Tanggal Masuk">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</td>
                                <td data-label="Tanggal Keluar" class="{{ $tenant->end_date !== null && $tenant->end_date->isPast() ? 'muted' : '' }}">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</td>
                                <td data-label="Status">
                                    <span class="tenant-status-badge tenant-status-badge-{{ $tenant->status }}">
                                        <span class="tenant-status-badge-dot"></span>
                                        {{ $statusLabels[$tenant->status] ?? $tenant->status }}
                                    </span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="tenant-table-actions">
                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="tenant-table-btn tenant-table-btn-edit" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        @if ($tenant->status === 'active')
                                            <a href="{{ route('admin.tenants.checkout', $tenant) }}" class="tenant-table-btn tenant-table-btn-checkout" title="Check-out">
                                                <span class="material-symbols-outlined">logout</span>
                                            </a>
                                            <a href="{{ route('admin.tenants.transfer', $tenant) }}" class="tenant-table-btn tenant-table-btn-edit" title="Pindah Kamar">
                                                <span class="material-symbols-outlined">move_group</span>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" onsubmit="return confirm('Hapus penghuni ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tenant-table-btn tenant-table-btn-delete" title="Hapus">
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
            <div class="tenant-pagination">
                <span>
                    Menampilkan {{ $tenants->firstItem() }}-{{ $tenants->lastItem() }} dari {{ $tenants->total() }} penghuni
                </span>
                <div class="tenant-pagination-arrows">
                    @if ($tenants->onFirstPage())
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></span>
                    @else
                        <a href="{{ $tenants->previousPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></a>
                    @endif
                    @if ($tenants->hasMorePages())
                        <a href="{{ $tenants->nextPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></a>
                    @else
                        <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></span>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
