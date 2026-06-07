@extends('admin.layout')

@section('title', 'Pengajuan Perbaikan')
@section('eyebrow', 'Admin Perbaikan')
@section('page_title', 'Pengajuan Perbaikan')
@section('page_description', 'Kelola laporan perbaikan dari penghuni.')

@push('styles')
<style>
    .mr-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .mr-stat-card { background:#fff; border:1px solid var(--ui-border); border-radius:12px; padding:16px; }
    .mr-stat-value { margin:0; font-family:'Sora',sans-serif; font-size:28px; font-weight:800; color:var(--ui-ink); letter-spacing:-0.5px; line-height:1.1; }
    .mr-stat-label { margin:0 0 4px; font-size:12px; font-weight:600; color:var(--ui-body); }
    .mr-card-wrap { background:#fff; border:1px solid var(--ui-border); border-radius:14px; overflow:hidden; }
    .mr-filter { padding:16px 20px; border-bottom:1px solid var(--ui-border); background:var(--gray-50); display:flex; flex-direction:column; gap:12px; }
    @media (min-width:768px) { .mr-filter { flex-direction:row; align-items:center; justify-content:space-between; } }
    .mr-filter-left { display:flex; flex-direction:column; gap:10px; width:100%; }
    @media (min-width:640px) { .mr-filter-left { flex-direction:row; align-items:center; width:auto; } }
    .mr-search-wrap { position:relative; width:100%; }
    @media (min-width:640px) { .mr-search-wrap { width:240px; } }
    .mr-search-wrap .material-symbols-outlined { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:18px; color:var(--ui-body); pointer-events:none; }
    .mr-search-wrap input { width:100%; padding:8px 12px 8px 34px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; }
    .mr-search-wrap input:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .mr-filter-select { padding:8px 12px; border:1px solid var(--ui-border); border-radius:8px; font-size:13px; background:#fff; color:var(--ui-ink); outline:none; min-width:140px; }
    .mr-filter-select:focus { border-color:var(--ui-accent); box-shadow:0 0 0 2px rgba(74,124,89,.15); }
    .mr-list { padding:0; margin:0; }
    .mr-item { padding:16px 20px; border-bottom:1px solid var(--ui-border); display:flex; flex-direction:column; gap:8px; transition:background .1s; }
    .mr-item:last-child { border-bottom:none; }
    .mr-item:hover { background:var(--ui-canvas); }
    .mr-item-top { display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:8px; }
    .mr-item-title { margin:0; font-size:14px; font-weight:600; color:var(--ui-ink); }
    .mr-item-meta { display:flex; flex-wrap:wrap; gap:6px; align-items:center; font-size:12px; color:var(--ui-body); }
    .mr-item-desc { margin:0; font-size:13px; color:var(--ui-body); line-height:1.6; }
    .mr-item-actions { display:flex; gap:8px; align-items:center; margin-top:4px; }
    .mr-btn { display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:8px; border:1px solid var(--ui-border); font-size:12px; font-weight:600; background:#fff; color:var(--ui-ink); cursor:pointer; transition:all .15s; text-decoration:none; }
    .mr-btn:hover { background:var(--ui-soft); }
    .mr-btn-primary { background:var(--ui-accent); color:#fff; border-color:var(--ui-accent); }
    .mr-btn-primary:hover { background:var(--ui-accent-hover); }
    .mr-btn-warning { background:#d97706; color:#fff; border-color:#d97706; }
    .mr-btn-warning:hover { background:#b45309; }
    .mr-btn-success { background:#059669; color:#fff; border-color:#059669; }
    .mr-btn-success:hover { background:#047857; }
    .mr-btn-danger { background:#dc2626; color:#fff; border-color:#dc2626; }
    .mr-btn-danger:hover { background:#b91c1c; }
    .mr-pagination { padding:14px 20px; border-top:1px solid var(--ui-border); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; font-size:13px; color:var(--ui-body); }
    .badge-low { background:#f0fdf4; color:#166534; }
    .badge-high { background:#fef3c7; color:#92400e; }
    .badge-urgent { background:#fee2e2; color:#991b1b; }
    .badge-in_progress { background:#fff7ed; color:#9a3412; }
</style>
@endpush

@section('content')
    @if ($requests->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada pengajuan yang cocok' : 'Belum ada pengajuan perbaikan' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter.' : 'Pengajuan perbaikan dari penghuni akan muncul di sini.' }}</p>
            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.maintenance-requests.index') }}" class="button button-secondary">Reset filter</a>
                @endif
            </div>
        </section>
    @else
        <section class="mr-stats">
            <div class="mr-stat-card"><p class="mr-stat-label">Total</p><p class="mr-stat-value">{{ $counts['total'] }}</p></div>
            <div class="mr-stat-card"><p class="mr-stat-label">Menunggu</p><p class="mr-stat-value">{{ $counts['pending'] }}</p></div>
            <div class="mr-stat-card"><p class="mr-stat-label">Ditangani</p><p class="mr-stat-value">{{ $counts['in_progress'] }}</p></div>
            <div class="mr-stat-card"><p class="mr-stat-label">Selesai</p><p class="mr-stat-value">{{ $counts['resolved'] }}</p></div>
        </section>

        <div class="mr-card-wrap">
            <form method="GET" action="{{ route('admin.maintenance-requests.index') }}" class="mr-filter">
                <div class="mr-filter-left">
                    <div class="mr-search-wrap">
                        <span class="material-symbols-outlined">search</span>
                        <input name="q" type="text" value="{{ $filters['q'] }}" placeholder="Cari judul atau penghuni...">
                    </div>
                    <select name="status" class="mr-filter-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $v => $l)
                            <option value="{{ $v }}" @selected($filters['status'] === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                    <select name="priority" class="mr-filter-select">
                        <option value="">Semua prioritas</option>
                        @foreach ($priorityLabels as $v => $l)
                            <option value="{{ $v }}" @selected($filters['priority'] === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="{{ route('admin.maintenance-requests.index') }}" class="button button-secondary">Reset</a>
                    <button type="submit" class="button btn-filter-apply">Terapkan</button>
                </div>
            </form>

            <div class="mr-list">
                @foreach ($requests as $req)
                    <div class="mr-item">
                        <div class="mr-item-top">
                            <div>
                                <h4 class="mr-item-title">{{ $req->title }}</h4>
                                <div class="mr-item-meta">
                                    <span>{{ $req->tenant?->user?->name ?: '-' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $req->room?->name ?: '-' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ \App\Support\UiFormatter::date($req->created_at, 'd M Y H:i') }}</span>
                                </div>
                            </div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-{{ $req->priority }}">{{ $priorityLabels[$req->priority] ?? $req->priority }}</span>
                                <span class="badge badge-{{ $req->status }}">{{ $statusLabels[$req->status] ?? $req->status }}</span>
                            </div>
                        </div>
                        <p class="mr-item-desc">{{ Str::limit($req->description, 200) }}</p>
                        @if ($req->admin_notes)
                            <div style="font-size:12px;color:var(--ui-body);background:var(--gray-50);padding:8px 12px;border-radius:8px;">
                                <strong>Catatan admin:</strong> {{ $req->admin_notes }}
                            </div>
                        @endif
                        <div class="mr-item-actions">
                            <a href="{{ route('admin.maintenance-requests.edit', $req) }}" class="mr-btn">Kelola</a>
                            <form method="POST" action="{{ route('admin.maintenance-requests.destroy', $req) }}" onsubmit="return confirm('Hapus pengajuan ini?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="mr-btn" style="color:#dc2626;">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mr-pagination">
                <span>Menampilkan {{ $requests->firstItem() }}-{{ $requests->lastItem() }} dari {{ $requests->total() }}</span>
                <div>
                    @if ($requests->onFirstPage()) <span class="disabled">←</span>
                    @else <a href="{{ $requests->previousPageUrl() }}">←</a> @endif
                    @if ($requests->hasMorePages()) <a href="{{ $requests->nextPageUrl() }}">→</a>
                    @else <span class="disabled">→</span> @endif
                </div>
            </div>
        </div>
    @endif
@endsection
