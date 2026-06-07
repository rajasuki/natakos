@extends('admin.layout')

@section('title', 'Tagihan Utilitas')
@section('eyebrow', 'Admin Utilitas')
@section('page_title', 'Tagihan Utilitas')
@section('page_description', 'Kelola tagihan air, listrik, dan internet penghuni.')

@section('page_actions')
    <a href="{{ route('admin.utility-bills.create') }}" class="button button-primary">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span>
        Tagihan baru
    </a>
@endsection

@push('styles')
<style>
    .bill-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .bill-stat-card { background:#fff; border:1px solid var(--ui-border); border-radius:12px; padding:16px; }
    .bill-stat-value { margin:0; font-family:'Sora',sans-serif; font-size:28px; font-weight:800; color:var(--ui-ink); letter-spacing:-0.5px; line-height:1.1; }
    .bill-stat-label { margin:0 0 4px; font-size:12px; font-weight:600; color:var(--ui-body); }
    .bill-table-wrap { background:#fff; border:1px solid var(--ui-border); border-radius:14px; overflow:hidden; }
    .bill-filter { padding:16px 20px; border-bottom:1px solid var(--ui-border); background:var(--gray-50); display:flex; flex-direction:column; gap:12px; }
    @media (min-width:768px) { .bill-filter { flex-direction:row; align-items:center; justify-content:space-between; } }
    .bill-filter-left { display:flex; flex-direction:column; gap:10px; width:100%; }
    @media (min-width:640px) { .bill-filter-left { flex-direction:row; align-items:center; width:auto; } }
    .bill-search-wrap { position:relative; width:100%; }
    @media (min-width:640px) { .bill-search-wrap { width:240px; } }
    .bill-search-wrap .material-symbols-outlined { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:18px; color:var(--ui-body); pointer-events:none; }
    .bill-search-wrap input { width:100%; padding:8px 12px 8px 34px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; }
    .bill-search-wrap input:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .bill-filter-select { padding:8px 12px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; min-width:140px; }
    .bill-filter-select:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .btn-filter-apply { background:var(--ui-accent-soft); color:var(--ui-accent); border:1px solid transparent; font-weight:600; }
    .btn-filter-apply:hover { background:var(--ui-accent); color:#fff; }
    .bill-table { width:100%; border-collapse:collapse; min-width:800px; }
    .bill-table th { padding:11px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); text-align:left; border-bottom:1px solid var(--ui-border); background:var(--gray-50); }
    .bill-table th:last-child { text-align:right; }
    .bill-table td { padding:12px 16px; font-size:13px; color:var(--ui-ink); border-bottom:1px solid var(--ui-border); vertical-align:middle; }
    .bill-table tbody tr:last-child td { border-bottom:none; }
    .bill-table tbody tr:hover { background:var(--ui-canvas); }
    .bill-actions { display:flex; align-items:center; justify-content:flex-end; gap:4px; }
    .bill-btn { display:flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:none; background:transparent; color:var(--ui-body); cursor:pointer; transition:background .15s,color .15s; }
    .bill-btn .material-symbols-outlined { font-size:18px; }
    .bill-btn:hover { background:var(--ui-soft); }
    .bill-btn-edit:hover { color:var(--ui-accent); background:var(--ui-accent-soft); }
    .bill-btn-delete:hover { color:#dc2626; background:#fee2e2; }
    .bill-pagination { padding:14px 20px; border-top:1px solid var(--ui-border); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; font-size:13px; color:var(--ui-body); }
    .bill-pagination-arrows { display:flex; gap:4px; }
    .bill-pagination-arrows a,.bill-pagination-arrows span { display:flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid var(--ui-border); color:var(--ui-body); transition:background .15s,color .15s; }
    .bill-pagination-arrows a:hover { background:var(--ui-soft); color:var(--ui-ink); }
    .bill-pagination-arrows span.disabled { opacity:.4; cursor:default; }
    @media (max-width:767px) { .bill-table thead { display:none; }
    .bill-table,.bill-table tbody,.bill-table tr,.bill-table td { display:block; }
    .bill-table tr { padding:12px 16px; border-bottom:1px solid var(--ui-border); }
    .bill-table td { padding:4px 0; border:none; display:flex; align-items:center; gap:8px; }
    .bill-table td::before { content:attr(data-label); font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); min-width:100px; flex-shrink:0; }
    .bill-table td:first-child { padding-top:0; } .bill-table td:last-child { padding-bottom:0; } }
</style>
@endpush

@section('content')
    @if ($bills->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada tagihan yang cocok' : 'Belum ada tagihan utilitas' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat tagihan lain.' : 'Tambahkan tagihan utilitas pertama untuk mulai mencatat biaya air, listrik, dan internet penghuni.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.utility-bills.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.utility-bills.create') }}" class="button button-primary">Tambah tagihan sekarang</a>
                @endif
            </div>
        </section>
    @else
        <section class="bill-stats">
            <div class="bill-stat-card"><p class="bill-stat-label">Total Tagihan</p><p class="bill-stat-value">{{ $counts['total'] }}</p></div>
            <div class="bill-stat-card"><p class="bill-stat-label">Belum Bayar</p><p class="bill-stat-value">{{ $counts['unpaid'] }}</p></div>
            <div class="bill-stat-card"><p class="bill-stat-label">Lunas</p><p class="bill-stat-value">{{ $counts['paid'] }}</p></div>
        </section>

        <div class="bill-table-wrap">
            <form method="GET" action="{{ route('admin.utility-bills.index') }}" class="bill-filter">
                <div class="bill-filter-left">
                    <div class="bill-search-wrap">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari penghuni...">
                    </div>
                    <select name="type" class="bill-filter-select">
                        <option value="">Semua jenis</option>
                        @foreach ($typeLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="bill-filter-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="{{ route('admin.utility-bills.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button btn-filter-apply">Terapkan</button>
                </div>
            </form>

            <div style="overflow-x:auto;">
                <table class="bill-table">
                    <thead><tr><th>Penghuni</th><th>Kamar</th><th>Jenis</th><th>Periode</th><th>Jumlah</th><th>Tenggat</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($bills as $bill)
                            <tr>
                                <td data-label="Penghuni">{{ $bill->tenant?->user?->name ?: '-' }}</td>
                                <td data-label="Kamar">{{ $bill->tenant?->room?->name ?: '-' }}</td>
                                <td data-label="Jenis">
                                    <span class="badge badge-{{ $bill->type }}">{{ $typeLabels[$bill->type] ?? $bill->type }}</span>
                                </td>
                                <td data-label="Periode">{{ $bill->period }}</td>
                                <td data-label="Jumlah" style="font-weight:600;">{{ \App\Support\UiFormatter::currency($bill->amount) }}</td>
                                <td data-label="Tenggat">{{ \App\Support\UiFormatter::date($bill->due_date) }}</td>
                                <td data-label="Status">
                                    <span class="badge badge-{{ $bill->status }}">{{ $statusLabels[$bill->status] ?? $bill->status }}</span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="bill-actions">
                                        <a href="{{ route('admin.utility-bills.edit', $bill) }}" class="bill-btn bill-btn-edit" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.utility-bills.destroy', $bill) }}" onsubmit="return confirm('Hapus tagihan utilitas ini?');" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bill-btn bill-btn-delete" title="Hapus"><span class="material-symbols-outlined">delete</span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bill-pagination">
                <span>Menampilkan {{ $bills->firstItem() }}-{{ $bills->lastItem() }} dari {{ $bills->total() }} tagihan</span>
                <div class="bill-pagination-arrows">
                    @if ($bills->onFirstPage()) <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></span>
                    @else <a href="{{ $bills->previousPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></a> @endif
                    @if ($bills->hasMorePages()) <a href="{{ $bills->nextPageUrl() }}"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></a>
                    @else <span class="disabled"><span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></span> @endif
                </div>
            </div>
        </div>
    @endif
@endsection
