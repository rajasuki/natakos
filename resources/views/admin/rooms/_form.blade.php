@php
    $room = $room ?? null;
    $currentImage = $room?->main_image;
    $errorBag = isset($errors) ? $errors : null;
    $selectedFacilityIds = collect(old('facility_ids', $room?->facilities?->modelKeys() ?? []))
        ->map(fn ($id) => (string) $id)
        ->all();
@endphp

<div class="card form-card">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid grid-two">
        @csrf

        @isset($method)
            @method($method)
        @endisset

        <div class="field field-full">
            <label for="name">Nama kamar</label>
            <input id="name" name="name" type="text" value="{{ old('name', $room?->name) }}" class="input" required>
            @if ($errorBag?->has('name'))
                <div class="field-error">{{ $errorBag->first('name') }}</div>
            @endif
            <div class="helper">Slug dibuat otomatis dari nama kamar saat data disimpan.</div>
            @if ($room?->slug)
                <div class="preview-meta">Slug saat ini: <strong>{{ $room->slug }}</strong></div>
            @endif
        </div>

        <div class="field">
            <label for="price">Harga</label>
            <input id="price" name="price" type="number" min="0" step="1" value="{{ old('price', $room?->price) }}" class="input" required>
            @if ($errorBag?->has('price'))
                <div class="field-error">{{ $errorBag->first('price') }}</div>
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
        </div>

        <div class="field">
            <label for="size">Ukuran</label>
            <input id="size" name="size" type="text" value="{{ old('size', $room?->size) }}" class="input" placeholder="Contoh: 3x4 meter">
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
            <textarea id="description" name="description" class="textarea" placeholder="Tulis deskripsi singkat kamar...">{{ old('description', $room?->description) }}</textarea>
            @if ($errorBag?->has('description'))
                <div class="field-error">{{ $errorBag->first('description') }}</div>
            @endif
        </div>

        <div class="field field-full">
            <label>Fasilitas</label>
            @if ($errorBag?->has('facility_ids') || $errorBag?->has('facility_ids.*'))
                <div class="field-error">{{ $errorBag->first('facility_ids') ?: $errorBag->first('facility_ids.*') }}</div>
            @endif
            <div class="helper">Pilih fasilitas yang tersedia untuk kamar ini. Anda bisa memilih lebih dari satu fasilitas.</div>

            <div class="checkbox-sections">
                @foreach ($facilityTypeLabels as $type => $label)
                    @php
                        $facilities = $facilityGroups[$type] ?? collect();
                    @endphp

                    <section class="checkbox-group">
                        <h3 class="checkbox-group-title">{{ $label }}</h3>

                        @if ($facilities->isEmpty())
                            <div class="helper">Belum ada fasilitas pada kelompok ini.</div>
                        @else
                            <div class="checkbox-grid">
                                @foreach ($facilities as $facility)
                                    <label class="checkbox-item" for="facility_{{ $facility->id }}">
                                        <input
                                            id="facility_{{ $facility->id }}"
                                            type="checkbox"
                                            name="facility_ids[]"
                                            value="{{ $facility->id }}"
                                            @checked(in_array((string) $facility->id, $selectedFacilityIds, true))
                                        >

                                        <span class="checkbox-copy">
                                            <strong>{{ $facility->name }}</strong>
                                            <span class="muted">{{ $facility->icon ?: 'Tanpa icon' }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>

        <div class="field field-full">
            <label for="main_image">Foto utama</label>
            <input id="main_image" name="main_image" type="file" accept="image/*" class="file-input">
            @if ($errorBag?->has('main_image'))
                <div class="field-error">{{ $errorBag->first('main_image') }}</div>
            @endif
            <div class="helper">Opsional. Jika diupload, file disimpan ke storage Laravel pada folder <code>rooms</code>.</div>

            @if ($currentImage)
                <div class="preview">
                    <img src="{{ asset('storage/'.$currentImage) }}" alt="{{ $room->name }}">
                    <div class="preview-meta">Path saat ini: <strong>{{ $currentImage }}</strong></div>
                </div>
            @endif
        </div>

        <div class="field field-full">
            <div class="form-actions">
                <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
                <a href="{{ route('admin.rooms.index') }}" class="button button-secondary">Kembali ke daftar kamar</a>
            </div>
        </div>
    </form>
</div>
