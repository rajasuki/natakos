@php
    $expense = $expense ?? null;
@endphp

<div class="card">
    <div class="card-body">
        <div class="form-layout grid-two">
            <div class="field">
                <label for="description">Deskripsi</label>
                <input id="description" name="description" type="text" class="input" value="{{ old('description', $expense?->description) }}" placeholder="Mis: Gaji satpam, ganti lampu, dll.">
                @error('description') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="amount">Jumlah (Rp)</label>
                <input id="amount" name="amount" type="number" class="input" value="{{ old('amount', $expense?->amount) }}" min="0">
                @error('amount') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="category">Kategori</label>
                <select id="category" name="category" class="select">
                    <option value="">Pilih kategori</option>
                    @foreach ($categoryLabels as $val => $label)
                        <option value="{{ $val }}" @selected(old('category', $expense?->category) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="date">Tanggal</label>
                <input id="date" name="date" type="date" class="input" value="{{ old('date', $expense?->date?->format('Y-m-d')) }}">
                @error('date') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field field-full">
                <label for="notes">Catatan (opsional)</label>
                <textarea id="notes" name="notes" class="textarea" style="min-height:80px;">{{ old('notes', $expense?->notes) }}</textarea>
                @error('notes') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>
