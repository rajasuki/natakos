@php
    $room = $room ?? null;
    $currentImage = $room?->main_image;
    $galleryImages = $room?->images ?? collect();
    $errorBag = isset($errors) ? $errors : null;
    $activeTenantCount = $room?->tenants?->count() ?? 0;
    $selectedFacilityIds = collect(old('facility_ids', $room?->facilities?->modelKeys() ?? []))
        ->map(fn ($id) => (string) $id)
        ->all();
    $isEdit = isset($room) && $room !== null;
@endphp

@push('styles')
<style>
    .room-form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (min-width: 900px) {
        .room-form-grid {
            grid-template-columns: 2fr 1.2fr;
        }
    }

    .room-form-left {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .room-form-right {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ── Compact checkbox grid 2-col ── */
    .room-facility-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 6px;
    }

    @media (min-width: 600px) {
        .room-facility-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .room-facility-group {
        display: grid;
        gap: 8px;
        padding: 14px;
        border-radius: 12px;
        background: var(--gray-50);
        border: 1px solid var(--ui-border);
    }

    .room-facility-group-title {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
    }

    .room-facility-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid var(--ui-border);
        transition: border-color .15s;
        cursor: pointer;
    }

    .room-facility-item:hover {
        border-color: var(--gray-300);
    }

    .room-facility-item input {
        accent-color: var(--ui-accent);
        flex-shrink: 0;
        margin: 0;
    }

    .room-facility-item-body {
        display: flex;
        flex-direction: column;
        gap: 1px;
        min-width: 0;
    }

    .room-facility-item-body strong {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
    }

    .room-facility-item-body .muted {
        font-size: 11px;
        color: var(--ui-body);
        line-height: 1.2;
    }

    /* ── Media card ── */
    .media-main {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: 160px;
        border-radius: 10px;
        overflow: hidden;
        background: var(--ui-soft);
        border: 1px solid var(--ui-border);
    }

    .media-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .media-main-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--ui-body);
        font-size: 13px;
        cursor: pointer;
    }

    .media-main-placeholder .material-symbols-outlined {
        font-size: 44px;
        opacity: .25;
    }

    .media-main-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.35);
        opacity: 0;
        transition: opacity .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        gap: 8px;
    }

    .media-main:hover .media-main-overlay {
        opacity: 1;
    }

    .media-main-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 14px;
        border-radius: 8px;
        border: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
        color: #fff;
    }

    .media-main-btn:hover {
        opacity: .9;
    }

    .media-main-btn-ubah {
        background: var(--ui-accent);
    }

    .media-main-btn-hapus {
        background: rgba(220,38,38,.85);
    }

    .media-main-btn-hapus:hover {
        background: #dc2626;
    }

    .media-main-btn .material-symbols-outlined {
        font-size: 16px;
    }

    /* ── Gallery grid ── */
    .media-gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    @media (min-width: 640px) {
        .media-gallery-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .media-gallery-item {
        position: relative;
        aspect-ratio: 1 / 1;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--ui-border);
        background: var(--ui-soft);
    }

    .media-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .media-gallery-item-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.25);
        opacity: 0;
        transition: opacity .15s;
        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
        padding: 3px;
    }

    .media-gallery-item:hover .media-gallery-item-overlay {
        opacity: 1;
    }

    .media-gallery-delete {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        border: none;
        background: rgba(0,0,0,.55);
        color: #fff;
        cursor: pointer;
        transition: background .15s;
    }

    .media-gallery-delete:hover {
        background: #dc2626;
    }

    .media-gallery-delete .material-symbols-outlined {
        font-size: 13px;
    }

    .media-gallery-add {
        aspect-ratio: 1 / 1;
        border-radius: 8px;
        border: 2px dashed var(--ui-border);
        background: var(--gray-50);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        cursor: pointer;
        color: var(--ui-body);
        transition: background .15s, border-color .15s, color .15s;
        font-size: 11px;
        font-weight: 600;
    }

    .media-gallery-add:hover {
        background: var(--gray-100);
        border-color: var(--ui-accent);
        color: var(--ui-accent);
    }

    .media-gallery-add .material-symbols-outlined {
        font-size: 24px;
    }

    .media-gallery-preview-new {
        border-color: var(--ui-accent);
        border-style: dashed;
    }
