@extends('admin.layout')

@section('title', 'Penghuni')
@section('eyebrow', 'Admin Penghuni')
@section('page_title', 'Kelola penghuni')
@section('page_description', 'Atur akun penghuni, kamar yang ditempati, masa tinggal, dan status penghuni dari dashboard admin.')

@section('page_actions')
    <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni</a>
@endsection

@section('content')
    @if ($tenants->isEmpty())
        <section class="empty-state">
            <h2>Belum ada penghuni</h2>
            <p>Tambahkan penghuni pertama untuk membuat akun tenant, menghubungkannya ke kamar, dan mengatur masa tinggal secara rapi.</p>
            <a href="{{ route('admin.tenants.create') }}" class="button button-primary">Tambah penghuni sekarang</a>
        </section>
    @else
        <section class="card">
            <div class="table-wrap">
                <table>
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
                                <td>
                                    <p class="room-name">{{ $tenant->user->name }}</p>
                                    <div class="muted">Tenant ID #{{ $tenant->id }}</div>
                                </td>
                                <td>{{ $tenant->user->email }}</td>
                                <td>{{ $tenant->user->phone ?: '-' }}</td>
                                <td>{{ $tenant->room->name }}</td>
                                <td>{{ $tenant->start_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $tenant->end_date?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $tenant->status }}">{{ $statusLabels[$tenant->status] ?? $tenant->status }}</span>
                                </td>
                                <td>
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
