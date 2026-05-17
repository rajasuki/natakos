@extends('admin.layout')

@section('title', 'Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Kelola kamar')
@section('page_description', 'Atur daftar kamar, harga sewa, status ketersediaan, dan foto utama kamar NATAKOS.')

@section('page_actions')
    <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar</a>
@endsection

@section('content')
    @php
        $roomCounts = [
            'Total' => $rooms->count(),
            'Tersedia' => $rooms->where('status', 'available')->count(),
            'Terisi' => $rooms->where('status', 'occupied')->count(),
            'Perbaikan' => $rooms->where('status', 'maintenance')->count(),
        ];
    @endphp

    @if ($rooms->isEmpty())
        <section class="empty-state">
            <h2>Belum ada kamar</h2>
            <p>Mulai dengan menambahkan kamar pertama agar admin dapat mengelola harga, status, dan foto utama kamar dari dashboard ini.</p>

            <div class="empty-state-actions">
                <a href="{{ route('admin.rooms.create') }}" class="button button-primary">Tambah kamar sekarang</a>
            </div>
        </section>
    @else
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Daftar kamar</h2>
                        <p class="card-copy">Pantau status ketersediaan, harga, fasilitas ringkas, dan tindakan cepat untuk setiap kamar.</p>
                    </div>

                    <div class="tag-list">
                        @foreach ($roomCounts as $label => $total)
                            <span class="tag">{{ $label }}: {{ number_format($total, 0, ',', '.') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Kamar</th>
                            <th>Harga</th>
                            <th>Ukuran</th>
                            <th>Lantai</th>
                            <th>Status</th>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr>
                                <td data-label="Foto">
                                    @if ($room->main_image)
                                        <img src="{{ asset('storage/'.$room->main_image) }}" alt="{{ $room->name }}" class="thumb">
                                    @else
                                        <div class="thumb thumb-placeholder">Belum ada foto</div>
                                    @endif
                                </td>
                                <td data-label="Kamar">
                                    <p class="room-name">{{ $room->name }}</p>
                                    <div class="room-slug">/{{ $room->slug }}</div>
                                </td>
                                <td data-label="Harga">{{ \App\Support\UiFormatter::currency($room->price) }}</td>
                                <td data-label="Ukuran">{{ $room->size ?: '-' }}</td>
                                <td data-label="Lantai">{{ $room->floor ?: '-' }}</td>
                                <td data-label="Status">
                                    <span class="badge badge-{{ $room->status }}">{{ $statusLabels[$room->status] ?? $room->status }}</span>
                                </td>
                                <td data-label="Fasilitas">
                                    @if ($room->facilities->isEmpty())
                                        <span class="muted">Belum dipilih</span>
                                    @else
                                        <div class="tag-list">
                                            @foreach ($room->facilities->take(4) as $facility)
                                                <span class="tag">{{ $facility->name }}</span>
                                            @endforeach

                                            @if ($room->facilities->count() > 4)
                                                <span class="tag tag-muted">+{{ $room->facilities->count() - 4 }} lagi</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <div class="actions">
                                        <a href="{{ route('admin.rooms.images.index', $room) }}" class="button button-subtle">Galeri</a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="button button-secondary">Edit</a>

                                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar ini?');">
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
