@extends('admin.layout')

@section('title', 'Monitor Pengguna')
@section('eyebrow', 'Admin')
@section('page_title', 'Monitor pengguna')
@section('page_description', 'Pantau aktivitas pengguna secara real-time — IP, perangkat, dan status online.')

@push('styles')
<style>
    .monitor-grid { display:grid; grid-template-columns:1fr; gap:16px; margin-bottom:24px; }
    @media(min-width:640px){ .monitor-grid { grid-template-columns:repeat(4,1fr); } }
    .monitor-stat { background:#fff; border:1px solid var(--ui-border); border-radius:12px; padding:16px; }
    .monitor-stat-label { font-size:12px; font-weight:600; color:var(--ui-body); margin:0 0 6px; }
    .monitor-stat-value { font-family:'Sora',sans-serif; font-size:28px; font-weight:800; color:var(--ui-ink); margin:0; }
    .monitor-stat-value.online { color:#059669; }
    .monitor-stat-value.tenant { color:var(--ui-accent); }
    .monitor-stat-value.admin { color:#7c3aed; }

    .monitor-card { background:#fff; border:1px solid var(--ui-border); border-radius:14px; overflow:hidden; }
    .monitor-card-head { padding:14px 20px; border-bottom:1px solid var(--ui-border); font-size:14px; font-weight:700; color:var(--ui-ink); display:flex; align-items:center; gap:8px; }
    .monitor-card-head .online-dot { width:8px; height:8px; border-radius:50%; background:#059669; display:inline-block; animation:pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
    .monitor-table { width:100%; border-collapse:collapse; min-width:800px; }
    .monitor-table th { padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--ui-body); text-align:left; border-bottom:1px solid var(--ui-border); background:var(--gray-50); }
    .monitor-table td { padding:10px 16px; font-size:13px; border-bottom:1px solid var(--ui-border); vertical-align:middle; }
    .monitor-table tbody tr:last-child td { border-bottom:none; }
    .monitor-table tbody tr:hover { background:var(--ui-canvas); }
    .monitor-name { font-weight:600; color:var(--ui-ink); }
    .monitor-role { display:inline-flex; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; }
    .monitor-role-admin { background:#ede9fe; color:#6d28d9; }
    .monitor-role-tenant { background:var(--ui-accent-soft); color:var(--ui-accent); }
    .monitor-status { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:600; }
    .monitor-status-dot { width:7px; height:7px; border-radius:50%; }
    .monitor-status-dot.online { background:#059669; }
    .monitor-status-dot.offline { background:#9ca3af; }
    .monitor-ip { font-family:'SF Mono','Consolas',monospace; font-size:12px; color:var(--ui-body); }
    .monitor-ua { font-size:11px; color:var(--ui-body); max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .monitor-time { font-size:12px; color:var(--ui-body); }
    .monitor-pagination { padding:14px 20px; border-top:1px solid var(--ui-border); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; font-size:13px; color:var(--ui-body); }
</style>
@endpush

@section('content')
    <div class="monitor-grid">
        <div class="monitor-stat">
            <p class="monitor-stat-label">Online Sekarang</p>
            <p class="monitor-stat-value online">{{ $onlineCount }}</p>
        </div>
        <div class="monitor-stat">
            <p class="monitor-stat-label">Total Penghuni</p>
            <p class="monitor-stat-value tenant">{{ $tenantCount }}</p>
        </div>
        <div class="monitor-stat">
            <p class="monitor-stat-label">Total Admin</p>
            <p class="monitor-stat-value admin">{{ $adminCount }}</p>
        </div>
        <div class="monitor-stat">
            <p class="monitor-stat-label">Batas Online</p>
            <p class="monitor-stat-value" style="font-size:14px;font-weight:600;">5 menit</p>
        </div>
    </div>

    @if ($onlineUsers->isNotEmpty())
        <div class="monitor-card" style="margin-bottom:24px;">
            <div class="monitor-card-head">
                <span class="online-dot"></span>
                Online Sekarang ({{ $onlineCount }})
            </div>
            <div style="overflow-x:auto;">
                <table class="monitor-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>IP Address</th>
                            <th>Perangkat</th>
                            <th>Terakhir Dilihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($onlineUsers as $user)
                            <tr>
                                <td><span class="monitor-name">{{ $user->name }}</span></td>
                                <td><span class="monitor-role monitor-role-{{ $user->role }}">{{ $user->role === 'admin' ? 'Admin' : 'Penghuni' }}</span></td>
                                <td><span class="monitor-ip">{{ $user->last_ip ?? '-' }}</span></td>
                                <td><span class="monitor-ua" title="{{ $user->last_user_agent }}">{{ $user->last_user_agent ?? '-' }}</span></td>
                                <td><span class="monitor-time">{{ $user->last_seen_at ? \App\Support\UiFormatter::date($user->last_seen_at, 'H:i:s') : '-' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="monitor-card">
        <div class="monitor-card-head">Semua Pengguna</div>
        <div style="overflow-x:auto;">
            <table class="monitor-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>IP Address</th>
                        <th>Perangkat</th>
                        <th>Terakhir Dilihat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allUsers as $user)
                        @php $isOnline = $user->last_seen_at && $user->last_seen_at->diffInMinutes() < 5; @endphp
                        <tr>
                            <td><span class="monitor-name">{{ $user->name }}</span></td>
                            <td><span class="monitor-role monitor-role-{{ $user->role }}">{{ $user->role === 'admin' ? 'Admin' : 'Penghuni' }}</span></td>
                            <td>
                                <span class="monitor-status">
                                    <span class="monitor-status-dot {{ $isOnline ? 'online' : 'offline' }}"></span>
                                    {{ $isOnline ? 'Online' : 'Offline' }}
                                </span>
                            </td>
                            <td><span class="monitor-ip">{{ $user->last_ip ?? '-' }}</span></td>
                            <td><span class="monitor-ua" title="{{ $user->last_user_agent }}">{{ $user->last_user_agent ?? '-' }}</span></td>
                            <td><span class="monitor-time">{{ $user->last_seen_at ? \App\Support\UiFormatter::date($user->last_seen_at, 'd M Y H:i') : 'Tidak pernah' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($allUsers->hasPages())
            <div class="monitor-pagination">
                <span>Menampilkan {{ $allUsers->firstItem() }}-{{ $allUsers->lastItem() }} dari {{ $allUsers->total() }} pengguna</span>
                <div style="display:flex;gap:4px;">
                    {{ $allUsers->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(function(){ location.reload(); }, 30000);
</script>
@endpush
