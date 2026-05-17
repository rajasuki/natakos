@php
    $facility = $facility ?? null;
    $errorBag = isset($errors) ? $errors : null;
@endphp

<div class="card form-card">
    <form method="POST" action="{{ $action }}" class="grid grid-two">
        @csrf

        @isset($method)
            @method($method)
        @endisset

        <div class="field field-full">
            <label for="name">Nama fasilitas</label>
            <input id="name" name="name" type="text" value="{{ old('name', $facility?->name) }}" class="input" required>
            @if ($errorBag?->has('name'))
                <div class="field-error">{{ $errorBag->first('name') }}</div>
            @endif
            <div class="helper">Nama fasilitas wajib diisi dan kombinasi nama dengan type tidak boleh duplikat.</div>
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
            <input id="icon" name="icon" type="text" value="{{ old('icon', $facility?->icon) }}" class="input" placeholder="Contoh: wifi, bed, camera">
            @if ($errorBag?->has('icon'))
                <div class="field-error">{{ $errorBag->first('icon') }}</div>
            @endif
            <div class="helper">Opsional. Isi nama icon jika ingin dipakai pada tahap UI berikutnya.</div>
        </div>

        <div class="field field-full">
            <div class="form-actions">
                <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
                <a href="{{ route('admin.facilities.index') }}" class="button button-secondary">Kembali ke daftar fasilitas</a>
            </div>
        </div>
    </form>
</div>
