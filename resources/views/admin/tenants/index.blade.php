@extends('admin.layout')

@section('title', 'Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Kelola penghuni')
@section('page_description', 'Atur akun penghuni, kamar yang ditempati, masa tinggal, dan status penghuni dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni</a>
@endsection

@section('content')
    @php
        $tenantCounts = [
            'Total' => $tenants->count(),
            'Aktif' => $tenants->where('status', 'active')->count(),
            'Tidak Aktif' => $tenants->where('status', 'inactive')->count(),
            'Sudah Keluar' => $tenants->where('status', 'moved_out')->count(),
        ];
    @endphp

    @if ($tenants->isEmpty())
        <section class="empty-state">
            <h2>Belum ada penghuni</h2>
            <p>Tambahkan penghuni pertama untuk membuat akun tenant, menghubungkannya ke kamar, dan mengatur masa tinggal secara rapi.</p>

            <div class="empty-state-actions">
                <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni sekarang</a>
            </div>
        </section>
    @else
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Daftar penghuni</h2>
                        <p class="card-copy">Kelola akun penghuni, status tinggal, dan keterkaitan penghuni dengan kamar secara jelas.</p>
                    </div>

                    <div class="tag-list">
                        @foreach ($tenantCounts as $label => $total)
                            <span class="tag">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

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
        </section>
    @endif
@endsection
