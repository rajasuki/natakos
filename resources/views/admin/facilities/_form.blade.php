@php
    $facility = $facility ?? null;
    $errorBag = isset($errors) ? $errors : null;
@endphp

<div class="card">
    <form method="POST" action="{{ $action }}">
        @csrf

        @isset($method)
            @method($method)
        @endisset

        <div class="card-head has-divider">
            <h2 class="card-title">Informasi fasilitas</h2>
            <p class="card-copy">Tetapkan nama, kelompok fasilitas, dan icon opsional agar katalog fasilitas tetap rapi.</p>
        </div>

        <div class="card-body">
            <div class="grid">
                <div class="field field-full">
                    <label for="name">Nama fasilitas</label>
                    <input id="name" name="name" type="text" class="input" value="{{ old('name', $facility?->name) }}" required>
                    @if ($errorBag?->has('name'))
                        <div class="field-error">{{ $errorBag->first('name') }}</div>
                    @endif
                    <div class="helper">Nama representatif yang akan ditampilkan kepada calon penghuni.</div>
                </div>

                <div class="field">
                    <label for="type">Type fasilitas</label>
                    <select id="type" name="type" class="select" required>
                        @foreach ($typeLabels as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $facility?->type ?? 'room') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errorBag?->has('type'))
                        <div class="field-error">{{ $errorBag->first('type') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label for="icon">Icon</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--gray-50);border:1px solid var(--ui-border);border-radius:var(--radius-md);">
                            <span id="icon-preview" class="material-symbols-outlined" style="font-size:22px;color:var(--ui-body);">
                                {{ old('icon', $facility?->icon) ?: 'check_circle' }}
                            </span>
                        </div>
                        <input id="icon" name="icon" type="text" class="input" value="{{ old('icon', $facility?->icon) }}" placeholder="check_circle" oninput="document.getElementById('icon-preview').textContent = this.value || 'check_circle'">
                    </div>
                    @if ($errorBag?->has('icon'))
                        <div class="field-error">{{ $errorBag->first('icon') }}</div>
                    @endif
                    <div class="helper">Masukkan identifier icon dari pustaka Google Material Symbols.</div>
                </div>
            </div>
        </div>

        <div style="padding:14px 22px;background:var(--gray-50);border-top:1px solid var(--ui-border);display:flex;justify-content:flex-end;gap:10px;border-radius:0 0 var(--radius-lg) var(--radius-lg);">
            <a href="{{ route('admin.facilities.index') }}" class="button button-secondary">Kembali ke daftar fasilitas</a>
            <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
