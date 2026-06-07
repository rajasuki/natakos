@extends('admin.layout')

@section('title', 'Log Aktivitas')
@section('eyebrow', 'Admin')
@section('page_title', 'Log Aktivitas')
@section('page_description', 'Riwayat aktivitas admin di sistem.')

@push('styles')
<style>
    .log-card { background:#fff; border:1px solid var(--ui-border); border-radius:14px; overflow:hidden; }
    .log-table { width:100%; border-collapse:collapse; min-width:700px; }
    .log-table th { padding:11px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); text-align:left; border-bottom:1px solid var(--ui-border); background:var(--gray-50); }
    .log-table td { padding:10px 16px; font-size:13px; color:var(--ui-ink); border-bottom:1px solid var(--ui-border); vertical-align:middle; }
    .log-table tbody tr:last-child td { border-bottom:none; }
    .log-table tbody tr:hover { background:var(--ui-canvas); }
    .log-action { display:inline-flex; align-items:center; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; }
    .log-action-created { background:#d1fae5; color:#065f46; }
    .log-action-updated { background:#dbeafe; color:#1e40af; }
    .log-action-deleted { background:#fee2e2; color:#991b1b; }
    .log-action-approved { background:#d1fae5; color:#065f46; }
    .log-action-rejected { background:#fee2e2; color:#991b1b; }
    .log-pagination { padding:14px 20px; border-top:1px solid var(--ui-border); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; font-size:13px; color:var(--ui-body); }
</style>
@endpush

@section('content')
    <div class="log-card">
        <div style="overflow-x:auto;">
            <table class="log-table">
                <thead>
                    <tr><th>Waktu</th><th>Admin</th><th>Aksi</th><th>Deskripsi</th></tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td style="white-space:nowrap;">{{ \App\Support\UiFormatter::date($log->created_at, 'd M Y H:i') }}</td>
                            <td>{{ $log->user?->name ?: 'Sistem' }}</td>
                            <td><span class="log-action log-action-{{ $log->action }}">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--ui-body);padding:32px;">Belum ada aktivitas tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="log-pagination">
            <span>Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} log</span>
            <div>
                @if ($logs->onFirstPage()) <span class="disabled">←</span>
                @else <a href="{{ $logs->previousPageUrl() }}">←</a> @endif
                @if ($logs->hasMorePages()) <a href="{{ $logs->nextPageUrl() }}">→</a>
                @else <span class="disabled">→</span> @endif
            </div>
        </div>
    </div>
@endsection
