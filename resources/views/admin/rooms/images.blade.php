@extends('admin.layout')

@section('title', 'Galeri Kamar')
@section('eyebrow', 'Admin Kamar')
@section('page_title', 'Galeri foto kamar')
@section('page_description', 'Kelola foto tambahan untuk satu kamar tanpa mengubah foto utama yang tersimpan pada tabel rooms.')

@section('page_actions')
    <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Kembali ke daftar kamar</a>
    <a href="{{ route('admin.rooms.edit', $room) }}" class="button button-subtle">Edit kamar</a>
@endsection

@push('styles')
    <style>
        .gallery-admin-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .gallery-admin-card {
            display: grid;
            gap: 14px;
            padding: 18px;
            border-radius: 16px;
            background: var(--ui-canvas);
            border: 1px solid var(--ui-border);
            box-shadow: var(--ui-shadow-soft);
        }

        .gallery-admin-image {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            border-radius: 16px;
            background: var(--ui-soft);
            border: 1px solid var(--ui-border);
        }

        .gallery-admin-meta {
            display: grid;
            gap: 10px;
        }

        .gallery-admin-caption {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .gallery-admin-caption-muted {
            color: var(--ui-body);
        }

        @media (max-width: 767px) {
            .gallery-admin-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $galleryCount = $room->images->count();
        $errorBag = isset($errors) ? $errors : null;
    @endphp

    <div class="content-stack">
        <section class="card">
            <div class="card-head has-divider">
                <div class="split-actions">
                    <div>
                        <h2 class="card-title">Informasi kamar</h2>
                        <p class="card-copy">Halaman ini khusus untuk foto tambahan kamar. Foto utama tetap dikelola terpisah dari form edit kamar.</p>
                    </div>

                    <div class="tag-list">
                        <span class="tag">Foto tambahan: {{ number_format($galleryCount, 0, ',', '.') }}</span>
                        <span class="tag">Status: {{ $statusLabels[$room->status] ?? $room->status }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="grid grid-two">
                    <div class="preview-frame">
                        <div class="muted">Nama kamar</div>
                        <p class="room-name">{{ $room->name }}</p>
                        <div class="room-slug">/{{ $room->slug }}</div>
                    </div>

                    <div class="preview-frame">
                        <div class="muted">Ringkasan singkat</div>
                        <div class="meta-line">
                            <span class="badge badge-{{ $room->status }}">{{ $statusLabels[$room->status] ?? $room->status }}</span>
                            <span>{{ \App\Support\UiFormatter::currency($room->price) }}</span>
                            <span>Ukuran: {{ $room->size ?: '-' }}</span>
                            <span>Lantai: {{ $room->floor ?: '-' }}</span>
                        </div>
                        <div class="helper">Foto utama saat ini: {{ $room->main_image ? 'sudah ada' : 'belum ada' }}.</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card form-card" id="upload-foto-galeri">
            <form method="POST" action="{{ route('admin.rooms.images.store', $room) }}" enctype="multipart/form-data" class="form-layout">
                @csrf

                <section class="form-section">
                    <div>
                        <h2 class="form-section-title">Upload foto galeri</h2>
                        <p class="form-section-copy">Unggah satu atau beberapa foto tambahan kamar. File akan disimpan ke folder <code>room-images</code> pada storage Laravel.</p>
                    </div>

                    <div class="grid grid-two">
                        <div class="field field-full">
                            <label for="images">File gambar</label>
                            <input id="images" name="images[]" type="file" accept="image/*" multiple class="file-input" required>
                            @if ($errorBag?->has('images') || $errorBag?->has('images.*'))
                                <div class="field-error">{{ $errorBag->first('images') ?: $errorBag->first('images.*') }}</div>
                            @endif
                            <div class="helper">Gunakan file JPG, JPEG, PNG, atau WEBP dengan ukuran maksimal 2MB per file.</div>
                        </div>

                        <div class="field field-full">
                            <label for="caption">Caption opsional</label>
                            <input id="caption" name="caption" type="text" value="{{ old('caption') }}" class="input" maxlength="255" placeholder="Contoh: Sudut meja dan jendela kamar">
                            @if ($errorBag?->has('caption'))
                                <div class="field-error">{{ $errorBag->first('caption') }}</div>
                            @endif
                            <div class="helper">Jika Anda mengunggah beberapa file sekaligus, caption ini akan dipakai untuk semua foto pada pengiriman ini.</div>
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">Upload foto</button>
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="button button-secondary">Kembali ke edit kamar</a>
                </div>
            </form>
        </section>

        @if ($room->images->isEmpty())
            <section class="empty-state">
                <h2>Belum ada foto tambahan</h2>
                <p>Galeri kamar ini masih kosong. Unggah foto tambahan agar halaman detail kamar publik menampilkan lebih banyak sudut kamar.</p>

                <div class="empty-state-actions">
                    <a href="#upload-foto-galeri" class="button button-primary">Upload foto pertama</a>
                </div>
            </section>
        @else
            <section class="card">
                <div class="card-head has-divider">
                    <div>
                        <h2 class="card-title">Grid galeri foto</h2>
                        <p class="card-copy">Foto ditampilkan berdasarkan <code>sort_order</code> dan urutan unggah yang tersimpan pada tabel <code>room_images</code>.</p>
                    </div>
                </div>

                <div class="card-body">
                    <div class="gallery-admin-grid">
                        @foreach ($room->images as $image)
                            <article class="gallery-admin-card">
                                <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->caption ?: 'Foto kamar '.$room->name }}" class="gallery-admin-image">

                                <div class="gallery-admin-meta">
                                    <div class="meta-line">
                                        <span class="tag">Urutan {{ $image->sort_order }}</span>
                                        <span class="muted">{{ \App\Support\UiFormatter::date($image->created_at, 'd M Y H:i') }}</span>
                                    </div>

                                    <p class="gallery-admin-caption {{ $image->caption ? '' : 'gallery-admin-caption-muted' }}">
                                        {{ $image->caption ?: 'Tanpa caption.' }}
                                    </p>

                                    <form method="POST" action="{{ route('admin.rooms.images.destroy', [$room, $image]) }}" onsubmit="return confirm('Hapus foto galeri ini?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="button button-danger">Hapus gambar</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection
