@extends('admin.layout')

@section('title', 'Tambah Badge')
@section('eyebrow', 'Admin Badge')
@section('page_title', 'Tambah badge baru')
@section('page_description', 'Buat badge baru yang bisa dipilih penghuni.')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.badges.store') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Badge</label>
                <input id="name" name="name" type="text" class="form-input" value="{{ old('name') }}" required maxlength="100">
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="effect" class="form-label">Efek Tampilan</label>
                <select id="effect" name="effect" class="form-input" required>
                    @foreach ($effectLabels as $val => $label)
                        <option value="{{ $val }}" @selected(old('effect') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('effect') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" class="form-input" rows="2" maxlength="500">{{ old('description') }}</textarea>
                @error('description') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="requirement_type" class="form-label">Syarat (opsional)</label>
                <select id="requirement_type" name="requirement_type" class="form-input">
                    <option value="">-- Tidak ada syarat --</option>
                    @foreach ($requirementTypes as $val => $label)
                        <option value="{{ $val }}" @selected(old('requirement_type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('requirement_type') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" id="requirement-value-group" style="display:none;">
                <label for="requirement_value" class="form-label">Nilai Syarat</label>
                <input id="requirement_value" name="requirement_value" type="number" class="form-input" value="{{ old('requirement_value') }}" min="1">
                @error('requirement_value') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    Aktif
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.badges.index') }}" class="button button-subtle">Batal</a>
                <button type="submit" class="button button-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('requirement_type').addEventListener('change', function() {
        document.getElementById('requirement-value-group').style.display = this.value ? 'block' : 'none';
    });
    if (document.getElementById('requirement_type').value) {
        document.getElementById('requirement-value-group').style.display = 'block';
    }
</script>
@endpush
