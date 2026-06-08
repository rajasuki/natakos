@extends('admin.layout')

@section('title', 'Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Kelola kamar')
@section('page_description', 'Manajemen inventaris dan status kamar kos.')

@section('page_actions')
    <a href="{{ route('admin.rooms.create') }}" class="button button-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <span class="material-symbols-outlined" style="font-size:20px;">add</span>
        Tambah Kamar
    </a>
@endsection

@push('styles')
<style>
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }

    .rm-stats { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; margin-bottom:24px; }
    @media(min-width:1024px){ .rm-stats { grid-template-columns:repeat(4,1fr); } }
    .rm-stat { background:#fff; border:1px solid var(--ui-border); border-radius:14px; padding:18px 20px; display:flex; align-items:center; gap:14px; }
    .rm-stat-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .rm-stat-icon .material-symbols-outlined { font-size:22px; font-variation-settings:'FILL' 1; }
    .rm-stat-body { min-width:0; }
    .rm-stat-label { font-size:12px; font-weight:600; color:var(--ui-body); margin:0 0 2px; }
    .rm-stat-value { font-family:'Sora',sans-serif; font-size:26px; font-weight:800; color:var(--ui-ink); margin:0; line-height:1.2; }
    .rm-stat-primary .rm-stat-icon { background:#e8f5e9; color:var(--ui-accent); }
    .rm-stat-success .rm-stat-icon { background:#d1fae5; color:#059669; }
    .rm-stat-warning .rm-stat-icon { background:#fef3c7; color:#d97706; }

    .rm-card { background:#fff; border:1px solid var(--ui-border); border-radius:14px; overflow:hidden; }
    .rm-filter { padding:14px 20px; border-bottom:1px solid var(--ui-border); background:var(--gray-50); display:flex; flex-direction:column; gap:10px; }
    @media(min-width:768px){ .rm-filter { flex-direction:row; align-items:center; justify-content:space-between; } }
    .rm-filter-left { display:flex; flex-direction:column; gap:10px; width:100%; }
    @media(min-width:640px){ .rm-filter-left { flex-direction:row; align-items:center; width:auto; } }
    .rm-search { position:relative; width:100%; }
    @media(min-width:640px){ .rm-search { width:220px; } }
    .rm-search .material-symbols-outlined { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:18px; color:var(--ui-body); pointer-events:none; }
    .rm-search input { width:100%; padding:8px 12px 8px 34px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; box-sizing:border-box; }
    .rm-search input:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .rm-select { padding:8px 12px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; min-width:140px; }
    .rm-select:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .rm-filter-right { display:flex; gap:8px; width:100%; }
    @media(min-width:768px){ .rm-filter-right { width:auto; } }
    .rm-filter-right .button { flex:1; }
    @media(min-width:768px){ .rm-filter-right .button { flex:none; } }
    .rm-filter-apply { background:var(--ui-accent-soft); color:var(--ui-accent); border:1px solid transparent; font-weight:600; }
    .rm-filter-apply:hover { background:var(--ui-accent); color:#fff; }

    .rm-table { width:100%; border-collapse:collapse; min-width:800px; }
    .rm-table thead { background:var(--gray-50); }
    .rm-table th { padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); text-align:left; border-bottom:1px solid var(--ui-border); }
    .rm-table th:last-child { text-align:right; }
    .rm-table td { padding:14px 16px; font-size:13px; color:var(--ui-ink); border-bottom:1px solid var(--ui-border); vertical-align:middle; }
    .rm-table tbody tr:last-child td { border-bottom:none; }
    .rm-table tbody tr { transition:background .12s; }
    .rm-table tbody tr:hover { background:#f9fafb; }

    .rm-photo { width:52px; height:52px; border-radius:10px; overflow:hidden; border:1px solid var(--ui-border); background:var(--ui-soft); flex-shrink:0; }
    .rm-photo img { width:100%; height:100%; object-fit:cover; display:block; }
    .rm-photo-empty { width:52px; height:52px; border-radius:10px; border:1px dashed var(--ui-border); background:var(--gray-50); display:flex; align-items:center; justify-content:center; color:var(--ui-body); flex-shrink:0; }
    .rm-photo-empty .material-symbols-outlined { font-size:20px; opacity:.35; }

    .rm-name { font-size:14px; font-weight:600; color:var(--ui-ink); margin:0 0 2px; }
    .rm-name-row { display:flex; align-items:center; gap:12px; }
    .rm-slug { font-size:11px; color:var(--gray-400); font-weight:500; }

    .rm-price { font-weight:700; font-size:14px; color:var(--ui-accent); white-space:nowrap; }
    .rm-meta { font-size:12px; color:var(--ui-body); display:flex; gap:6px; align-items:center; }
    .rm-meta-divider { width:3px; height:3px; border-radius:50%; background:var(--ui-border); }

    .rm-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:600; line-height:1.3; }
    .rm-badge-dot { width:7px; height:7px; border-radius:999px; flex-shrink:0; }
    .rm-badge-available { background:#d1fae5; color:#065f46; }
    .rm-badge-available .rm-badge-dot { background:#059669; }
    .rm-badge-occupied { background:var(--ui-accent-soft); color:var(--ui-accent); }
    .rm-badge-occupied .rm-badge-dot { background:var(--ui-accent); }
    .rm-badge-maintenance { background:#fef3c7; color:#92400e; }
    .rm-badge-maintenance .rm-badge-dot { background:#d97706; }

    .rm-actions { display:flex; align-items:center; justify-content:flex-end; gap:4px; }
    .rm-btn { width:34px; height:34px; border-radius:8px; border:none; background:transparent; color:var(--ui-body); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .15s,color .15s; }
    .rm-btn .material-symbols-outlined { font-size:18px; }
    .rm-btn:hover { background:var(--ui-soft); }
    .rm-btn-edit:hover { color:var(--ui-accent); background:var(--ui-accent-soft); }
    .rm-btn-delete:hover { color:#dc2626; background:#fee2e2; }

    .rm-pagination { padding:14px 20px; border-top:1px solid var(--ui-border); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; font-size:13px; color:var(--ui-body); }

    @media(max-width:767px){
        .rm-table thead { display:none; }
        .rm-table,.rm-table tbody,.rm-table tr,.rm-table td { display:block; }
        .rm-table tr { padding:14px 16px; border-bottom:1px solid var(--ui-border); }
        .rm-table tr:last-child { border-bottom:none; }
        .rm-table td { padding:4px 0; border:none; display:flex; align-items:center; gap:10px; }
        .rm-table td::before { content:attr(data-label); font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); min-width:80px; flex-shrink:0; }
        .rm-table td:first-child { padding-top:0; }
        .rm-table td:last-child { padding-bottom:0; }
        .rm-table td[data-label="Foto"]::before { display:none; }
    }
</style>
@endpush

@section('content')
    @if ($rooms->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada kamar yang cocok' : 'Belum ada kamar' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat kamar lain.' : 'Mulai dengan menambahkan kamar pertama agar admin dapat mengelola harga, status, dan foto utama kamar dari dashboard ini.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar sekarang</a>
                @endif
            </div>
        </section>
    @else
        <section class="rm-stats">
            @foreach ([
                ['label'=>'Total Kamar','icon'=>'bed','count'=>$roomCounts['Total']??0,'class'=>'rm-stat-primary'],
                ['label'=>'Tersedia','icon'=>'check_circle','count'=>$roomCounts['Tersedia']??0,'class'=>'rm-stat-success'],
                ['label'=>'Terisi','icon'=>'group','count'=>$roomCounts['Terisi']??0,'class'=>'rm-stat-primary'],
                ['label'=>'Perbaikan','icon'=>'build','count'=>$roomCounts['Perbaikan']??0,'class'=>'rm-stat-warning'],
            ] as $s)
                <div class="rm-stat {{ $s['class'] }}">
                    <div class="rm-stat-icon"><span class="material-symbols-outlined">{{ $s['icon'] }}</span></div>
                    <div class="rm-stat-body">
                        <p class="rm-stat-label">{{ $s['label'] }}</p>
                        <p class="rm-stat-value">{{ $s['count'] }}</p>
                    </div>
                </div>
            @endforeach
        </section>

        <div class="rm-card">
            <form method="GET" action="{{ route('admin.rooms.index') }}" class="rm-filter">
                <div class="rm-filter-left">
                    <div class="rm-search">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari kamar...">
                    </div>
                    <select name="status" class="rm-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="rm-filter-right">
                    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button rm-filter-apply">Terapkan</button>
                    <a href="{{ route('admin.rooms.export', request()->query()) }}" class="button button-subtle" title="Export PDF">
                        <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                    </a>
                </div>
            </form>

            <div style="overflow-x:auto;">
                <table class="rm-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Kamar</th>
                            <th>Harga</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr>
                                <td data-label="Foto" style="width:68px;">
                                    @if ($room->main_image)
                                        <div class="rm-photo"><img src="{{ asset('storage/'.$room->main_image) }}" alt="{{ $room->name }}"></div>
                                    @else
                                        <div class="rm-photo-empty"><span class="material-symbols-outlined">image</span></div>
                                    @endif
                                </td>
                                <td data-label="Kamar">
                                    <div class="rm-name-row">
                                        <div>
                                            <div class="rm-name">{{ $room->name }}</div>
                                            <div class="rm-slug">/{{ $room->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Harga"><span class="rm-price">{{ \App\Support\UiFormatter::currency($room->price) }}</span></td>
                                <td data-label="Detail">
                                    <div class="rm-meta">
                                        <span>{{ $room->capacity }} org</span>
                                        @if ($room->size)<span class="rm-meta-divider"></span><span>{{ $room->size }}</span>@endif
                                        @if ($room->floor)<span class="rm-meta-divider"></span><span>Lt.{{ $room->floor }}</span>@endif
                                    </div>
                                </td>
                                <td data-label="Status">
                                    <span class="rm-badge rm-badge-{{ $room->status }}">
                                        <span class="rm-badge-dot"></span>
                                        {{ $statusLabels[$room->status] ?? $room->status }}
                                    </span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="rm-actions">
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="rm-btn rm-btn-edit" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        @if ($room->status === 'occupied')
                                            <span class="rm-btn" title="Checkout penghuni terlebih dahulu" style="opacity:0.35;cursor:not-allowed;">
                                                <span class="material-symbols-outlined">delete</span>
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar {{ $room->name }}?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rm-btn rm-btn-delete" title="Hapus">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="rm-pagination">
                <span>Menampilkan {{ $rooms->firstItem() }}-{{ $rooms->lastItem() }} dari {{ $rooms->total() }} kamar</span>
                <div style="display:flex;gap:4px;">{{ $rooms->links() }}</div>
            </div>
        </div>
    @endif
@endsection