</style>
@endpush

<div class="card form-card" style="background:transparent;border:none;box-shadow:none;padding:0;">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="room-form">
        @csrf

        @isset($method)
            @method($method)
        @endisset

        <input type="hidden" name="remove_main_image" value="0" id="remove_main_image_flag">

        <div class="room-form-grid">
            {{-- ===== LEFT COLUMN ===== --}}
            <div class="room-form-left" style="display:flex;flex-direction:column;gap:24px;">

                {{-- Informasi Utama --}}
                <section class="card">
                    <div class="card-head has-divider">
                        <h2 class="card-title">
                            <span class="material-symbols-outlined" style="font-size:20px;color:var(--ui-accent);">info</span>
                            Informasi Utama
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-two">
                            <div class="field field-full">
                                <label for="name">Nama kamar <span class="muted">*</span></label>
                                <input id="name" name="name" type="text" value="{{ old('name', $room?->name) }}" class="input" required>
                                @if ($errorBag?->has('name'))
                                    <div class="field-error">{{ $errorBag->first('name') }}</div>
                                @endif
                                @if ($room?->slug)
                                    <div class="helper">Slug: <strong>{{ $room->slug }}</strong></div>
                                @else
                                    <div class="helper">Slug dibuat otomatis dari nama kamar saat data disimpan.</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="status">Status kamar</label>
                                <select id="status" name="status" class="select" required>
                                    @foreach ($statusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $room?->status ?? 'available') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if ($errorBag?->has('status'))
                                    <div class="field-error">{{ $errorBag->first('status') }}</div>
                                @endif
                                @if ($activeTenantCount > 0)
                                    <div class="helper">Kamar ini sedang punya penghuni aktif, jadi statusnya harus tetap <strong>Terisi</strong>.</div>
                                @else
                                    <div class="helper">Status <strong>Terisi</strong> dipakai otomatis saat kamar sudah dihubungkan ke penghuni aktif.</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="price">Harga sewa / bulan <span class="muted">*</span></label>
                                <input id="price" name="price" type="number" min="0" step="1" value="{{ old('price', $room?->price) }}" class="input" required>
                                @if ($errorBag?->has('price'))
                                    <div class="field-error">{{ $errorBag->first('price') }}</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="capacity">Kapasitas <span class="muted">*</span></label>
                                <select id="capacity" name="capacity" class="select" required>
                                    @php $selectedCap = old('capacity', $room?->capacity ?? 1); @endphp
                                    @foreach (range(1, 10) as $cap)
                                        <option value="{{ $cap }}" @selected((int) $selectedCap === $cap)>{{ $cap }} orang</option>
                                    @endforeach
                                </select>
                                @if ($errorBag?->has('capacity'))
                                    <div class="field-error">{{ $errorBag->first('capacity') }}</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="size">Ukuran</label>
                                <input id="size" name="size" type="text" value="{{ old('size', $room?->size) }}" class="input" placeholder="Contoh: 3x4 m">
                                @if ($errorBag?->has('size'))
                                    <div class="field-error">{{ $errorBag->first('size') }}</div>
                                @endif
                            </div>

                            <div class="field">
                                <label for="floor">Lantai</label>
                                <input id="floor" name="floor" type="text" value="{{ old('floor', $room?->floor) }}" class="input" placeholder="Contoh: 1">
                                @if ($errorBag?->has('floor'))
                                    <div class="field-error">{{ $errorBag->first('floor') }}</div>
                                @endif
                            </div>

                            <div class="field field-full">
                                <label for="description">Deskripsi</label>
                                <textarea id="description" name="description" class="textarea" placeholder="Tulis deskripsi singkat kamar..." rows="4">{{ old('description', $room?->description) }}</textarea>
                                @if ($errorBag?->has('description'))
                                    <div class="field-error">{{ $errorBag->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Fasilitas --}}
                <section class="card">
                    <div class="card-head has-divider">
                        <h2 class="card-title">
                            <span class="material-symbols-outlined" style="font-size:20px;color:var(--ui-accent);">chair</span>
                            Fasilitas
                        </h2>
                    </div>
                    <div class="card-body" style="padding:18px 20px;">
                        @if ($errorBag?->has('facility_ids') || $errorBag?->has('facility_ids.*'))
                            <div class="field-error" style="margin-bottom:12px;">{{ $errorBag->first('facility_ids') ?: $errorBag->first('facility_ids.*') }}</div>
                        @endif

                        <div style="display:grid;gap:12px;">
                            @foreach ($facilityTypeLabels as $type => $label)
                                @php
                                    $facilities = $facilityGroups[$type] ?? collect();
                                @endphp

                                <div class="room-facility-group">
                                    <h4 class="room-facility-group-title">{{ $label }}</h4>

                                    @if ($facilities->isEmpty())
                                        <div class="helper">Belum ada fasilitas pada kelompok ini.</div>
                                    @else
                                        <div class="room-facility-grid">
                                            @foreach ($facilities as $facility)
                                                <label class="room-facility-item" for="facility_{{ $facility->id }}">
                                                    <input
                                                        id="facility_{{ $facility->id }}"
                                                        type="checkbox"
                                                        name="facility_ids[]"
                                                        value="{{ $facility->id }}"
                                                        @checked(in_array((string) $facility->id, $selectedFacilityIds, true))
                                                    >
                                                    <span class="room-facility-item-body">
                                                        <strong>{{ $facility->name }}</strong>
                                                        <span style="display:flex;align-items:center;gap:4px;margin-top:2px;">
                                                            {!! \App\Support\FacilityIcon::render($facility, 'sm') !!}
                                                            <span class="muted">{{ $facility->icon ?: 'Tanpa icon' }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="room-form-right">

                {{-- Media Kamar --}}
                <section class="card">
                    <div class="card-head has-divider">
                        <h2 class="card-title">
                            <span class="material-symbols-outlined" style="font-size:20px;color:var(--ui-accent);">image</span>
                            Media Kamar
                        </h2>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">

                        {{-- Main Image --}}
                        <div>
                            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:8px;color:var(--ui-ink);">Foto Utama</label>

                            <div class="media-main">
                                @if ($currentImage)
                                    <img src="{{ asset('storage/'.$currentImage) }}" alt="{{ $room->name }}">
                                    <div class="media-main-overlay">
                                        <button type="button" class="media-main-btn media-main-btn-ubah" onclick="document.getElementById('main_image').click();">
                                            <span class="material-symbols-outlined">folder</span>
                                            Ubah
                                        </button>
                                        <button type="button" class="media-main-btn media-main-btn-hapus" onclick="if(confirm('Hapus foto utama ini?')){ document.getElementById('remove_main_image_flag').value='1'; document.getElementById('room-form').submit(); }">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="media-main-placeholder" onclick="document.getElementById('main_image').click();">
                                        <span class="material-symbols-outlined">image</span>
                                        <span>Belum ada foto utama</span>
                                    </div>
                                @endif
                            </div>

                            <input id="main_image" name="main_image" type="file" accept="image/*" style="display:none;">
                            @if ($errorBag?->has('main_image'))
                                <div class="field-error" style="margin-top:6px;">{{ $errorBag->first('main_image') }}</div>
                            @endif
                            <div class="helper" style="margin-top:6px;">Format: JPG, JPEG, PNG, WEBP. Maks. 2MB.</div>

                            @if ($currentImage)
                                <div class="helper" style="margin-top:2px;">Path saat ini: <strong>{{ $currentImage }}</strong></div>
                            @endif
                        </div>

                        {{-- Divider --}}
                        @if ($isEdit)
                            <hr class="tenant-info-sep" style="margin:0;">

                            {{-- Gallery --}}
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                    <label style="font-size:13px;font-weight:600;color:var(--ui-ink);margin:0;">Galeri Foto</label>
                                    @if ($galleryImages->isNotEmpty())
                                        <span class="muted" style="font-size:12px;" id="gallery-count">{{ $galleryImages->count() }} foto</span>
                                    @endif
                                </div>

                                <div class="media-gallery-grid" id="gallery-grid">
                                    @foreach ($galleryImages as $image)
                                        <div class="media-gallery-item" data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->caption ?: 'Foto galeri' }}">
                                            <div class="media-gallery-item-overlay">
                                                <form method="POST" action="{{ route('admin.rooms.images.destroy', [$room, $image]) }}" onsubmit="return confirm('Hapus foto galeri ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="media-gallery-delete" title="Hapus">
                                                        <span class="material-symbols-outlined">close</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="media-gallery-add" id="gallery-add-box" onclick="document.getElementById('gallery_file_input').click();">
                                        <span class="material-symbols-outlined">add_photo_alternate</span>
                                        <span>Tambah Foto</span>
                                    </div>
                                </div>

                                <input type="file" name="gallery_photos[]" accept="image/*" multiple id="gallery_file_input" hidden>

                                <div id="gallery-upload-error" class="field-error" style="display:none;margin-top:8px;"></div>
                                <div class="helper" style="margin-top:8px;">Format: JPG, JPEG, PNG, WEBP. Maks. 2MB per file.</div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Tips Card (edit only) --}}
                @if ($isEdit)
                    <div style="background:rgba(74,124,89,.06);border:1px solid rgba(74,124,89,.15);border-radius:12px;padding:16px;display:flex;gap:10px;">
                        <span class="material-symbols-outlined" style="font-size:20px;color:var(--ui-accent);flex-shrink:0;margin-top:1px;">tips_and_updates</span>
                        <div>
                            <strong style="display:block;margin-bottom:4px;font-size:13px;">Tips Fotografi Kamar</strong>
                            <p style="margin:0;font-size:12px;color:var(--ui-body);line-height:1.6;">Pastikan pencahayaan terang dan ambil sudut gambar yang memperlihatkan keseluruhan ruangan. Foto yang jelas meningkatkan minat calon penyewa hingga 40%.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions" style="margin-top:24px;">
            <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Kembali ke daftar kamar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    /* ── Main image preview ── */
    var mainInput = document.getElementById('main_image');
    var mainContainer = document.querySelector('.media-main');

    if (mainInput && mainContainer) {
        mainInput.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;

            if (['image/jpeg','image/png','image/webp'].indexOf(file.type) === -1) {
                alert('Format tidak didukung. Hanya JPG, JPEG, PNG, WEBP.');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar. Maksimal 2MB.');
                this.value = '';
                return;
            }

            var url = URL.createObjectURL(file);
            var existingImg = mainContainer.querySelector('img');

            if (existingImg) {
                existingImg.src = url;
            } else {
                buildMainPreview(url);
            }
        });
    }

    function buildMainPreview(url) {
        var img = document.createElement('img');
        img.src = url;
        img.alt = 'Preview';

        var overlay = document.createElement('div');
        overlay.className = 'media-main-overlay';

        var ubahBtn = document.createElement('button');
        ubahBtn.type = 'button';
        ubahBtn.className = 'media-main-btn media-main-btn-ubah';
        ubahBtn.innerHTML = '<span class="material-symbols-outlined">folder</span> Ubah';
        ubahBtn.addEventListener('click', function() {
            document.getElementById('main_image').click();
        });

        var hapusBtn = document.createElement('button');
        hapusBtn.type = 'button';
        hapusBtn.className = 'media-main-btn media-main-btn-hapus';
        hapusBtn.innerHTML = '<span class="material-symbols-outlined">close</span>';
        hapusBtn.addEventListener('click', function() {
            document.getElementById('main_image').value = '';
            mainContainer.innerHTML = '';
            var ph = document.createElement('div');
            ph.className = 'media-main-placeholder';
            ph.innerHTML = '<span class="material-symbols-outlined">image</span><span>Belum ada foto utama</span>';
            ph.addEventListener('click', function() {
                document.getElementById('main_image').click();
            });
            mainContainer.appendChild(ph);
        });

        overlay.appendChild(ubahBtn);
        overlay.appendChild(hapusBtn);

        mainContainer.innerHTML = '';
        mainContainer.appendChild(img);
        mainContainer.appendChild(overlay);
    }

    /* ── Gallery preview (form-based, no AJAX) ── */
    var galleryInput = document.getElementById('gallery_file_input');
    var grid = document.getElementById('gallery-grid');
    var errorEl = document.getElementById('gallery-upload-error');

    if (galleryInput && grid) {
        galleryInput.addEventListener('change', function() {
            var files = Array.from(this.files);
            if (files.length === 0) return;

            if (errorEl) {
                errorEl.style.display = 'none';
                errorEl.textContent = '';
            }

            /* Clear previous previews */
            var prevPreviews = grid.querySelectorAll('.gallery-form-preview');
            for (var p = 0; p < prevPreviews.length; p++) {
                prevPreviews[p].remove();
            }

            /* Validate */
            var addBox = document.getElementById('gallery-add-box');
            var allowedTypes = ['image/jpeg','image/png','image/webp'];
            var maxSize = 2 * 1024 * 1024;
            var hasError = false;

            for (var i = 0; i < files.length; i++) {
                var f = files[i];
                if (allowedTypes.indexOf(f.type) === -1) {
                    if (errorEl) {
                        showError('Format tidak didukung: ' + f.name + '. Hanya JPG, JPEG, PNG, WEBP.');
                    }
                    hasError = true;
                    continue;
                }
                if (f.size > maxSize) {
                    if (errorEl) {
                        showError(f.name + ' melebihi batas 2MB.');
                    }
                    hasError = true;
                    continue;
                }

                var url = URL.createObjectURL(f);
                var div = document.createElement('div');
                div.className = 'media-gallery-item gallery-form-preview';
                div.innerHTML = '<img src="' + url + '" alt="Preview">'
                    + '<div class="media-gallery-item-overlay">'
                    + '<button type="button" class="media-gallery-delete gallery-form-remove" title="Hapus">'
                    + '<span class="material-symbols-outlined">close</span>'
                    + '</button></div>';

                div.querySelector('.gallery-form-remove').addEventListener('click', function() {
                    URL.revokeObjectURL(url);
                    div.remove();
                    updateGalleryCount();
                });

                grid.insertBefore(div, addBox);
            }

            if (hasError) {
                this.value = '';
            }

            updateGalleryCount();
        });
    }

    function showError(msg) {
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        }
    }

    function updateGalleryCount() {
        var countSpan = document.getElementById('gallery-count');
        if (!countSpan) return;
        var existing = grid.querySelectorAll('.media-gallery-item[data-image-id]').length;
        var previews = grid.querySelectorAll('.gallery-form-preview').length;
        countSpan.textContent = (existing + previews) + ' foto';
    }

    /* ── Clean up object URLs on form submit ── */
    var form = document.getElementById('room-form');
    if (form) {
        form.addEventListener('submit', function() {
            var imgs = document.querySelectorAll('.gallery-form-preview img, .media-main img[src^="blob:"]');
            for (var i = 0; i < imgs.length; i++) {
                URL.revokeObjectURL(imgs[i].src);
            }
        });
    }
})();
</script>
@endpush
