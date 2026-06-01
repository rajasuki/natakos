@extends('admin.layout')

@section('title', 'Fasilitas')
@section('eyebrow', 'Admin Fasilitas')
@section('page_title', 'Kelola fasilitas')
@section('page_description', 'Kelola daftar fasilitas kamar dan fasilitas umum properti.')

@section('page_actions')
    <a href="{{ route('admin.facilities.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Tambah fasilitas
    </a>
@endsection

@push('styles')
<style>
    .material-symbols-outlined.fill {
        font-variation-settings: 'FILL' 1;
    }

    .facility-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (min-width: 640px) {
        .facility-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .facility-stat-card {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: box-shadow .15s;
    }

    .facility-stat-card:hover {
        box-shadow: var(--ui-shadow);
    }

    .facility-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .facility-stat-icon .material-symbols-outlined {
        font-size: 22px;
    }

    .facility-stat-icon-primary {
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
    }

    .facility-stat-icon-success {
        background: #d1fae5;
        color: #059669;
    }

    .facility-stat-icon-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .facility-stat-label {
        margin: 0 0 2px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
    }

    .facility-stat-value {
        margin: 0;
        font-family: 'Sora', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--ui-ink);
        line-height: 1.1;
    }

    /* ── Main card ── */
    .facility-table-wrap {
        background: #fff;
        border: 1px solid var(--ui-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .facility-toolbar {
        padding: 12px 16px;
        border-bottom: 1px solid var(--ui-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .facility-search-wrap {
        position: relative;
        width: 100%;
        max-width: 320px;
    }

    .facility-search-wrap .material-symbols-outlined {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: var(--ui-body);
        pointer-events: none;
    }

    .facility-search-wrap input {
        width: 100%;
        padding: 7px 12px 7px 34px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--gray-50);
        color: var(--ui-ink);
        outline: none;
    }

    .facility-search-wrap input:focus {
        border-color: var(--ui-accent);
        box-shadow: 0 0 0 2px rgba(74,124,89,.15);
        background: #fff;
    }

    .facility-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border: 1px solid var(--ui-border);
        border-radius: 8px;
        background: transparent;
        color: var(--ui-body);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, color .15s;
    }

    .facility-filter-btn:hover {
        background: var(--gray-50);
        color: var(--ui-ink);
    }

    /* ── Table ── */
    .facility-table {
        width: 100%;
        border-collapse: collapse;
    }

    .facility-table thead {
        background: var(--gray-50);
    }

    .facility-table th {
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ui-body);
        text-align: left;
        border-bottom: 1px solid var(--ui-border);
    }

    .facility-table th:last-child {
        text-align: right;
    }

    .facility-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ui-ink);
        border-bottom: 1px solid var(--ui-border);
        vertical-align: middle;
    }

    .facility-table tbody tr:last-child td {
        border-bottom: none;
    }

    .facility-table tbody tr {
        transition: background .1s;
    }

    .facility-table tbody tr:hover {
        background: var(--ui-canvas);
    }

    .facility-table-id {
        font-size: 13px;
        color: var(--ui-body);
    }

    .facility-table-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--ui-ink);
        margin: 0;
    }

    .facility-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    .facility-type-badge-room {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid rgba(5,150,105,.15);
    }

    .facility-type-badge-public {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid rgba(217,119,6,.15);
    }

    .facility-table-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
    }

    .facility-table-btn {
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

    .facility-table-btn .material-symbols-outlined {
        font-size: 18px;
    }

    .facility-table-btn:hover {
        background: var(--ui-soft);
    }

    .facility-table-btn-edit:hover {
        color: var(--ui-accent);
        background: var(--ui-accent-soft);
    }

    .facility-table-btn-delete:hover {
        color: #dc2626;
        background: #fee2e2;
    }

    /* ── Footer ── */
    .facility-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--ui-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 13px;
        color: var(--ui-body);
    }

    .facility-footer-arrows {
        display: flex;
        gap: 4px;
    }

    .facility-footer-arrows a,
    .facility-footer-arrows span {
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

    .facility-footer-arrows a:hover {
        background: var(--ui-soft);
        color: var(--ui-ink);
    }

    .facility-footer-arrows span.disabled {
        opacity: .4;
        cursor: default;
    }

    .facility-footer-page {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--ui-accent-soft);
        color: var(--ui-accent);
        font-size: 12px;
        font-weight: 700;
    }

    @media (max-width: 767px) {
        .facility-table thead { display: none; }
        .facility-table,
        .facility-table tbody,
        .facility-table tr,
        .facility-table td { display: block; }
        .facility-table tr { padding: 12px 16px; border-bottom: 1px solid var(--ui-border); }
        .facility-table tr:last-child { border-bottom: none; }
        .facility-table td {
            padding: 4px 0;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .facility-table td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ui-body);
            min-width: 80px;
            flex-shrink: 0;
        }
        .facility-table td:first-child { padding-top: 0; }
        .facility-table td:last-child { padding-bottom: 0; }
        .facility-table td[data-label="Aksi"] { justify-content: flex-start; }
        .facility-table-actions { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
    @php
        $totalCount = $facilities->count();
        $roomCount = $facilities->where('type', 'room')->count();
        $publicCount = $facilities->where('type', 'public')->count();
    @endphp

    @if ($facilities->isEmpty())
        <section class="empty-state">
            <h2>Belum ada fasilitas</h2>
            <p>Tambahkan fasilitas pertama untuk mulai membangun katalog fasilitas kamar dan fasilitas umum di area admin.</p>
            <div class="empty-state-actions">
                <a href="{{ route('admin.facilities.create') }}" class="button button-primary">Tambah fasilitas sekarang</a>
            </div>
        </section>
    @else
        {{-- Stats Cards --}}
        <section class="facility-stats">
            <div class="facility-stat-card">
                <div class="facility-stat-icon facility-stat-icon-primary">
                    <span class="material-symbols-outlined fill">inventory_2</span>
                </div>
                <div>
                    <p class="facility-stat-label">Total Fasilitas</p>
                    <p class="facility-stat-value">{{ $totalCount }}</p>
                </div>
            </div>
            <div class="facility-stat-card">
                <div class="facility-stat-icon facility-stat-icon-success">
                    <span class="material-symbols-outlined fill">bed</span>
                </div>
                <div>
                    <p class="facility-stat-label">Fasilitas Kamar</p>
                    <p class="facility-stat-value">{{ $roomCount }}</p>
                </div>
            </div>
            <div class="facility-stat-card">
                <div class="facility-stat-icon facility-stat-icon-warning">
                    <span class="material-symbols-outlined fill">deck</span>
                </div>
                <div>
                    <p class="facility-stat-label">Fasilitas Umum</p>
                    <p class="facility-stat-value">{{ $publicCount }}</p>
                </div>
            </div>
        </section>

        {{-- Main Table Card --}}
        <div class="facility-table-wrap">
            {{-- Search toolbar --}}
            <div class="facility-toolbar">
                <form method="GET" action="{{ route('admin.facilities.index') }}" class="facility-search-wrap" style="margin:0;">
                    <span class="material-symbols-outlined">search</span>
                    <input name="q" type="text" value="{{ request('q') }}" placeholder="Cari fasilitas...">
                </form>
                <button class="facility-filter-btn" type="button" onclick="alert('Filter akan segera tersedia.');">
                    <span class="material-symbols-outlined" style="font-size:16px;">filter_list</span>
                    Filter
                </button>
            </div>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table class="facility-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama fasilitas</th>
                            <th>Type</th>
                            <th>Icon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facilities as $facility)
                            <tr>
                                <td data-label="ID" class="facility-table-id">#{{ $facility->id }}</td>
                                <td data-label="Nama fasilitas">
                                    <p class="facility-table-name">{{ $facility->name }}</p>
                                </td>
                                <td data-label="Type">
                                    <span class="facility-type-badge facility-type-badge-{{ $facility->type }}">
                                        {{ $typeLabels[$facility->type] ?? $facility->type }}
                                    </span>
                                </td>
                                <td data-label="Icon">
                                    {!! \App\Support\FacilityIcon::render($facility) !!}
                                </td>
                                <td data-label="Aksi">
                                    <div class="facility-table-actions">
                                        <a href="{{ route('admin.facilities.edit', $facility) }}" class="facility-table-btn facility-table-btn-edit" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Hapus fasilitas ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="facility-table-btn facility-table-btn-delete" title="Hapus">
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

            {{-- Footer --}}
            <div class="facility-footer">
                <span>Menampilkan {{ $totalCount }} dari {{ $totalCount }} fasilitas</span>
                <div class="facility-footer-arrows">
                    <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></span>
                    <span class="facility-footer-page">1</span>
                    <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></span>
                </div>
            </div>
        </div>
    @endif
@endsection
