@extends('tenant.layout')

@section('title', 'Pengajuan Perbaikan')

@push('styles')
<style>
    .page-header { display:none; }
    .mr-header { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px; }
    .mr-header h1 { margin:0; font-size:22px; font-weight:700; color:var(--ui-ink); }
    .mr-card { background:#fff; border:1px solid var(--ui-border); border-radius:var(--radius-lg); overflow:hidden; }
    .mr-item { padding:16px 20px; border-bottom:1px solid var(--ui-border); }
    .mr-item:last-child { border-bottom:none; }
    .mr-item-title { margin:0 0 4px; font-size:14px; font-weight:600; color:var(--ui-ink); }
    .mr-item-desc { margin:0 0 8px; font-size:13px; color:var(--ui-body); line-height:1.6; }
    .mr-item-meta { display:flex; flex-wrap:wrap; gap:6px; align-items:center; font-size:12px; }
    .tenant-empty { display:flex; flex-direction:column; align-items:center; padding:32px; text-align:center; }
    .tenant-empty h2 { margin:0 0 8px; font-size:18px; font-weight:700; }
    .tenant-empty p { margin:0 0 16px; max-width:480px; color:var(--ui-body); line-height:1.7; }
</style>
@endpush

@section('content')
    <div style="padding-top:24px;">
        <div class="mr-header">
            <h1>Pengajuan Perbaikan</h1>
            <a href="{{ route('tenant.maintenance-requests.create') }}" class="button button-primary">Ajukan Perbaikan</a>
        </div>

        <div class="mr-card">
            @forelse ($requests as $req)
                <div class="mr-item">
                    <h3 class="mr-item-title">{{ $req->title }}</h3>
                    <p class="mr-item-desc">{{ $req->description }}</p>
                    <div class="mr-item-meta">
                        <span class="badge badge-{{ $req->priority }}">{{ $priorityLabels[$req->priority] ?? $req->priority }}</span>
                        <span class="badge badge-{{ $req->status }}">{{ $statusLabels[$req->status] ?? $req->status }}</span>
                        <span style="color:var(--ui-body);">{{ \App\Support\UiFormatter::date($req->created_at, 'd M Y') }}</span>
                    </div>
                    @if ($req->admin_notes)
                        <div style="margin-top:8px;padding:10px 14px;background:var(--gray-50);border-radius:8px;font-size:13px;color:var(--ui-body);">
                            <strong>Catatan admin:</strong> {{ $req->admin_notes }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="tenant-empty">
                    <h2>Belum ada pengajuan</h2>
                    <p>Anda belum mengajukan perbaikan apapun. Jika ada kerusakan, silakan ajukan melalui form di atas.</p>
                    <a href="{{ route('tenant.maintenance-requests.create') }}" class="button button-primary">Ajukan Perbaikan</a>
                </div>
            @endforelse
        </div>

        @if ($requests->hasPages())
            <div style="margin-top:16px;">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
