@extends('admin.layout')

@section('title', 'Riwayat Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Riwayat penghuni')
@section('page_description', 'Lihat penghuni yang sudah tidak aktif atau sudah check-out beserta riwayat kamar dan periode tinggalnya.')

@section('page_actions')
    <a href="{{ route('admin.tenants.index') }}" class="button button-secondary">Kembali ke penghuni aktif</a>
    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Penghuni baru</a>
    <a href="{{ route('admin.tenants.create-existing') }}" class="button button-secondary">Penghuni sudah punya akun</a>
@endsection

@section('content')
    @if ($tenants->isEmpty())
        <section class="empty-state">
            <h2>{{ $hasActiveFilters ? 'Tidak ada riwayat yang cocok' : 'Belum ada riwayat penghuni' }}</h2>
            <p>{{ $hasActiveFilters ? 'Ubah atau reset filter untuk melihat riwayat penghuni lainnya.' : 'Riwayat akan muncul setelah ada penghuni yang dinonaktifkan atau diproses check-out dari kamar.' }}</p>

            @if ($hasActiveFilters)
                <div class="empty-state-actions">
                    <a href="{{ route('admin.tenants.history') }}" class="button button-secondary">Reset filter</a>
                </div>
            @endif
        </section>
    @else
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Riwayat masa tinggal penghuni</h2>
                        <p class="card-copy">Gunakan halaman ini untuk melihat histori penghuni lama, kamar yang pernah ditempati, dan durasi tinggalnya.</p>
                    </div>

                    <div class="tag-list">
                        @foreach ($historyCounts as $label => $total)
                            <span class="tag">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.tenants.history') }}" class="toolbar-form">
                <input type="hidden" name="history" value="1">

                <div class="toolbar-grid">
                    <div class="field">
                        <label for="history_q">Cari penghuni</label>
                        <input id="history_q" name="q" type="text" value="{{ $filters['q'] }}" class="input" placeholder="Nama, email, nomor HP, kamar...">
                    </div>

                    <div class="field">
                        <label for="history_status">Status</label>
                        <select id="history_status" name="status" class="select">
                            <option value="">Semua status riwayat</option>
                            <option value="inactive" @selected($filters['status'] === 'inactive')>Tidak Aktif</option>
                            <option value="moved_out" @selected($filters['status'] === 'moved_out')>Sudah Keluar</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="history_room_id">Kamar</label>
                        <select id="history_room_id" name="room_id" class="select">
                            <option value="">Semua kamar</option>
                            @foreach ($filterRooms as $room)
                                <option value="{{ $room->id }}" @selected((string) $filters['room_id'] === (string) $room->id)>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="toolbar-actions">
                        <button type="submit" class="button button-primary">Terapkan filter</button>
                        <a href="{{ route('admin.tenants.history') }}" class="button button-secondary">Reset</a>
                        <a href="{{ route('admin.tenants.export', array_merge(request()->query(), ['history' => 1])) }}" class="button button-subtle">Export CSV</a>
                    </div>
                </div>
            </form>

            <div class="table-wrap">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Nama penghuni</th>
                            <th>Kamar terakhir</th>
                            <th>Periode tinggal</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                            <tr>
                                <td data-label="Nama penghuni">
                                    <p class="room-name">{{ $tenant->user?->name ?: 'User tidak tersedia' }}</p>
                                    <div class="muted">{{ $tenant->user?->email ?: '-' }}</div>
                                </td>
                                <td data-label="Kamar terakhir">{{ $tenant->room?->name ?: 'Kamar tidak tersedia' }}</td>
                                <td data-label="Periode tinggal">
                                    <div>{{ \App\Support\UiFormatter::date($tenant->start_date) }}</div>
                                    <div class="muted">s/d {{ \App\Support\UiFormatter::date($tenant->end_date) }}</div>
                                </td>
                                <td data-label="Status">
                                    <span class="badge badge-{{ $tenant->status }}">{{ $statusLabels[$tenant->status] ?? $tenant->status }}</span>
                                </td>
                                <td data-label="Pembayaran">{{ number_format($tenant->payments_count, 0, ',', '.') }} tagihan</td>
                                <td data-label="Catatan">{{ $tenant->notes ?: '-' }}</td>
                                <td data-label="Aksi">
                                    <div class="actions">
                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="button button-secondary">Lihat detail</a>
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
