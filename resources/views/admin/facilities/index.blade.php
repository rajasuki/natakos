@extends('admin.layout')

@section('title', 'Fasilitas')
@section('eyebrow', 'Admin Fasilitas')
@section('page_title', 'Kelola fasilitas')
@section('page_description', 'Atur daftar fasilitas kamar dan fasilitas umum yang tersedia di NATAKOS.')

@section('page_actions')
    <a href="{{ route('admin.facilities.create') }}" class="button button-primary">Tambah fasilitas</a>
@endsection

@section('content')
    @if ($facilities->isEmpty())
        <section class="empty-state">
            <h2>Belum ada fasilitas</h2>
            <p>Tambahkan fasilitas pertama untuk mulai membangun katalog fasilitas kamar dan fasilitas umum di area admin.</p>
            <a href="{{ route('admin.facilities.create') }}" class="button button-primary">Tambah fasilitas sekarang</a>
        </section>
    @else
        <section class="card">
            <div class="table-wrap">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Nama fasilitas</th>
                            <th>Type</th>
                            <th>Icon</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facilities as $facility)
                            <tr>
                                <td data-label="Nama fasilitas">
                                    <p class="room-name">{{ $facility->name }}</p>
                                    <div class="muted">ID #{{ $facility->id }}</div>
                                </td>
                                <td data-label="Type">
                                    <span class="badge badge-{{ $facility->type }}">{{ $typeLabels[$facility->type] ?? $facility->type }}</span>
                                </td>
                                <td data-label="Icon">{{ $facility->icon ?: '-' }}</td>
                                <td data-label="Dibuat">{{ \App\Support\UiFormatter::date($facility->created_at, 'd M Y H:i') }}</td>
                                <td data-label="Aksi">
                                    <div class="actions">
                                        <a href="{{ route('admin.facilities.edit', $facility) }}" class="button button-secondary">Edit</a>

                                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Hapus fasilitas ini?');">
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
