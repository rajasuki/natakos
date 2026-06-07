@extends('admin.layout')

@section('title', 'Akun Pengguna')
@section('eyebrow', 'Admin')
@section('page_title', 'Akun Pengguna')
@section('page_description', 'Daftar semua akun yang terdaftar di sistem, termasuk admin dan penghuni.')

@push('styles')
<style>
    .title-form { display:flex; gap:4px; align-items:center; flex-wrap:wrap; }
    .title-input { width:100px; padding:4px 6px; font-size:12px; border:1px solid var(--ui-border); border-radius:4px; }
    .title-select { padding:4px 6px; font-size:12px; border:1px solid var(--ui-border); border-radius:4px; background:#fff; }
    .title-save { padding:4px 10px; font-size:11px; }
    .user-title-badge { display:inline-block; font-size:11px; font-weight:600; padding:2px 8px; border-radius:6px; }
    .user-title-none { background:var(--ui-soft); color:var(--ui-body); }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body" style="padding-bottom:0;">
            <div class="metric-grid">
                <div class="metric-card is-info">
                    <div class="metric-accent-bar"></div>
                    <p class="metric-label">Total Akun</p>
                    <p class="metric-value">{{ $counts['total'] }}</p>
                </div>
                <div class="metric-card is-success">
                    <div class="metric-accent-bar"></div>
                    <p class="metric-label">Admin</p>
                    <p class="metric-value">{{ $counts['admin'] }}</p>
                </div>
                <div class="metric-card">
                    <div class="metric-accent-bar"></div>
                    <p class="metric-label">Penghuni</p>
                    <p class="metric-value">{{ $counts['tenant'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        @if ($users->isEmpty())
            <div class="card-body">
                <section class="empty-state">
                    <h2>Belum ada pengguna</h2>
                    <p>Belum ada akun yang terdaftar di sistem.</p>
                </section>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Kamar</th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <span style="width:32px;height:32px;border-radius:50%;background:var(--ui-accent-soft);color:var(--ui-accent);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                        <strong style="font-size:14px;">{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:13px;color:var(--ui-body);">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span style="font-size:13px;">{{ $user->phone ?: '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->role === 'admin' ? 'occupied' : 'available' }}">
                                        {{ $roleLabels[$user->role] ?? $user->role }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->tenant && $user->tenant->room)
                                        <span style="font-weight:500;">{{ $user->tenant->room->name }}</span>
                                    @elseif ($user->tenant)
                                        <span class="muted">Menunggu kamar</span>
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->tenant)
                                        <span class="badge badge-{{ $user->tenant->status === 'active' ? 'active' : 'inactive' }}">
                                            {{ $user->tenant->status === 'active' ? 'Aktif' : ($user->tenant->status === 'moved_out' ? 'Keluar' : 'Tidak Aktif') }}
                                        </span>
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.title', $user) }}" class="title-form">
                                        @csrf @method('PUT')
                                        <input type="text" name="title" class="title-input" value="{{ $user->title }}" placeholder="Title..." maxlength="100">
                                        <select name="title_effect" class="title-select">
                                            @foreach ($effectLabels as $val => $label)
                                                <option value="{{ $val }}" @selected($user->title_effect === $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="button button-sm button-subtle title-save">Simpan</button>
                                    </form>
                                </td>
                                <td>
                                    <span style="font-size:12px;color:var(--ui-body);">
                                        {{ $user->created_at ? \App\Support\UiFormatter::date($user->created_at, 'd M Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->role === 'tenant' && !$user->tenant)
                                        <a href="{{ route('admin.tenants.create-existing') }}" class="button button-sm button-primary">
                                            Assign kamar
                                        </a>
                                    @else
                                        <span class="muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-shell">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
