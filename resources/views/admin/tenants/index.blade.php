@extends('admin.layout')

@section('title', 'Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Kelola penghuni')
@section('page_description', 'Atur akun penghuni, kamar yang ditempati, masa tinggal, dan status penghuni dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.tenants.history') }}" class="button button-secondary">Riwayat</a>
    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">+ Penghuni baru</a>
    <a href="{{ route('admin.tenants.create-existing') }}" class="button button-subtle">+ Assign kamar</a>
@endsection

@section('content')
    @if ($tenants->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada penghuni aktif yang cocok' : 'Belum ada penghuni aktif' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat penghuni aktif lain yang tercatat.' : 'Tambahkan penghuni aktif pertama untuk membuat akun tenant, menghubungkannya ke kamar, dan mengatur masa tinggal secara rapi.' }}</p>

            <div class="empty-state-actions">
                @if ($hasActiveFilters)
                    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Reset filter</a>
                @else
                    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni sekarang</a>
                @endif
            </div>
        </section>
    @else
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Daftar penghuni</h2>
                        <p class="card-copy">Kelola penghuni yang masih aktif menempati kamar, lalu proses check-out saat masa tinggal mereka selesai.</p>
                    </div>

                    <div class="tag-list">
                        @foreach ($tenantCounts as $label => $total)
                            <span class="tag">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.tenants.index') }}" class="toolbar-form">
                <div class="toolbar-grid">
                    <div class="field">
                        <label for="tenant_q">Cari penghuni</label>
                        <input id="tenant_q" name="q" type="text" value="{{ $filters['q'] }}" class="input" placeholder="Nama, email, nomor HP, kamar...">
                    </div>

                    <div class="field">
                        <label for="tenant_room_id">Kamar</label>
                        <select id="tenant_room_id" name="room_id" class="select">
                            <option value="">Semua kamar</option>
                            @foreach ($filterRooms as $room)
                                <option value="{{ $room->id }}" @selected((string) $filters['room_id'] === (string) $room->id)>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="toolbar-actions">
                        <button type="submit" class="button button-primary">Terapkan filter</button>
                        <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Reset</a>
                        <a href="{{ route('admin.tenants.export', request()->query()) }}" class="button button-subtle">Export CSV</a>
                    </div>
                </div>
            </form>

            <div class="table-wrap">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Nama penghuni</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Kamar</th>
                            <th>Tanggal masuk</th>
                            <th>Tanggal keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                            <tr>
                                <td data-label="Nama penghuni">
                                    <p class="room-name">{{ $tenant->user?->name ?: 'User tidak tersedia' }}</p>
                                    <div class="muted">Tenant ID #{{ $tenant->id }}</div>
                                </td>
                                <td data-label="Email">{{ $tenant->user?->email ?: '-' }}</td>
                                <td data-label="Nomor HP">{{ $tenant->user?->phone ?: '-' }}</td>
                                <td data-label="Kamar">{{ $tenant->room?->name ?: 'Kamar tidak tersedia' }}</td>
                                <td data-label="Tanggal masuk">{{ \App\Support\UiFormatter::date($tenant->start_date) }}</td>
                                <td data-label="Tanggal keluar">{{ \App\Support\UiFormatter::date($tenant->end_date) }}</td>
                                <td data-label="Status">
                                    <span class="badge badge-{{ $tenant->status }}">{{ $statusLabels[$tenant->status] ?? $tenant->status }}</span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="actions">
                                         <a href="{{ route('admin.tenants.edit', $tenant) }}" class="button button-secondary">Edit</a>

                                        @if ($tenant->status === 'active')
                                            <a href="{{ route('admin.tenants.checkout', $tenant) }}" class="button button-subtle">Check-out</a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" onsubmit="return confirm('Hapus penghuni ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-shell">
                {{ $tenants->links() }}
            </div>
        </section>
    @endif
@endsection
