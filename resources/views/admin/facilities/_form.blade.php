@php
    $facility = $facility ?? null;
    $errorBag = isset($errors) ? $errors : null;
@endphp

<div class="card form-card">
    <form method="POST" action="{{ $action }}" class="form-layout">
        @csrf

        @isset($method)
            @method($method)
        @endisset

        <section class="form-section">
            <div>
                <h2 class="form-section-title">Informasi fasilitas</h2>
                <p class="form-section-copy">Tetapkan nama, kelompok fasilitas, dan icon opsional agar katalog fasilitas tetap rapi.</p>
            </div>

            <div class="grid grid-two">
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
                    <div style="display:flex;align-items:center;gap:10px;">
                        <select id="icon" name="icon" class="select" style="flex:1;">
                            <option value="">— Default —</option>
                            @foreach ($iconOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('icon', $facility?->icon) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span id="icon-preview" class="material-symbols-outlined" style="font-size:22px;color:var(--ui-body);width:32px;text-align:center;">
                            {{ old('icon', $facility?->icon) ?: 'check_circle' }}
                        </span>
                    </div>
                    @if ($errorBag?->has('icon'))
                        <div class="field-error">{{ $errorBag->first('icon') }}</div>
                    @endif
                    <div class="helper">Pilih icon yang mewakili fasilitas ini. Icon akan tampil di kartu kamar dan halaman publik.</div>
                </div>

                @push('scripts')
                <script>
                    (function() {
                        var sel = document.getElementById('icon');
                        var preview = document.getElementById('icon-preview');
                        if (sel && preview) {
                            sel.addEventListener('change', function() {
                                preview.textContent = this.value || 'check_circle';
                            });
                        }
                    })();
                </script>
                @endpush
            </div>
        </section>

        <div class="form-actions">
            <button type="submit" class="button button-primary">{{ $submitLabel }}</button>
            <a href="{{ route('admin.facilities.index') }}" class="button button-secondary">Kembali ke daftar fasilitas</a>
        </div>
    </form>
</div>
